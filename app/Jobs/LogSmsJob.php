<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\{FarazSms, KaveNegar};
use App\Enums\{SmsBoxHistoryTypeEnum, UserCountryEnum};
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private $response;
    private string $ip;
    private string $type;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $response
     * @param string $ip
     * @param string $type
     */
    public function __construct(User $user, $response, string $ip, string $type)
    {
        $this->user = $user;
        $this->response = $response;
        $this->ip = $ip;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        try {
            $userCountry = UserCountryEnum::from($this->user->country);
            if ($userCountry == UserCountryEnum::iran) {
                $message = FarazSms::getSms($this->response->bulk_id);

                if ($message->cost == 0 && $message->payback_cost == 0) {
                    $this->dispatchLater();
                    return;
                }

                $response = "شناسه: {$this->response->bulk_id}
                    هزینه: {$message->cost} ریال
                    هزینه برگشت: {$message->payback_cost} ریال";

                $smsLog = $this->createSmsLog($response, $message->message);

                if ($this->type == 'otp') return;

                if ($message->cost > 0 && $message->payback_cost == 0) {
                    $this->createSmsBoxHistory($smsLog, ceil($message->cost) / 10, "برداشت هزینه پیامک '" . $message->message . "'");
                }
            } else {
                $message = KaveNegar::getSms($this->response[0]->messageid);

                if (!$message || $message[0]->status <= 5) {
                    $this->dispatchLater();
                    return;
                }

                $response = "شناسه: {$message[0]->messageid}
                    هزینه: {$message[0]->cost} ریال
                    وضعیت {$message[0]->status}: {$message[0]->statustext} ";

                $smsLog = $this->createSmsLog($response, $message[0]->message);

                if ($this->type == 'otp') return;

                if ($message[0]->status == 10) {
                    $this->createSmsBoxHistory($smsLog, ceil($message[0]->cost) / 10, "برداشت هزینه پیامک '" . $message[0]->message . "'");
                }
            }
        } catch (Exception $exception) {
            Log::error("LogSmsJob failed for user_id {$this->user->id} \nbecause: {$exception->getMessage()}.");
        }
    }

    private function createSmsLog(string $response, string $message): Model
    {
        return $this->user->smsLogs()->create([
            'phone' => $this->user->phone,
            'ip' => $this->ip,
            'type' => $this->type,
            'response' => $response,
            'message' => $message
        ]);
    }

    private function createSmsBoxHistory($smsLog, $amount, string $description)
    {
        $smsBox = $this->user->smsBox;
        $smsBox->histories()->create([
            'sms_log_id' => $smsLog->id,
            'type' => SmsBoxHistoryTypeEnum::withdraw,
            'success' => true,
            'amount' => $amount,
            'description' => $description
        ]);
        $smsBox->refreshBalance();
    }

    private function dispatchLater()
    {
        dispatch(new LogSmsJob($this->user, $this->response, $this->ip, $this->type))
            ->onQueue('sms')
            ->delay(Carbon::now()->addMinutes(5));
    }
}
