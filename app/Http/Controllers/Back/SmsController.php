<?php

namespace App\Http\Controllers\Back;

use App\Models\SmsLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    /**
     * @param SmsLog $sms
     * @return View
     * @throws AuthorizationException
     */
    public function show(SmsLog $sms): View
    {
        $this->authorize('statistics.sms');

        return view('back.statistics.sms.sms-show', compact('sms'));
    }
}
