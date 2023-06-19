<?php

namespace App\Http\Controllers\Back;

use App\Models\{MarketingRequest, User};
use Illuminate\Http\{Request, JsonResponse};
use App\Events\MarketingRequestStatusChangeEvent;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\Access\AuthorizationException;

class MarketingController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('users.marketing-requests');

        $marketingRequests = MarketingRequest::latest()
            ->orderByRaw("FIELD(status, 'waiting', 'rejected', 'accepted')")
            ->paginate(15);

        return view('back.marketing-requests.index', compact('marketingRequests'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function accept(Request $request): JsonResponse
    {
        $this->authorize('users.marketing-requests.accept');

        $marketingRequest = MarketingRequest::find($request->marketing_request_id);

        User::where('id', $marketingRequest->user_id)
            ->update([
                'is_marketer' => TRUE
            ]);

        $updated = $marketingRequest->update(['status' => 'accepted']);

        event(new MarketingRequestStatusChangeEvent($marketingRequest, 'تایید شده'));

        return response()->json(NULL, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function reject(Request $request): JsonResponse
    {
        $this->authorize('users.marketing-requests.reject');

        $marketingRequest = MarketingRequest::find($request->marketing_request_id);

        User::where('id', $marketingRequest->user_id)
            ->update([
                'is_marketer' => FALSE
            ]);

        $updated = $marketingRequest->update(['status' => 'rejected']);

        event(new MarketingRequestStatusChangeEvent($marketingRequest, 'رد شده'));

        return response()->json(NULL, 200);
    }

}
