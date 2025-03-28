<?php

namespace App\Http\Controllers\Back;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\OrderCollection;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Order;
use Illuminate\Http\{JsonResponse, Request, Response};

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

        $order->user->sendRefoundCheckNotification($order);
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

        $order->user->sendRefoundCheckNotification($order);

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
