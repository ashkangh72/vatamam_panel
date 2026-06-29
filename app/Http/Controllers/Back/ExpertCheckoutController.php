<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\ExpertCheckoutStatusEnum;
use App\Models\ExpertCheckout;
use App\Http\Controllers\Controller;
use App\Services\ExpertCheckoutService;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\Access\AuthorizationException;

class ExpertCheckoutController extends Controller
{
    public function __construct(private ExpertCheckoutService $service) {}

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

        if (!$this->service->processAccept($expertCheckout)) {
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
