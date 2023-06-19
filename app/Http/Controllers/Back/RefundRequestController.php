<?php

namespace App\Http\Controllers\Back;

use App\Models\RefundedOrderItem;
use App\Events\RefundRequestStatusChangeEvent;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;

class RefundRequestController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('products.refund-requests');

        $refundRequests = RefundedOrderItem::latest()->paginate(15);

        return view('back.refund-requests.index', compact('refundRequests'));
    }

    /**
     * @param RefundedOrderItem $refund_request
     * @return string
     * @throws AuthorizationException
     */
    public function show(RefundedOrderItem $refund_request): string
    {
        $this->authorize('products.refund-requests.show');

        return view('back.refund-requests.show', compact('refund_request'))->render();
    }

    /**
     * @param RefundedOrderItem $refundedOrderItem
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(RefundedOrderItem $refundedOrderItem): Response
    {
        $this->authorize('products.refund-requests.delete');

        $refundedOrderItem->delete();

        return response('success');
    }

    /**
     * @param RefundedOrderItem $refund_request
     * @return Response
     * @throws AuthorizationException
     */
    public function accept(RefundedOrderItem $refund_request): Response
    {
        $this->authorize('products.refund-requests.accept');

        $updated = $refund_request->update(['status' => 'waiting_to_receive']);

        event(new RefundRequestStatusChangeEvent($refund_request,'تایید شده و منتظر دریافت'));

        return response('success');
    }

    /**
     * @param RefundedOrderItem $refund_request
     * @return Response
     * @throws AuthorizationException
     */
    public function reject(RefundedOrderItem $refund_request): Response
    {
        $this->authorize('products.refund-requests.reject');

        $updated = $refund_request->update(['status' => 'rejected']);

        event(new RefundRequestStatusChangeEvent($refund_request,'رد شده'));

        return response('success');
    }

    /**
     * @param RefundedOrderItem $refund_request
     * @return Response
     * @throws AuthorizationException
     */
    public function receive(RefundedOrderItem $refund_request): Response
    {
        $this->authorize('products.refund-requests.receive');

        $updated = $refund_request->update(['status' => 'received']);

        event(new RefundRequestStatusChangeEvent($refund_request,'دریافت شده'));

        return response('success');
    }
}
