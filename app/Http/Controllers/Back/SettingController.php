<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function showInformation(): View
    {
        $this->authorize('settings.information');

        return view('back.settings.information');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|AuthorizationException
     */
    public function updateInformation(Request $request): JsonResponse
    {
        $this->authorize('settings.information');

        $information = $request->except(['info_icon', 'info_logo']);

        $this->validate($request, [
            'info_site_title' => 'required',
            'info_logo' => 'image|max:2048',
        ]);

        if ($request->hasFile('info_logo')) {
            $name = uniqid() . '_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('/', $name);

            $old_logo = option('info_logo');

            if ($old_logo) {
                Storage::disk('public')->delete($old_logo);
            }

            $information['info_logo'] = '/uploads/' . $name;
        }

        foreach ($information as $info => $value) {
            if (is_null($value)) continue;
            option_update($info, $value);
        }

        return response()->json([], 200);
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function showSocials(): View
    {
        $this->authorize('settings.socials');

        return view('back.settings.socials');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function updateSocials(Request $request): Response
    {
        $this->authorize('settings.socials');

        $socials = $request->all();

        foreach ($socials as $social => $value) {
            if (is_null($value)) continue;
            option_update($social, $value);
        }

        return response('success');
    }

    public function showSms()
    {
        $this->authorize('settings.sms');

        return view('back.settings.sms');
    }

    public function updateSms(Request $request)
    {

        $this->authorize('settings.sms');

        $except = [
            'sms_on_user_register',
            'sms_on_order_not_paid',
            'sms_on_order_paid',
            'admin_sms_on_order_paid',
            'sms_on_order_status_changed',
            'sms_on_unbound_cart',
            'sms_on_stock_notify',
            'sms_on_refund_requests',
            'sms_on_periodic_marketing_commission_deposit',
            'sms_on_marketing_request_status_changed'
        ];

        $sms = $request->except($except);

        foreach ($sms as $key => $value) {
            option_update($key, $value);
        }

        foreach ($except as $option) {
            if ($request->$option) {
                option_update($option, 'on');
            } else {
                option_update($option, 'off');
            }
        }

        return response('success');
    }
}
