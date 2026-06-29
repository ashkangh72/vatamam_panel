<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\ExpertCheckoutStatusEnum;
use App\Models\ExpertCheckout;
use App\Models\ExpertCheckoutTransaction;
use App\Http\Controllers\Controller;
use App\Services\JibitService;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\Access\AuthorizationException;

class ExpertCheckoutController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('transactions.expert_checkouts');

        $expertCheckouts = ExpertCheckout::latest()->paginate(15);

        return view('back.expert_checkouts.index', compact('expertCheckouts'));
    }

    /**
     * @throws AuthorizationException
     */
    public function accept(Request $request): Response
    {
        $this->authorize('transactions.expert_checkouts.accept');

        $request->validate([
            'id' => [
                'required',
                'numeric',
                Rule::exists('expert_checkouts', 'id')
                    ->whereIn('status', [ExpertCheckoutStatusEnum::pending_approval->value, ExpertCheckoutStatusEnum::rejected->value]),
            ],
        ]);

        $expertCheckout = ExpertCheckout::find($request->id);

        $jibit = new JibitService();
        $jibitResult = $jibit->settlementToIban($expertCheckout->amount, $expertCheckout->iban);

        if ($jibitResult) {
            $state = 'DESTINATION_PROCESSING';
            foreach ($jibitResult->records as $record) {
                if ($record->recordType == 'PRIME') {
                    $state = $record->state;
                }
            }

            ExpertCheckoutTransaction::create([
                'reference_number'   => $jibitResult->referenceNumber,
                'track_id'           => $jibitResult->trackId,
                'owner_code'         => $jibitResult->ownerCode,
                'request_channel'    => $jibitResult->requestChannel,
                'type'               => $jibitResult->type,
                'source_iban'        => $jibitResult->sourceIban,
                'destination_iban'   => $jibitResult->destinationIban,
                'total_amount'       => $jibitResult->totalAmount,
                'created_at_jibit'   => $jibitResult->createdAt,
                'updated_at_jibit'   => $jibitResult->updatedAt,
                'records'            => $jibitResult->records,
                'status'             => $state,
                'expert_checkout_id' => $expertCheckout->id,
            ]);

            $expertCheckout->update(['status' => ExpertCheckoutStatusEnum::approved]);
        } else {
            return response(['success' => 400, 'message' => 'خطا در ارسال درخواست برداشت به سرویس جیبیت']);
        }

        return response(['success' => 200, 'message' => 'درخواست برداشت با موفقیت ارسال شد']);
    }

    /**
     * @throws AuthorizationException
     */
    public function reject(Request $request): Response
    {
        $this->authorize('transactions.expert_checkouts.reject');

        $request->validate([
            'id' => [
                'required',
                'numeric',
                Rule::exists('expert_checkouts', 'id')
                    ->whereIn('status', [ExpertCheckoutStatusEnum::pending_approval->value, ExpertCheckoutStatusEnum::approved->value]),
            ],
        ]);

        ExpertCheckout::find($request->id)->update(['status' => ExpertCheckoutStatusEnum::rejected]);

        return response(['success' => 200, 'message' => 'درخواست برداشت رد شد']);
    }
}
