<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\{WalletCheckoutStatusEnum, WalletHistoryTypeEnum};
use App\Models\WalletCheckout;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Controllers\Controller;
use App\Models\WalletCheckoutTransaction;
use App\Services\JibitService;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class WalletCheckoutController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('transactions.checkouts');

        $walletCheckouts = WalletCheckout::latest()->paginate(15);

        return view('back.wallets.checkouts', compact('walletCheckouts'));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function accept(Request $request): Response
    {
        $this->authorize('transactions.checkouts.accept');

        $request->validate([
            'id' => [
                'required',
                'numeric',
                Rule::exists('wallet_checkouts', 'id')
                    ->whereIn('status', [WalletCheckoutStatusEnum::pending_approval, WalletCheckoutStatusEnum::rejected])
            ]
        ]);

        $walletCheckout = WalletCheckout::find($request->id);


        //jibit
        $jibit = new JibitService();
        $jibitResult = $jibit->settlement($walletCheckout->amount, $walletCheckout->user);
        if ($jibitResult) {
            $state = 'DESTINATION_PROCESSING';
            foreach ($jibitResult->records as $record) {
                if($record->recordType == 'PRIME'){
                    $state = $record->state;
                }
            }

            WalletCheckoutTransaction::create([
                'reference_number' => $jibitResult->referenceNumber,
                'track_id' => $jibitResult->trackId,
                'owner_code' => $jibitResult->ownerCode,
                'request_channel' => $jibitResult->requestChannel,
                'type' => $jibitResult->type,
                'source_iban' => $jibitResult->sourceIban,
                'destination_iban' => $jibitResult->destinationIban,
                'total_amount' => $jibitResult->totalAmount,
                'created_at_jibit' => $jibitResult->createdAt,
                'updated_at_jibit' => $jibitResult->updatedAt,
                'records' => $jibitResult->records,
                'status' => $state,
                'wallet_checkout_id' => $walletCheckout->id,
            ]);
    
            $walletCheckout->update(['status' => WalletCheckoutStatusEnum::approved]);
        } else {
            return response(['success' => 400, 'message' => 'خطا در ارسال درخواست برداشت به سرویس جیبیت']);
        }


        return response(['success' => 200, 'message' => 'درخواست برداشت با موفقیت ارسال شد']);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function reject(Request $request): Response
    {
        $this->authorize('transactions.checkouts.reject');

        $request->validate([
            'id' => [
                'required',
                'numeric',
                Rule::exists('wallet_checkouts', 'id')
                    ->whereIn('status', [WalletCheckoutStatusEnum::pending_approval, WalletCheckoutStatusEnum::rejected])
            ]
        ]);

        $walletCheckout = WalletCheckout::find($request->id);
        $walletCheckout->update(['status' => WalletCheckoutStatusEnum::rejected]);

        return response('success');
    }
}
