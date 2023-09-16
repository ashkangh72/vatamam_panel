<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\{WalletCheckoutStatusEnum, WalletHistoryTypeEnum};
use App\Models\WalletCheckout;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;

class WalletCheckoutController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('users.wallets.checkouts');

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
        $this->authorize('users.wallets.checkouts.accept');

        $request->validate([
            'id' => [
                'required', 'numeric', Rule::exists('wallet_checkouts', 'id')
                    ->whereIn('status', [WalletCheckoutStatusEnum::pending_approval, WalletCheckoutStatusEnum::rejected])
            ]
        ]);

        $walletCheckout = WalletCheckout::find($request->id);
        $wallet = $walletCheckout->user->wallet;
        $wallet->histories()->create([
            'type' => WalletHistoryTypeEnum::withdraw,
            'amount' => $walletCheckout->amount,
            'description' => 'برداشت از کیف پول',
            'success' => true,
            'balance' => $wallet->balance - $walletCheckout->amount
        ]);
        $wallet->refreshBalance();

        $walletCheckout->update(['status' => WalletCheckoutStatusEnum::approved]);

        return response('success');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function reject(Request $request): Response
    {
        $this->authorize('users.wallets.checkouts.reject');

        $request->validate([
            'id' => ['required', 'numeric', Rule::exists('wallet_checkouts', 'id')
                ->whereIn('status', [WalletCheckoutStatusEnum::pending_approval, WalletCheckoutStatusEnum::rejected])
            ]
        ]);

        $walletCheckout = WalletCheckout::find($request->id);
        $walletCheckout->update(['status' => WalletCheckoutStatusEnum::rejected]);

        return response('success');
    }
}
