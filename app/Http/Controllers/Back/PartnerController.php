<?php

namespace App\Http\Controllers\Back;

use App\Models\Partner;
use App\Enums\PartnerStatusEnum;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, Response};
use Illuminate\Contracts\View\View;

class PartnerController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('users.partners');

        $partners = Partner::latest()->paginate(15);

        return view('back.users.partners', compact('partners'));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function accept(Request $request): Response
    {
        $this->authorize('users.partners.accept');

        $request->validate([
            'id' => [
                'required', 'numeric', Rule::exists('partners', 'id')
                    ->whereIn('status', [PartnerStatusEnum::pending, PartnerStatusEnum::rejected])
            ]
        ]);

        Partner::where('id', $request->id)
            ->update(['status' => PartnerStatusEnum::accepted]);

        return response('success');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function reject(Request $request): Response
    {
        $this->authorize('users.partners.reject');

        $request->validate([
            'id' => [
                'required', 'numeric', Rule::exists('partners', 'id')
                    ->whereIn('status', [PartnerStatusEnum::pending, PartnerStatusEnum::rejected])
            ]
        ]);

        Partner::where('id', $request->id)
            ->update(['status' => PartnerStatusEnum::rejected]);

        return response('success');
    }
}
