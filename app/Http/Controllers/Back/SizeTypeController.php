<?php

namespace App\Http\Controllers\Back;

use App\Models\SizeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeTypeController extends Controller
{
    public function index()
    {
        $this->authorize('products.size-types');

        $sizeTypes = SizeType::latest()->paginate(15);

        return view('back.size-types.index', compact('sizeTypes'));
    }

    public function show(SizeType $sizeType)
    {
        return view('back.size-types.show', compact('sizeType'));
    }

    public function create()
    {
        return view('back.size-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'unique:size_types,title'],
            'sizes'       => ['required'],
            'description' => 'nullable|string'
        ]);

        $sizeType = SizeType::create([
            'title'       => $request->title,
            'description' => $request->description
        ]);

        $ordering = 1;

        foreach ($request->sizes as $title) {
            $sizeType->sizes()->create([
                'title'    => $title,
                'ordering' => $ordering++
            ]);
        }

        toastr()->success('راهنمای سایز با موفقیت ایجاد شد');

        return response('success');
    }

    public function edit(SizeType $sizeType)
    {
        return view('back.size-types.edit', compact('sizeType'));
    }

    public function update(Request $request, SizeType $sizeType)
    {
        $request->validate([
            'title'       => ['required', 'unique:size_types,title,' . $sizeType->id],
            'sizes'       => ['required', 'array'],
            'description' => 'nullable|string'
        ]);

        $sizeType->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        $ordering = 1;

        $sizes = [];

        foreach ($request->sizes as $key => $title) {
            $id = $request->sizes_id[$key] ?? null;
            if ($id) {
                $size = $sizeType->sizes()->where('id', $id)->first();
                $size->update([
                    'title'    => $title,
                    'ordering' => $ordering++
                ]);
            } else {
                $size = $sizeType->sizes()->create([
                    'title'    => $title,
                    'ordering' => $ordering++
                ]);
            }

            $sizes[] = $size->id;
        }

        $sizeType->sizes()->whereNotIn('id', $sizes)->delete();

        toastr()->success('راهنمای سایز با موفقیت ویرایش شد');

        return response('success');
    }

    public function destroy(SizeType $sizeType)
    {
        $sizeType->delete();

        return response('success');
    }

    public function editValues(SizeType $sizeType)
    {
        return view('back.size-types.values', compact('sizeType'));
    }

    public function updateValues(Request $request, SizeType $sizeType)
    {
        $sizeType->values()->detach();
        $ordering      = 1;
        $groupOrdering = 1;

        foreach ($request->values as $group => $values) {

            foreach ($values as $size_id => $value) {
                $sizeType->values()->attach(
                    [
                        $size_id => [
                            'group'    => $groupOrdering,
                            'value'    => $value,
                            'ordering' => $ordering++
                        ]
                    ]
                );
            }

            $groupOrdering++;
        }

        toastr()->success('راهنمای سایز با موفقیت ویرایش شد');

        return response('success');
    }
}
