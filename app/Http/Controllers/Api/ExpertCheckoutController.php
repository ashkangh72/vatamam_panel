<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExpertCheckoutStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\ExpertCheckout;
use App\Models\ExpertCheckoutTransaction;
use App\Services\ExpertCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpertCheckoutController extends Controller
{
    public function __construct(private ExpertCheckoutService $service) {}

    public function store(Request $request): JsonResponse
    {
        // if ($request->api_token !== config('services.expert_checkout.token')) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        $validated = $request->validate([
            'phone'       => 'required|string|max:20',
            'amount'      => 'required|numeric|min:1',
            'iban'        => 'nullable|string|max:26',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['name'] = $request->fullname;
        $validated['status'] = ExpertCheckoutStatusEnum::pending_approval->value;

        $expertCheckout = ExpertCheckout::create($validated);

        if (!$this->service->processAccept($expertCheckout)) {
            return response()->json(['message' => 'خطا در ارسال درخواست برداشت به سرویس جیبیت'], 500);
        }

        return response()->json(['message' => 'درخواست برداشت با موفقیت ثبت شد'], 201);
    }

    public function results(Request $request): JsonResponse
    {
        // if ($request->api_token !== config('services.expert_checkout.token')) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        $finalStatuses = ['TRANSFERRED', 'FAILED', 'TRANSFERRED_REVERTED', 'FAILED_WRONG'];

        $transactions = ExpertCheckoutTransaction::with('expertCheckout')
            ->whereIn('status', $finalStatuses)
            ->whereNull('notified_at')
            ->get();

        if ($transactions->isEmpty()) {
            return response()->json([]);
        }

        $response = $transactions->map(fn($t) => [
            'phone'  => $t->expertCheckout->phone,
            'name'   => $t->expertCheckout->name,
            'status' => $t->status === 'TRANSFERRED' ? 'success' : 'failed',
            'amount' => $t->total_amount / 10,
        ]);

        $transactions->each->update(['notified_at' => Carbon::now()]);

        return response()->json($response);
    }
}
