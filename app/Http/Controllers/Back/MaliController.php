<?php

namespace App\Http\Controllers\Back;

use App\Http\Resources\Datatable\TransactionCollection;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{BlackListAdmin, Role, User, WalletHistory};
use App\Rules\{ValidaPhone, NotSpecialChar};
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Datatable\UserCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\WalletHistoryCollection;

class MaliController extends Controller
{
    public function index()
    {
        $this->authorize('users.index');

        return view('back.transactions.mali_index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('users.index');

        $users = User::excludeCreator()->filter($request);

        $users = datatable($request, $users);

        return new UserCollection($users);
    }

    public function details()
    {
        $this->authorize('users.index');

        return view('back.transactions.user_index');
    }

    public function detailsApiIndex(Request $request)
    {
        $this->authorize('users.index');

        $users = WalletHistory::filter($request);

        $users = datatable($request, $users);

        return new WalletHistoryCollection($users);
    }
}
