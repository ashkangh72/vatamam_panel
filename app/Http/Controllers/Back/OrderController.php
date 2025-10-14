<?php

namespace App\Http\Controllers\Back;

use App\Enums\SafeBoxHistoryTypeEnum;
use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\OrderCollection;
use App\Models\Auction;
use App\Models\CommissionTariff;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Order;
use App\Models\VatamamWalletHistory;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('orders.index');

        return view('back.orders.index');
    }

    /**
     * @param Request $request
     * @return OrderCollection
     * @throws AuthorizationException
     */
    public function apiIndex(Request $request): OrderCollection
    {
        $this->authorize('orders.index');

        $orders = Order::filter($request);

        $orders = datatable($request, $orders);

        return new OrderCollection($orders);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Order $order): View
    {
        $this->authorize('orders.view');

        return view('back.orders.show', compact('order'));
    }

    public function multipleFactors(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id',
        ]);

        return response()->json(['route' => route('admin.orders.factors', ['ids' => $request->ids])], 200);
    }

    /**
     * @param Order $order
     * @return View
     * @throws AuthorizationException
     */
    public function factor(Order $order): View
    {
        $this->authorize('orders.view');

        return view('back.orders.factor', compact('order'));
    }

    /**
     * @param Order $order
     * @return Response
     * @throws AuthorizationException
     */
    public function acceptRefund(Order $order): Response
    {
        $this->authorize('orders.refund.accept');

        $order->refund()->update(['status' => 'accepted']);

        $order->user->sendAcceptRefoundCheckNotification($order);
        $order->seller->sendOrderUnSatisfiedNotification($order);

        return response('success');
    }

    /**
     * @param Order $order
     * @return Response
     * @throws AuthorizationException
     */
    public function rejectRefund(Order $order): Response
    {
        $this->authorize('orders.refund.reject');

        $order->refund()->update(['status' => 'rejected']);

        $order->user->sendRejectRefoundCheckNotification($order);

        $userSafeBox = $order->user->safeBox;
        $sellerWallet = $order->seller->wallet;

        $userAuctionGuaranteePaidAmounts = $userSafeBox->histories()
            ->where('type', SafeBoxHistoryTypeEnum::auction_guarantee)
            ->where('historiable_type', Auction::class)
            ->whereIn('historiable_id', $order->auctions->pluck('id'))
            ->success()
            ->sum('amount');

        $userOrderPaidAmounts = $userSafeBox->histories()
            ->where('type', SafeBoxHistoryTypeEnum::order)
            ->where('historiable_type', Order::class)
            ->where('historiable_id', $order->id)
            ->success()
            ->sum('amount');

        $totalUserPaidAmount = $userAuctionGuaranteePaidAmounts + $userOrderPaidAmounts;

        DB::transaction(function () use ($order, $userSafeBox, $sellerWallet, $totalUserPaidAmount) {
            $auction = $order->auctions()->first();
            $title = '';
            if (!is_null($auction)) {
                $title = $auction->getTitle();
            }

            $userSafeBox->histories()->create([
                'user_id' => $order->seller->id,
                'type' => SafeBoxHistoryTypeEnum::checkout,
                'amount' => $totalUserPaidAmount,
                'balance' => $userSafeBox->balance - $totalUserPaidAmount,
                'description' => 'برداشت مبلغ تضمین مزایده ها و سفارش شماره ' . $order->id . ' و واریز به کیف پول فروشنده ' . $order->seller->username . $title,
                'historiable_type' => Order::class,
                'historiable_id' => $order->id,
                'success' => true,
            ]);
            $userSafeBox->refreshBalance();

            $sellerWallet->histories()->create([
                'type' => WalletHistoryTypeEnum::income,
                'amount' => $totalUserPaidAmount,
                'balance' => $sellerWallet->balance + $totalUserPaidAmount,
                'description' => 'واریز مبلغ مزایده ها و سفارش شماره ' . $order->id . ' از صندوق امانت خریدار ' . $order->user->username . $title,
                'historiable_type' => Order::class,
                'historiable_id' => $order->id,
                'success' => true,
            ]);
            $sellerWallet->refreshBalance();

            $commissionTariff = CommissionTariff::where('min', '<=', $totalUserPaidAmount)->orderBy('min', 'DESC')->first();
            if ($commissionTariff && $commissionTariff->commission_percent > 0) {
                $commission = ($commissionTariff->commission_percent * $totalUserPaidAmount) / 100;
                $sellerWallet->histories()->create([
                    'type' => WalletHistoryTypeEnum::commission,
                    'amount' => $commission,
                    'balance' => $sellerWallet->balance - $commission,
                    'description' => 'برداشت مبلغ کمیسیون کالا ها در سفارش شماره ' . $order->id . ' از کیف پول فروشنده ' . $order->seller->username . $title,
                    'historiable_type' => Order::class,
                    'historiable_id' => $order->id,
                    'success' => true,
                ]);
                $sellerWallet->refreshBalance();


                //todo add commission to vatamam wallet
                VatamamWalletHistory::create([
                    'type' => WalletHistoryTypeEnum::commission,
                    'amount' => $commission,
                    'description' => 'برداشت مبلغ کمیسیون کالا ها در سفارش شماره ' . $order->id . ' از کیف پول فروشنده ' . $order->seller->username . $title,
                    'historiable_type' => Order::class,
                    'historiable_id' => $order->id,
                    'success' => true,
                ]);
            }
        });

        return response('success');
    }

    /**
     * @param Order $order
     * @return Response
     * @throws AuthorizationException
     */
    public function refundPayment(Order $order): Response
    {
        $this->authorize('orders.refund.payment');

        $order->refundPayment();

        $order->refund()->update(['refunded_payment' => true]);

        return response('success');
    }
}
