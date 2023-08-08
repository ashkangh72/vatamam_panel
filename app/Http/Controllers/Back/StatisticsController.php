<?php

namespace App\Http\Controllers\Back;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{OrderStatisticsTrait, UserStatisticsTrait};
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\{Order, OrderItem, Sms};
use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    use OrderStatisticsTrait, UserStatisticsTrait;

    public function eCommerce()
    {
        return view('back.statistics.e-commerce');
    }

    /**
     * @throws AuthorizationException
     */
    public function orders()
    {
        $this->authorize('statistics.orders');

        return view('back.statistics.orders.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function users(): View
    {
        $this->authorize('statistics.users');

        return view('back.statistics.users.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function smsLog()
    {
        $this->authorize('statistics.sms');

        $sms = Sms::latest()->paginate(20);

        return view('back.statistics.sms.sms-log', compact('sms'));
    }

    public function eCommerceTotalSales(Request $request): JsonResponse
    {
        $orders = $this->_getOrdersQuery($request);

        $orders = $orders->where('status', 'paid')
            ->selectRaw("STR_TO_DATE(created_at, '%Y-%m-%e') date, SUM(price) as total_sale, SUM(shipping_cost) as total_shipping_cost,
                (SUM(price) - SUM(shipping_cost)) as gross_sale")
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get()
            ->each(function ($item, $key) {
                $item->date = tverta($item->date)->format('d M Y');
                $item->gross_sale = number_format($item->gross_sale);
                $item->total_shipping_cost = number_format($item->total_shipping_cost);
                $item->total_sale = number_format($item->total_sale);
            });

        return response()->json([
            'status' => 200,
            'orders' => $orders,
        ], 200);
    }

    public function eCommerceTotalSalesChart(Request $request): JsonResponse
    {
        $orders = $this->_getOrdersQuery($request);

        $orders = $orders->where('status', 'paid')
            ->selectRaw("STR_TO_DATE(created_at, '%Y-%m-%e') date, SUM(price) as total_sale,
                (SUM(price) - SUM(shipping_cost)) as gross_sale")
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->take(15)
            ->get()
            ->sortBy('date')
            ->each(function ($item, $key) {
                $item->date = tverta($item->date)->format('d M Y');
            });
        return response()->json([
            'status' => 200,
            'dates' => $orders->pluck('date'),
            'gross_sale' => $orders->pluck('gross_sale'),
            'total_sale' => $orders->pluck('total_sale'),
        ], 200);
    }

    public function eCommerceProductsSales(Request $request): JsonResponse
    {
        $startDateTime = $request->start_datetime ? Verta::parse($request->start_datetime)->datetime()->format('Y-m-d H:i:s') : NULL;
        $endDateTime = $request->end_datetime ? Verta::parse($request->end_datetime)->datetime()->format('Y-m-d H:i:s') : NULL;

        $orderItems = OrderItem::query()
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', 'orders.id');
            })
            ->leftJoin('products', function($join) {
                $join->on('order_items.product_id', 'products.id');
            })
            ->leftJoin('prices', function($join) {
                $join->on('order_items.price_id', 'prices.id');
            });
        if ($request->start_datetime && $request->end_datetime) {
            $orderItems = $orderItems->whereBetween('order_items.created_at', [$startDateTime, $endDateTime]);
        } else {
            $orderItems->take(20);
        }

        $orderItems = $orderItems->where('orders.status', 'paid')
            ->selectRaw("products.title as product_title, SUM(order_items.quantity) as total_quantity,
                SUM(order_items.quantity * prices.discount_price) as total_prices")
            ->groupBy('order_items.product_id')
            ->orderBy('total_quantity', 'DESC')
            ->get()
            ->each(function ($item, $key) {
                $item->total_prices = number_format($item->total_prices);
            });

        return response()->json([
            'status' => 200,
            'order_items' => $orderItems,
        ], 200);
    }

    private function _calculatePeriodStartDateTime(?string $period): array
    {
        switch ($period) {
            case 'last_seven_days':
                $startDateTime = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
                break;
            case 'last_thirty_Days':
                $startDateTime = Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
                break;
            case 'last_month':
                $startDateTime = Carbon::now()->subDays(60)->format('Y-m-d H:i:s');
                $endDateTime = Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
                break;
            default:
                $startDateTime = '2021-12-09 00:00:00';
                break;
        }

        return [$startDateTime, $endDateTime ?? Carbon::now()->format('Y-m-d H:i:s')];
    }

    private function _getOrdersQuery(Request $request): Builder
    {
        $startDateTime = $request->start_datetime ? Verta::parse($request->start_datetime)->datetime()->format('Y-m-d H:i:s') :now()->subDays(20)->format('Y-m-d H:i:s');
        $endDateTime = $request->end_datetime ? Verta::parse($request->end_datetime)->datetime()->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');

        $orders = Order::query();

        return $orders->whereBetween('created_at', [$startDateTime, $endDateTime]);
    }
}
