<?php

namespace App\Http\Controllers\Back;

use App\Enums\SlideGroupEnum;
use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{Response, Request};
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SliderController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('slides.index');

        $slides = Slide::orderBy('ordering')->get();

        return view('back.slides.index', compact('slides'));
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('slides.update');

        return view('back.slides.create');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Request $request): Response
    {
        $this->authorize('slides.create');

        $this->validate($request, [
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['mimes:jpeg,jpg,png,gif,svg,webp', 'required', 'max:2048'],
            'group' => ['required', 'string', 'in:' . implode(',', SlideGroupEnum::getNames())],
            'linkable_type' => ['nullable', 'string', 'in:auction,category'],
            'linkable_id' => ['nullable', 'numeric'],
            'published' => ['nullable'],
            'link' => ['nullable', 'string', 'max:255'],
        ]);

        $slide = Slide::create([
            'title' => $request->title,
            'link' => $request->link,
            'group' => SlideGroupEnum::find($request->group),
            'is_active' => (bool)$request->published,
            'linkable_type' => $this->getLinkableType($request->linkable_type),
            'linkable_id' => $request->linkable_id,
        ]);

        $this->updateSlideImage($request, $slide);

        toastr()->success('اسلاید با موفقیت ایجاد شد.');

        return response("success");
    }

    /**
     * @param Slide $slide
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Slide $slide): View
    {
        $this->authorize('slides.update');

        return view('back.slides.edit', compact('slide'));
    }

    /**
     * @param Slide $slide
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(Slide $slide, Request $request): Response
    {
        $this->authorize('slides.update');

        $this->validate($request, [
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
            'group' => ['required', 'string', 'in:' . implode(',', SlideGroupEnum::getNames())],
            'linkable_type' => ['nullable', 'string', 'in:auction,category'],
            'linkable_id' => ['nullable', 'numeric'],
            'published' => ['nullable'],
            'link' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $this->updateSlideImage($request, $slide);
        }

        $slide->update([
            'title' => $request->title,
            'link' => $request->link,
            'group' => SlideGroupEnum::find($request->group),
            'is_active' => (bool)$request->published,
            'linkable_type' => $this->getLinkableType($request->linkable_type),
            'linkable_id' => $request->linkable_id,
        ]);

        toastr()->success('اسلایدر با موفقیت ویرایش شد.');

        return response("success");
    }

    /**
     * @param Slide $slide
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Slide $slide): Response
    {
        $this->authorize('slides.delete');

        if ($slide->image) {
            Storage::disk('local')->delete($slide->image);
        }

        $slide->delete();

        return response('success');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function sort(Request $request): Response
    {
        $this->authorize('slides.update');

        $this->validate($request, [
            'sliders' => 'required|array'
        ]);

        $i = 1;

        foreach ($request->slides as $slide) {
            Slide::findOrFail($slide)->update([
                'ordering' => $i++,
            ]);
        };

        return response('success');
    }

    private function updateSlideImage(Request $request, Slide $slide)
    {
        if ($slide->image && Storage::exists($slide->image)) {
            Storage::disk('local')->delete($slide->image);
        }

        $name = uniqid() . '_' . time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('slides', $name);

        $slide->image = '/uploads/slides/' . $name;
        $slide->save();
    }

    private function getLinkableType($type): ?string
    {
        return match ($type) {
            'auction' => 'App\Models\Auction',
            'category' => 'App\Models\Category',
            default => null,
        };
    }
}
