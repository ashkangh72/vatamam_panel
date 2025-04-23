<?php

namespace App\Services;

use App\Enums\WalletHistoryTypeEnum;
use App\Models\Option;
use App\Models\WalletCheckout;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\{Client, RequestOptions};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class SmsIr
 * @package App\Services\JibitService
 * @since 1.0
 * @author Parviz Ansaryan
 */
class JibitService
{
    protected string $accessToken;

    /**
     * @return void
     */
    public function __construct()
    {
        $jibitOptions = Option::whereIn('name', ['jibit_access_token', 'jibit_refresh_token', 'jibit_options_last_update'])->get();
        $jibitAccessToken = $jibitOptions->where('name', 'jibit_access_token')->first();
        $jibitRefreshToken = $jibitOptions->where('name', 'jibit_refresh_token')->first();
        $jibitOptionsLastUpdate = $jibitOptions->where('name', 'jibit_options_last_update')->first();

        if ($jibitOptions->count() && Carbon::parse($jibitOptionsLastUpdate->value)->addHours(23) > Carbon::now()) {
            $this->accessToken = $jibitAccessToken->value;
        } else {
            $tokens = $this->generateToken();
            $this->accessToken = $tokens->accessToken;

            Option::updateOrCreate(['name' => 'jibit_access_token'], ['value' => $tokens->accessToken]);
            Option::updateOrCreate(['name' => 'jibit_refresh_token'], ['value' => $tokens->refreshToken]);
            Option::updateOrCreate(['name' => 'jibit_options_last_update'], ['value' => Carbon::now()->format('Y-m-d H:i:s')]);
        }
    }

    /**
     * executes the main method.
     *
     * @param string $method
     * @param array $headers
     * @param array $data array of json data
     * @param string $url url
     * @return object Indicates the curl execute result
     */
    private static function execute(string $method, array $headers, array $data, string $url): object
    {
        $client = new Client();

        $result = $client->$method(
            $url,
            [
                (($method == 'get') ? RequestOptions::QUERY : RequestOptions::JSON) => $data,
                RequestOptions::HEADERS => $headers,
                'connect_timeout' => 30
            ]
        );
        Log::error($result->getBody());
        return json_decode($result->getBody());
    }

    private function generateToken()
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = [
            'apiKey' => config('jibit.api_key'),
            'secretKey' => config('jibit.secret_key'),
            'scopes' => ['SETTLEMENT']
        ];

        try {
            return self::execute(
                'post',
                $headers,
                $body,
                config('jibit.base_url_bank') . config('jibit.endpoints.tokens_generate')
            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }

    public function checkPhoneWithNationalCode(string $phone, string $nationalCode)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];
        $body = [
            'mobileNumber' => $phone,
            'nationalCode' => $nationalCode
        ];

        try {
            return self::execute(
                'get',
                $headers,
                $body,
                config('jibit.base_url') . config('jibit.endpoints.matching_service')
            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }

    public function iban(string $cardNumber)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];
        $body = [
            'iban' => 'true',
            'number' => $cardNumber,
        ];

        try {
            return self::execute(
                'get',
                $headers,
                $body,
                config('jibit.base_url') . config('jibit.endpoints.iban_info')
            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }

    public function checkIbanWithNationalCode(string $iban, string $nationalCode)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];
        $body = [
            'iban' => $iban,
            'nationalCode' => $nationalCode
        ];

        try {
            return self::execute(
                'get',
                $headers,
                $body,
                config('jibit.base_url') . config('jibit.endpoints.matching_service')
            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }

    public function settlement($amount, $user)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];
        $body = [
            'recordTrackId' => (string) Str::uuid(),
            // 'transferType' => '',
            // 'transferReason' => '',
            'sourceIban' => 'IR690540103447001694200606',
            'destinationIban' => 'IR' . $user->bankAccounts()->first()->iban,
            'amount' => $amount * 10,
            // 'paymentId' => '',
            // 'requestDescription' => '',
        ];

        Log::error($amount);
        Log::error($user->bankAccounts()->first()->iban);

        try {
            return self::execute(
                'post',
                $headers,
                $body,
                config('jibit.base_url_bank') . config('jibit.endpoints.settlement')
            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }

    public function checkSettlement($walletCheckoutTransaction)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];

        $body = [];

        try {
            $body = self::execute(
                'get',
                $headers,
                $body,
                config('jibit.base_url_bank') . config('jibit.endpoints.check_settlement') . '/' . $walletCheckoutTransaction->track_id
            );
            Log::error('check');
            $state = '';
            foreach ($body->records as $record) {
                if ($record->recordType == 'PRIME') {
                    $state = $record->state;
                }
            }

            $walletCheckoutTransaction->update(['status' => $state, 'records' => $body->records]);

            $walletCheckout = WalletCheckout::find($walletCheckoutTransaction->wallet_checkout_id);

            if ($state == 'TRANSFERRED') {
                $wallet = $walletCheckout->user->wallet;
                $wallet->histories()->create([
                    'type' => WalletHistoryTypeEnum::withdraw,
                    'amount' => $walletCheckout->amount,
                    'description' => 'برداشت از کیف پول',
                    'success' => true,
                    'balance' => $wallet->balance - $walletCheckout->amount
                ]);
                $wallet->refreshBalance();
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . json_encode($headers) . json_encode($body));
        }
    }
}
