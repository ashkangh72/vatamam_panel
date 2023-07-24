<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index()
    {
        return view('back.index');
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
}
