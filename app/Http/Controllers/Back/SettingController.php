<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\{Gateway, Province};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{Artisan, Storage};

class SettingController extends Controller
{
    public function showInformation()
    {
        $this->authorize('settings.information');

        $provinces = Province::all();

        $info_province = option('info_province_id');

        if ($info_province) {
            $cities = Province::find($info_province)->cities;
        } else {
            $cities = [];
        }

        return view('back.settings.information', compact('provinces', 'cities'));
    }

    public function updateInformation(Request $request)
    {
        $this->authorize('settings.information');

        $informations = $request->except(['info_icon', 'info_logo']);

        $this->validate($request, [
            'info_site_title' => 'required',
            'info_icon' => 'image|max:2048',
            'info_logo' => 'image|max:2048',
            'info_city_id' => 'exists:cities,id',
            'info_province_id' => 'exists:provinces,id',
        ]);

        if ($request->hasFile('info_icon')) {

            $imageName = time() . '_icon.' . $request->info_icon->getClientOriginalExtension();
            $request->info_icon->move(public_path('uploads/'), $imageName);

            $old_icon = option('info_icon');

            if ($old_icon) {
                Storage::disk('public')->delete($old_icon);
            }

            $informations['info_icon'] = '/uploads/' . $imageName;
        }

        if ($request->hasFile('info_logo')) {

            $imageName = time() . '_logo.' . $request->info_logo->getClientOriginalExtension();
            $request->info_logo->move(public_path('uploads/'), $imageName);

            $old_logo = option('info_logo');

            if ($old_logo) {
                Storage::disk('public')->delete($old_logo);
            }

            $informations['info_logo'] = '/uploads/' . $imageName;
        }

        $admin_route_prefix_changed = env('ADMIN_ROUTE_PREFIX') != $request->admin_route_prefix ? true : false;
        $admin_route_prefix = $request->admin_route_prefix;

        if ($admin_route_prefix_changed) {
            change_env('ADMIN_ROUTE_PREFIX', $request->admin_route_prefix);
            Artisan::call('route:cache');
        }

        foreach ($informations as $information => $value) {
            option_update($information, $value);
        }

        return response()->json([
            'admin_route_prefix'         => $admin_route_prefix,
            'admin_route_prefix_changed' => $admin_route_prefix_changed
        ]);
    }

    public function showSocials()
    {
        $this->authorize('settings.socials');

        return view('back.settings.socials');
    }

    public function updateSocials(Request $request)
    {
        $this->authorize('settings.socials');

        $socials = $request->all();

        foreach ($socials as $social => $value) {
            option_update($social, $value);
        }

        return response('success');
    }

    public function showGateways()
    {
        $this->authorize('settings.gateway');

        foreach (config('general.supported_gateways') as $key => $name) {
            Gateway::firstOrCreate(
                [
                    'key' => $key
                ],
                [
                    'name' => $name
                ]
            );
        }

        $gateways = Gateway::get();

        return view('back.settings.gateways', compact('gateways'));
    }

    public function updateGateways(Request $request)
    {
        $this->authorize('settings.gateway');

        $active_ids = [];

        if ($request->gateways) {
            foreach ($request->gateways as $id => $request_gateway) {
                if (!isset($request_gateway['is_active'])) {
                    continue;
                }

                $active_ids[] = $id;

                $gateway = Gateway::find($id);

                $gateway->update([
                    'name'        => $request_gateway['name'],
                    'ordering'    => $request_gateway['ordering'],
                    'is_active'   => true,
                ]);

                foreach ($request_gateway['configs'] as $key => $value) {
                    $gateway->configs()->updateOrCreate(
                        [
                            'key' => $key
                        ],
                        [
                            'value' => $value
                        ]
                    );
                }
            }
        }

        Gateway::whereNotIn('id', $active_ids)->update([
            'is_active' => false
        ]);

        return response('success');
    }

    public function showOthers()
    {
        $this->authorize('settings.others');

        return view('back.settings.others');
    }

    public function updateOthers(Request $request)
    {
        $this->authorize('settings.others');

        $env_options = [
            'PUSHER_APP_ID',
            'PUSHER_APP_KEY',
            'PUSHER_APP_SECRET',
            'PUSHER_APP_CLUSTER',
        ];

        $env = $request->only($env_options);

        change_env('BROADCAST_DRIVER', 'pusher');

        foreach ($env as $key => $value) {
            change_env($key, $value);
        }

        $others = $request->except($env_options);

        foreach ($others as $key => $value) {
            option_update($key, $value);
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

    /**
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function showFtp()
    {
        $this->authorize('settings.ftp');

        return view('back.settings.ftp');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function updateFtp(Request $request): Response
    {
        $this->authorize('settings.ftp');

        $keys = [
            'FTP_ACTIVE',
            'FTP_SSL',
            'FTP_HOST',
            'FTP_PORT',
            'FTP_ROOT',
            'FTP_USERNAME',
            'FTP_PASSWORD'
        ];

        foreach ($keys as $key) {
            if (in_array($key, ['FTP_ACTIVE', 'FTP_SSL'])) {
                change_env($key, $request->$key && $request->$key == 'on');
                continue;
            }

            change_env($key, $request->$key);
        }

        return response('success');
    }
}
