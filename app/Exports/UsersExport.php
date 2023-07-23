<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public function view(): View
    {
        return view('back.exports.users', [
            'users' => User::where('level', '!=', 'creator')->get()
        ]);
    }
}
