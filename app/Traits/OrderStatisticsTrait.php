<?php

namespace App\Traits;

use App\Models\{Order, OrderAuction};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait OrderStatisticsTrait
{
    use StatisticsTrait;

    /**
     * @param $type
     * @param $period
     * @param $jalali_date
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public static function getStatisticsData($type, $period, $jalali_date, $start_date, $end_date): array
    {
        $data = [];

        switch ($period) {
            case "weekly":
            case "daily":
            {
                $data['chart_category'] = $jalali_date->format('%Y-%m- %d');
                break;
            }
            case "monthly":
            {
                $data['chart_category'] = $jalali_date->format('%B - %Y');
                break;
            }
            case "yearly":
            {
                $data['chart_category'] = $jalali_date->format('%Y');
                break;
            }
        }

        switch ($type) {
            case "orderCounts":
            {
                $data['success_orders'] = Order::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->paid()
                    ->count();
                break;
            }
            case "orderValues":
            {
                $data['success_orders'] = Order::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->paid()
                    ->sum('price');
                break;
            }
            case "orderUsers":
            {
                $data['success_orders'] = Order::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->paid()
                    ->distinct('user_id')
                    ->count('user_id');
                break;
            }
            case "orderAuctions":
            {
                $data['success_orders'] = OrderAuction::whereHas('order', function ($query) use ($start_date, $end_date) {
                    $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date)
                        ->paid();
                })->sum('quantity');

                break;
            }
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function orderValues(Request $request): JsonResponse
    {
        $this->authorize('statistics.orders');

        $data = $this->getPeriodData('orderValues', $request, [$this, "getStatisticsData"]);

        $total_count = 0;
        $total = 0;

        foreach ($data as $item) {
            $total += $item['success_orders'];
            $total_count += 1;
        }

        $avg = $total / $total_count;

        return response()->json(
            [
                'data' => $data,
                'meta' => [
                    'total' => formatPriceUnits($total),
                    'avg' => formatPriceUnits($avg),
                ],
                'status' => 'success',
            ],
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function orderCounts(Request $request): JsonResponse
    {
        $this->authorize('statistics.orders');

        $data = $this->getPeriodData('orderCounts', $request, [$this, "getStatisticsData"]);

        $total_count = 0;
        $total = 0;

        foreach ($data as $item) {
            $total += $item['success_orders'];
            $total_count += 1;
        }

        $avg = $total / $total_count;

        return response()->json(
            [
                'data' => $data,
                'meta' => [
                    'total' => formatPriceUnits($total),
                    'avg' => formatPriceUnits($avg),
                ],
                'status' => 'success',
            ],
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function orderAuctions(Request $request): JsonResponse
    {
        $this->authorize('statistics.orders');

        $data = $this->getPeriodData('orderAuctions', $request, [$this, "getStatisticsData"]);

        $total_count = 0;
        $total = 0;

        foreach ($data as $item) {
            $total += $item['success_orders'];
            $total_count += 1;
        }

        $avg = $total / $total_count;

        return response()->json(
            [
                'data' => $data,
                'meta' => [
                    'total' => formatPriceUnits($total),
                    'avg' => formatPriceUnits($avg),
                ],
                'status' => 'success',
            ],
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function orderUsers(Request $request): JsonResponse
    {
        $this->authorize('statistics.orders');

        $data = $this->getPeriodData('orderUsers', $request, [$this, "getStatisticsData"]);

        $total_count = 0;
        $total = 0;

        foreach ($data as $item) {
            $total += $item['success_orders'];
            $total_count += 1;
        }

        $avg = $total / $total_count;

        return response()->json(
            [
                'data' => $data,
                'meta' => [
                    'total' => formatPriceUnits($total),
                    'avg' => formatPriceUnits($avg),
                ],
                'status' => 'success',
            ],
        );
    }
}
