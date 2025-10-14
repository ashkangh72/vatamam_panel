<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\CommissionTariff;
use Illuminate\Http\Request;

class CommissionTariffController extends Controller
{
    public function index()
    {
        $this->authorize('commission_tariffs.index');

        $commissionTariffs = CommissionTariff::orderBy('created_at', 'DESC')->get();

        return view('back.commission_tariffs.index', compact('commissionTariffs'));
    }

    public function create()
    {
        $this->authorize('commission_tariffs.create');

        return view('back.commission_tariffs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('commission_tariffs.create');

        $this->validate($request, [
            'min' => 'required|numeric',
            'max' => 'nullable|numeric',
            'commission_percent' => 'required|max:100|min:0',
        ]);

        CommissionTariff::create([
            'min' => $request->min,
            'max' => $request->max,
            'commission_percent' => $request->commission_percent
        ]);

        toastr()->success('تعرفه با موفقیت ایجاد شد.');

        return response("success");
    }

    public function edit(CommissionTariff $commissionTariff)
    {
        $this->authorize('commission_tariffs.update');

        return view('back.commission_tariffs.edit', compact('commissionTariff'));
    }

    public function update(CommissionTariff $commissionTariff, Request $request)
    {
        $this->authorize('commission_tariffs.update');

        $this->validate($request, [
            'min' => 'required|numeric',
            'max' => 'nullable|numeric',
            'commission_percent' => 'required|max:100|min:0',
        ]);

        $commissionTariff->update([
            'min' => $request->min,
            'max' => $request->max,
            'commission_percent' => $request->commission_percent
        ]);

        toastr()->success('تعرفه با موفقیت ویرایش شد.');

        return response("success");
    }

    public function destroy(CommissionTariff $commissionTariff)
    {
        $this->authorize('commission_tariffs.delete');

        $commissionTariff->delete();

        return response('success');
    }
}
