<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\ExpertCheckoutStatusEnum;
use App\Models\ExpertCheckout;
use App\Http\Controllers\Controller;
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

        $expertCheckouts = ExpertCheckout::with('user')->latest()->paginate(15);

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

        ExpertCheckout::find($request->id)->update(['status' => ExpertCheckoutStatusEnum::approved]);

        return response(['success' => 200, 'message' => 'درخواست برداشت با موفقیت تایید شد']);
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
