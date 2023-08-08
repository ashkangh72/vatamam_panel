<?php

namespace App\Http\Controllers\Back;

use Illuminate\Contracts\View\View;
use App\Traits\{OrderStatisticsTrait, UserStatisticsTrait};
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Sms;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    use OrderStatisticsTrait, UserStatisticsTrait;

    /**
     * @throws AuthorizationException
     */
    public function orders(): View
    {
        $this->authorize('statistics.orders');

        return view('back.statistics.orders.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function users(): View
    {
        $this->authorize('statistics.users');

        return view('back.statistics.users.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function smsLog(): View
    {
        $this->authorize('statistics.sms');

        $sms = Sms::latest()->paginate(20);

        return view('back.statistics.sms.sms-log', compact('sms'));
    }
}
