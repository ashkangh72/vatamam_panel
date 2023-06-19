<?php

namespace App\Http\Controllers\Back;

use App\Exports\NewUsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{OrderStatisticsTrait, UserStatisticsTrait, ViewStatisticsTrait};
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\{Order, OrderItem, Sms, User, Viewer};
use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StatisticsController extends Controller
{
    use OrderStatisticsTrait, UserStatisticsTrait, ViewStatisticsTrait;

    public function views()
    {
        $views = Viewer::latest();

        if (auth()->user()->level != 'creator') {
            $views = $views->whereNull('user_id')->orWhere(function ($query) {
                $query->whereHas('user', function ($q1) {
                    $q1->where('level', '!=', 'creator');
                });
            });
        }

        $views = $views->paginate(20);

        return view('back.statistics.views', compact('views'));
    }

    public function viewsCharts()
    {
        return view('back.statistics.views.index');
    }

    public function eCommerce()
    {
        return view('back.statistics.e-commerce');
    }

    public function viewers()
    {
        $viewers = Viewer::latest()->whereDate('created_at', now())->get()->unique('user_id');

        return view('back.statistics.viewers', compact('viewers'));
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
    public function users()
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

    public function viewsChartsRouteViews(?string $period): JsonResponse
    {
        [$startDateTime, $endDateTime] = $this->_calculatePeriodStartDateTime($period);

        $routeViews = Viewer::where('created_at', '>', $startDateTime)
            ->where([
                ['path', 'NOT LIKE', '%'.'admin'.'%'],
                ['path', 'NOT LIKE', '%'.'.'.'%'],
                ['path', 'NOT LIKE', '%'.'captcha'.'%'],
            ])
            ->groupBy('path')
            ->selectRaw("path, COUNT(DISTINCT(id)) as views, COUNT(DISTINCT(user_id)) as users")
            ->orderBy('views', 'DESC')
            ->take(7)
            ->get();

        return response()->json([
            'status' => 200,
            'route_views' => $routeViews
        ], 200);
    }

    public function viewsChartsViews(string $period): JsonResponse
    {
        [$startDateTime, $endDateTime] = $this->_calculatePeriodStartDateTime($period);

        $data = Viewer::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->where([
                ['path', 'NOT LIKE', '%'.'admin'.'%'],
                ['path', 'NOT LIKE', '%'.'.'.'%'],
                ['path', 'NOT LIKE', '%'.'captcha'.'%'],
            ])
            ->selectRaw("STR_TO_DATE(created_at, '%Y-%m-%e') date, COUNT(DISTINCT(id)) as views, COUNT(DISTINCT(user_id)) as users")
            ->groupBy('date')
            ->orderBy('created_at')
            ->get()
            ->each(function ($item, $key) {
                $item->date = tverta($item->date)->format('Y-m-d');
            });

        return response()->json([
            'status' => 200,
            'dates' => $data->pluck('date'),
            'views' => $data->pluck('views'),
            'users' => $data->pluck('users')
        ], 200);
    }

    public function viewsChartsViewersPlatforms(): JsonResponse
    {
        $data = Viewer::where([
            ['path', 'NOT LIKE', '%'.'admin'.'%'],
            ['path', 'NOT LIKE', '%'.'.'.'%'],
            ['path', 'NOT LIKE', '%'.'captcha'.'%']
        ])
            ->selectRaw("JSON_EXTRACT(`options` , '$.platform') as platform, COUNT(DISTINCT(user_id)) AS users")
            ->groupBy('platform')
            ->get();

        foreach ($data as $key => $item) {
            $viewersPlatformsCount[] = ['name' => $item->platform, 'value' => $item->users];
        }

        return response()->json([
            'status' => 200,
            'viewers_platforms' => $data->pluck('platform'),
            'viewers_platforms_count' => $viewersPlatformsCount
        ], 200);
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

    public function userPurchaseCounts(Request $request): JsonResponse
    {
        $users = User::excludeCreator()
            ->withCount(['orders' => function (Builder $query) {
                $query->paid();
            }])
            ->selectRaw("count(*) as total_users")
            ->groupBy('orders_count')
            ->get();

        return response()->json([
            'status' => 200,
            'labels' => $users->pluck('orders_count'),
            'total_users' => $users->pluck('total_users')
        ], 200);
    }

    public function userPurchaseCountsExport(Request $request): BinaryFileResponse
    {
        $users = User::excludeCreator()
            ->withCount(['orders' => function (Builder $query) {
                $query->paid();
            }])
            ->filter($request)
            ->get();

        return Excel::download(new NewUsersExport('purchase_count', $users), 'orders.xlsx');
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
