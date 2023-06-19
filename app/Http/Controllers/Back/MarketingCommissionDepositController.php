<?php

namespace App\Http\Controllers\Back;

use Illuminate\Support\Facades\DB;
use App\Models\{MarketingCampaignCommission, MarketingCampaignCommissionDepositRequest, User, Wallet};
use Illuminate\Http\{Request, JsonResponse};
use App\Events\MarketingCampaignCommissionDepositRequestStatusChangeEvent;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\Access\AuthorizationException;

class MarketingCommissionDepositController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('users.marketing-commission-deposit-requests');

        $marketingCampaignCommissionDepositRequests = MarketingCampaignCommissionDepositRequest::latest()
            ->orderByRaw("FIELD(status, 'pending', 'rejected', 'accepted')")
            ->paginate(15);

        return view('back.marketing-commissions-deposit-requests.index', compact('marketingCampaignCommissionDepositRequests'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function accept(Request $request): JsonResponse
    {
        $this->authorize('users.marketing-commission-deposit-requests.accept');

        $marketingCampaignCommissionDepositRequest = MarketingCampaignCommissionDepositRequest::find($request->marketing_commissions_deposit_request_id);

        DB::transaction(function () use ($marketingCampaignCommissionDepositRequest) {
            $wallet = Wallet::where('user_id', $marketingCampaignCommissionDepositRequest->marketingCampaignCommission->user_id)->first();
            $wallet->histories()->create([
                'type'          => 'deposit',
                'source'        => 'admin',
                'status'        => 'success',
                'amount'        => $marketingCampaignCommissionDepositRequest->marketingCampaignCommission->getCommissionSum(),
                'description'   => 'واریز کمیسیون کمپین بازاریابی '.$marketingCampaignCommissionDepositRequest->marketingCampaignCommission->marketingCampaign->name
            ]);

            $wallet->refereshBalance();

            $marketingCampaignCommissionDepositRequest->marketingCampaignCommission->update(['status' => 'deposited_to_wallet']);
        });

        $updated = $marketingCampaignCommissionDepositRequest->update(['status' => 'accepted']);

        event(new MarketingCampaignCommissionDepositRequestStatusChangeEvent($marketingCampaignCommissionDepositRequest, 'تایید شده'));

        return response()->json(NULL, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function reject(Request $request): JsonResponse
    {
        $this->authorize('users.marketing-commission-deposit-requests.reject');

        $marketingCampaignCommissionDepositRequest = MarketingCampaignCommissionDepositRequest::find($request->marketing_commissions_deposit_request_id);

        $updated = $marketingCampaignCommissionDepositRequest->update(['status' => 'rejected']);

        event(new MarketingCampaignCommissionDepositRequestStatusChangeEvent($marketingCampaignCommissionDepositRequest, 'رد شده'));

        return response()->json(NULL, 200);
    }

}
