<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Codedge\Updater\UpdaterManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeveloperController extends Controller
{
    public function showSettings()
    {

        $schedule_last_work = option('schedule_run');
        $schedule_run       = false;
        $random_str         = str_random(15);

        if ($schedule_last_work) {
            if (!is_object($schedule_last_work)) {
				$schedule_last_work = Carbon::createFromDate($schedule_last_work);
			}

            $diff = $schedule_last_work->diffInMinutes(now());
            $schedule_run = ($diff <= 2);
        }

        return view('back.developer.settings', compact('schedule_run', 'random_str'));
    }

    public function updateSettings(Request $request)
    {
        $developer_options = $request->except(['SELF_UPDATER_HTTP_PRIVATE_ACCESS_TOKEN']);

        foreach ($developer_options as $option => $value) {
            option_update($option, $value);
        }

        if ($request->app_debug_mode) {
            change_env('APP_DEBUG', 'true');
        } else {
            change_env('APP_DEBUG', 'false');
        }

        if ($request->enable_help_videos) {
            option_update('enable_help_videos', 'true');
        } else {
            option_update('enable_help_videos', 'false');
        }

        change_env('SELF_UPDATER_HTTP_PRIVATE_ACCESS_TOKEN', $request->SELF_UPDATER_HTTP_PRIVATE_ACCESS_TOKEN);

        if ($request->debugbar_enabled) {
            change_env('DEBUGBAR_ENABLED', 'true');
        } else {
            change_env('DEBUGBAR_ENABLED', 'false');
        }

        return response('success');
    }

    public function downApplication(Request $request)
    {
        $request->validate([
            'secret' => 'required|string'
        ]);

        Artisan::call("down --render='errors::503' --secret='$request->secret'");

        return response()->json(['secret' => $request->secret]);
    }

    public function upApplication()
    {
        Artisan::call("up");

        return response('success');
    }

    public function webpushNotification()
    {

        Artisan::call('webpush:vapid');

        return response('success');
    }



    public function updateApplication()
    {
        Artisan::call('updater:update');

        return response('success');
    }

    public function updaterAfter()
    {
        Artisan::call('updater:after');

        return response('success');
    }
}
