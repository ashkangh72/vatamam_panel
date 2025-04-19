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
            $name = uniqid() . '_' . time() . '.' . $request->info_logo->getClientOriginalExtension();
            $request->info_logo->storeAs('/', $name);

            $old_logo = option('info_logo');

            if ($old_logo) {
                Storage::disk('public')->delete($old_logo);
            }

            $information['info_logo'] = '/uploads/' . $name;
        }

        // if ($request->hasFile('kasbokar')) {
        //     $name = uniqid() . '_' . time() . '.' . $request->kasbokar->getClientOriginalExtension();
        //     $request->kasbokar->storeAs('/', $name);

        //     $old_logo = option('kasbokar');

        //     if ($old_logo) {
        //         Storage::disk('public')->delete($old_logo);
        //     }

        //     $information['kasbokar'] = '/uploads/' . $name;
        // }

        if ($request->hasFile('logo_kasbokar')) {
            $name = uniqid() . '_' . time() . '.' . $request->logo_kasbokar->getClientOriginalExtension();
            $request->logo_kasbokar->storeAs('/', $name);

            $old_logo = option('logo_kasbokar');

            if ($old_logo) {
                Storage::disk('public')->delete($old_logo);
            }

            $information['logo_kasbokar'] = '/uploads/' . $name;
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

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function showSms(): View
    {
        $this->authorize('settings.sms');

        return view('back.settings.sms');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function updateSms(Request $request): Response
    {
        $this->authorize('settings.sms');

        $except = [
            // 'sms_on_order_paid',
            // 'sms_on_auction_end',
            // 'sms_on_auction_before_end',
            // 'sms_on_thanks_for_buy',
            // 'sms_on_winning_auction',
            // 'sms_on_auction_higher_bid',
            // 'sms_on_followed_auction',
            // 'sms_on_discount',
            // 'sms_on_auction_first_bid',
            // 'sms_on_auction_accept',
            // 'sms_on_auction_reject',
            // 'sms_on_auction_create',
            
            
            'sms_on_buy_product_to_seller',
            'sms_on_send_product_to_seller',
            'sms_on_satisfied_product_to_seller',
            'sms_on_unsatisfied_product_to_seller',
            'sms_on_win_auction_to_seller',
            'sms_on_end_auction_to_seller',
            
            'sms_on_send_product_to_buyer',
            'sms_on_accept_unsatisfied_product_to_buyer',
            'sms_on_win_auction_to_buyer',
            'sms_on_buy_product_to_buyer',
            
            'sms_on_accept_product_to_seller',
            'sms_on_accept_auction_to_seller',
            'sms_on_reject_product_to_seller',
            'sms_on_reject_auction_to_seller',
            
            'sms_on_notice_auction',
            'sms_on_transaction',
        ];

        $texts = [
            'sms_text_on_buy_product_to_seller',
            'sms_text_on_send_product_to_seller',
            'sms_text_on_satisfied_product_to_seller',
            'sms_text_on_unsatisfied_product_to_seller',
            'sms_text_on_win_auction_to_seller',
            'sms_text_on_end_auction_to_seller',

            'sms_text_on_send_product_to_buyer',
            'sms_text_on_accept_unsatisfied_product_to_buyer',
            'sms_text_on_win_auction_to_buyer',
            'sms_text_on_buy_product_to_buyer',

            'sms_text_on_accept_product_to_seller',
            'sms_text_on_accept_auction_to_seller',
            'sms_text_on_reject_product_to_seller',
            'sms_text_on_reject_auction_to_seller',

            'sms_text_on_notice_auction',
            'sms_text_on_transaction',
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
