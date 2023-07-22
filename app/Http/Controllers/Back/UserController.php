<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ValidaPhone;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsersExport;
use App\Http\Resources\Datatable\UserCollection;
use App\Models\Role;
use App\Rules\NotSpecialChar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
       // $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        $this->authorize('users.index',User::class);
        return view('back.users.index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('users.index');

        $users = User::excludeCreator()->filter($request);

        $users = datatable($request, $users);

        return new UserCollection($users);
    }

    public function create()
    {
        $this->authorize('users.create',User::class);

        $roles = Role::latest()->get();

        return view('back.users.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $this->authorize('users.update',User::class);
        $roles = Role::latest()->get();

        return view('back.users.edit', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('users.create',User::class);

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'last_name'  => ['required', 'string', 'max:255', new NotSpecialChar()],
            'level'      => 'in:user,admin',
            'username'   => ['required', 'string', 'unique:users','max:8'],
            'phone'   => ['nullable', 'numeric', 'unique:users'],
            'email'      => ['string', 'email', 'max:255', 'unique:users', 'nullable'],
            'password'   => ['required', 'string', 'confirmed:confirmed'],
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,id'
        ]);


        $user = User::create([
            'name'  => $this->getFullname($request),
            'username'    => $request->username,
            'email'       => $request->email,
            'level'       => $request->level,
            'password'    => Hash::make($request->password),
            'verified_at' => $request->verified_at ? Carbon::now() : null,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = uniqid() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('users', $name);

            $user->image = '/uploads/users/' . $name;
            $user->save();
        }

        $user->roles()->attach($request->roles);

        toastr()->success('کاربر با موفقیت ایجاد شد.');

        return response('success');
    }

    public function update(User $user, Request $request)
    {
        $this->authorize('users.update',User::class);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'phone' => ['required', 'string', 'max:255', new ValidaPhone()],
            'level'      => 'in:user,admin',
            'username'   => ['required', 'string', "unique:users,username,$user->id"],
            'email'      => ['string', 'email', 'max:255', "unique:users,email,$user->id", 'nullable'],
            'password'   => ['nullable', 'string', 'min:6', 'confirmed:confirmed'],
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,id'
        ]);

        $verified_at = $user->verified_at ?: Carbon::now();

        $user->update([
            'name'  => $request->name,
            'username'    => $request->username,
            'phone'    => $request->phone,
            'email'       => $request->email,
            'level'       => $request->level,
            'verified_at' => $request->verified_at ? $verified_at : null,
        ]);

        if ($request->password) {
            $password = Hash::make($request->password);

            $user->update([
                'password' => $password
            ]);

            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = uniqid() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('users', $name);

            $user->image = '/uploads/users/' . $name;
            $user->save();
        }

        $user->roles()->sync($request->roles);

        toastr()->success('کاربر با موفقیت ویرایش شد.');

        return response('success');
    }

    public function show(User $user)
    {
        return view('back.users.show', compact('user'));
    }

    public function destroy(User $user, $multiple = false)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        if (!$multiple) {
            toastr()->success('کاربر با موفقیت حذف شد.');
        }

        return response('success');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('users.delete');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', '!=', auth()->user()->id)->where('level', '!=', 'creator');
                })
            ]
        ]);

        foreach ($request->ids as $id) {
            $user = User::find($id);
            $this->destroy($user, true);
        }

        return response('success');
    }

    public function export()
    {
        $this->authorize('users.export.excel');

        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function views(User $user)
    {
        $views = $user->views()->latest()->paginate(20);

        return view('back.users.views', compact('views', 'user'));
    }

    public function showProfile()
    {
        return view('back.users.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'name' => 'required|string|max:191',
        ]);

        if ($request->password || $request->password_confirmation) {
            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->save();

        if ($request->password) {
            DB::table('sessions')->where('user_id', auth()->user()->id)->delete();
        }

        $options = $request->only([
            'theme_color',
            'theme_font',
            'menu_type'
        ]);

        foreach ($options as $option => $value) {
            user_option_update($option, $value);
        }

        return response()->json('success');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getFullname(Request $request): string
    {
        return $request->first_name . " " . $request->last_name;
    }
}
