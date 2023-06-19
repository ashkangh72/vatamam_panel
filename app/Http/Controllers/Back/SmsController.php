<?php

namespace App\Http\Controllers\Back;

use App\Models\Sms;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    /**
     * @param Sms $sms
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show(Sms $sms)
    {
        $this->authorize('statistics.sms');

        return view('back.statistics.sms.sms-show', compact('sms'));
    }
}
