<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\VatamamWalletHistory;
use App\Models\Wallet;

class MainController extends Controller
{
    public function index()
    {
        $total_price = Wallet::sum('balance');
        $total_commissions = VatamamWalletHistory::sum('amount');
        return view('back.index', compact('total_price', 'total_commissions'));
    }

    public function login()
    {
        return view('back.auth.login');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        auth()->user()->unreadNotifications->markAsRead();

        return view('back.notifications', compact('notifications'));
    }

    public function fileManager()
    {
        // $this->authorize('file-manager');

        return view('back.file-manager');
    }

    public function fileManagerIframe()
    {
        // $this->authorize('file-manager');

        return view('back.file-manager-iframe');
    }
}
