<?php

namespace App\Http\Controllers\Back;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\OrderCollection;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Order;
use Illuminate\Http\{JsonResponse, Request};

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
}
