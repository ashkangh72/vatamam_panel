<?php

namespace App\Http\Controllers\Back;

use App\Events\{OrderCreated, OrderStatusChangedEvent};
use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Order\OrderStoreRequest;
use App\Http\Resources\Api\Product\ProductResource;
use App\Http\Resources\Datatable\OrderCollection;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\{Order, Price, Product, User, Warehouse, Province, Carrier};
use Illuminate\Http\{JsonResponse,Request};

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('orders.index');

        $warehouses = Warehouse::select('id', 'name')->get();

        return view('back.orders.index', compact('warehouses'));
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('orders.create');

        $provinces = Province::orderBy('ordering')->get();
        $carriers  = Carrier::active()->get();

        return view('back.orders.create', compact('provinces', 'carriers'));
    }

    public function store(OrderStoreRequest $request)
    {
        $user = User::firstOrCreate(
            [
                'username' => $request->username
            ],
            [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name
            ]
        );

        $order_price = 0;

        foreach ($request->products as $requestProduct) {
            $product = Product::find($requestProduct['id']);
            $price   = $product->prices()->find($requestProduct['price_id']);

            $orderItems[] = [
                'product_id'      => $product->id,
                'title'           => $product->title,
                'price'           => $price->discountPrice(),
                'real_price'      => $price->tomanPrice(),
                'quantity'        => $requestProduct['quantity'],
                'discount'        => $price->discount,
                'price_id'        => $price->id,
            ];

            $order_price += $price->discountPrice() * $requestProduct['quantity'];
        }

        $order_price += $request->shipping_cost;
        $order_price -= $request->discount_amount;

        $order = Order::create([
            'user_id'           => $user->id,
            'name'              => $request->first_name . ' ' . $request->last_name,
            'mobile'            => $request->username,
            'province_id'       => $request->province_id,
            'city_id'           => $request->city_id,
            'postal_code'       => $request->postal_code,
            'carrier_id'        => $request->carrier_id,
            'address'           => $request->address,
            'description'       => $request->description,
            'shipping_cost'     => $request->shipping_cost ?: 0,
            'status'            => 'paid',
            'shipping_status'   => $request->shipping_status,
            'discount_amount'   => $request->discount_amount,
            'price'             => $order_price,
            'gateway'           => 'cash'
        ]);

        $order->items()->createMany($orderItems);

        event(new OrderCreated($order));

        return response('success');
    }

    /**
     * @throws AuthorizationException
     */
    public function userInfo(Request $request)
    {
        $this->authorize('orders.create');

        $request->validate([
            'input' => 'required|in:username',
        ]);

        if (!$request->term) {
            return;
        }

        $input = $request->input('input');
        $term  = $request->input('term');

        switch ($input) {
            case "username": {
                $users = User::with('address')
                    ->where('username', 'like', "%$term%")
                    ->latest()
                    ->take(10)
                    ->get();
                break;
            }
        }

        return response()->json($users);
    }

    /**
     * @throws AuthorizationException
     */
    public function productsList(Request $request)
    {
        $this->authorize('orders.create');

        $term = $request->term;

        if (!$term) {
            return;
        }

        $products = Product::with('getPrices')
            ->available()
            ->where(function ($query) use ($term) {
                $query->where('title', 'like', "%$term%")->orWhere('title_en', 'like', "%$term%");
            })
            ->orderByStock()
            ->latest()
            ->take(10)
            ->get();

        return ProductResource::collection($products);
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('orders.index');

        $orders = Order::filter($request);

        $orders = datatable($request, $orders);

        return new OrderCollection($orders);
    }

    public function show(Order $order)
    {
        $this->authorize('orders.view');

        return view('back.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $this->authorize('orders.delete');

        $order->items()->delete();
        $order->transactions()->delete();

        $order->delete();
        toastr()->success('سفارش با موفقیت حذف شد.');

        return redirect()->route('admin.orders.index');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('orders.delete');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id',
        ]);

        foreach ($request->ids as $id) {
            $order = Order::find($id);
            $this->destroy($order);
        }

        return response('success');
    }

    public function multipleFactors(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:orders,id',
        ]);

        return response()->json(['route' => route('admin.orders.factors', ['ids' => $request->ids])], 200);
    }

    public function multipleShippingStatus(Request $request)
    {
        $this->authorize('orders.update');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:orders,id',
            'shipping_status' => 'required|string|in:pending,wating,sent,canceled',
        ]);

        $orders = Order::whereIn('id', $request->ids)->get();

        foreach ($orders as $order) {
            $order->update([
                'shipping_status' => $request->shipping_status
            ]);
            event(new OrderStatusChangedEvent($order, $request->shipping_status));
        }

        return response()->json([NULL, 200]);
    }

    public function shipping_status(Order $order, Request $request)
    {
        $this->authorize('orders.update');

        $this->validate($request, [
            'status' => 'required',
        ]);

        $order->update([
            'shipping_status' => $request->status
        ]);


        event(new OrderStatusChangedEvent($order, $request->status));


        return response('success');
    }

    public function notCompleted()
    {
        $this->authorize('orders.index');

        $prices = Price::whereHas('orderItems', function ($q) {
            $q->whereHas('order', function ($q2) {
                $q2->notCompleted();
            })->whereHas('product', function ($q3) {
                $q3->physical();
            });
        })->paginate(20);

        return view('back.orders.not-completed', compact('prices'));
    }

    public function factor(Order $order)
    {
        $this->authorize('orders.view');

        return view('back.orders.factor', compact('order'));
    }

    public function factors(Request $request)
    {
        $this->authorize('orders.view');

        return view('back.orders.multiple-factor', [
            'orders' => Order::whereIn('id', $request->ids)->get()
        ]);
    }

    public function postLabel(Order $order)
    {
        $this->authorize('orders.view');

        return view('back.orders.post-label', compact('order'));
    }
}
