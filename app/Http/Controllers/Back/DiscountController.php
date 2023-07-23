<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Discount\StoreDiscountRequest;
use App\Http\Requests\Back\Discount\UpdateDiscountRequest;
use App\Models\{Discount, User};
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $this->authorize('discounts.index');

        $discounts = Discount::latest()->paginate(20);

        return view('back.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $this->authorize('discounts.create');

        $users = User::latest()->get();

        return view('back.discounts.create', compact(
            'users',
        ));
    }

    public function store(StoreDiscountRequest $request)
    {
        $this->authorize('discounts.create');

        $data = $request->validated();

        $data['amount'] = $data['type'] == 'amount' ? $data['price'] : $data['percent'];
        $data['start_date'] = Verta::parse($data['start_date'])->datetime();
        $data['end_date'] = Verta::parse($data['end_date'])->datetime();

        $discount = Discount::create($data);

        $discount->users()->sync($request->include_users);

        toastr()->success('تخفیف با موفقیت ایجاد شد.');

        return response('success');
    }

    public function edit(Discount $discount)
    {
        $this->authorize('discounts.update');

        $users = User::latest()->get();

        return view('back.discounts.edit', compact('users', 'discount'));
    }

    public function update(Discount $discount, UpdateDiscountRequest $request)
    {
        $this->authorize('discounts.update');

        $data = $request->validated();

        $data['amount'] = $data['type'] == 'amount' ? $data['price'] : $data['percent'];
        $data['start_date'] = Verta::parse($data['start_date'])->datetime();
        $data['end_date'] = Verta::parse($data['end_date'])->datetime();

        $discount->update($data);

        $discount->users()->sync($request->include_users);

        toastr()->success('تخفیف با موفقیت ویرایش شد.');

        return response('success');
    }

    public function destroy(Discount $discount)
    {
        $this->authorize('discounts.delete');

        $discount->delete();

        return response('success');
    }
}
