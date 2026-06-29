<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExpertCheckoutStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\ExpertCheckout;
use App\Services\ExpertCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
