<?php

namespace App\Services;

use App\Enums\ExpertCheckoutStatusEnum;
use App\Models\ExpertCheckout;
use App\Models\ExpertCheckoutTransaction;
use Illuminate\Support\Facades\Log;

class ExpertCheckoutService
{
    public function processAccept(ExpertCheckout $expertCheckout): bool
    {
        $jibit = new JibitService();
        $jibitResult = $jibit->settlementToIban($expertCheckout->amount, $expertCheckout->iban);

        if (!$jibitResult) {
            Log::error('ExpertCheckoutService: Jibit returned null for checkout id=' . $expertCheckout->id);
            return false;
        }

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

        return true;
    }
}
