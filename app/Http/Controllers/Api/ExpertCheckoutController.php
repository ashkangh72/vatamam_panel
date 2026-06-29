<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExpertCheckoutStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\ExpertCheckout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpertCheckoutController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        if ($request->api_token !== config('services.expert_checkout.token')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'user_id'     => 'required|integer|exists:users,id',
            'amount'      => 'required|numeric|min:1',
            'iban'        => 'nullable|string|max:26',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['status'] = ExpertCheckoutStatusEnum::pending_approval->value;

        ExpertCheckout::create($validated);

        return response()->json(['message' => 'درخواست برداشت با موفقیت ثبت شد'], 201);
    }
}
