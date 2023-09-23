<?php

namespace App\Http\Controllers\Back;

use App\Enums\PosterGroupEnum;
use App\Models\Poster;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Response, Request};
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PosterController extends Controller
{
    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('posters.index');

        $posters = Poster::orderBy('ordering')->get();

        return view('back.posters.index', compact('posters'));
    }

    /**
     * @return RedirectResponse|View
     * @throws AuthorizationException
     */
    public function create(): RedirectResponse|View
    {
        $this->authorize('posters.create');

        if (Poster::where('is_active', true)->exists()) {
            toastr()->error('فقط یک پوستر میتوانید ایجاد کنید.');
            return redirect()->route('admin.posters.index');
        }

        return view('back.posters.create');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Request $request): Response
    {
        $this->authorize('posters.create');

        $this->validate($request, [
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['mimes:jpeg,jpg,png,gif,svg,webp', 'required', 'max:2048'],
            'group' => ['required', 'string', 'in:' . implode(',', PosterGroupEnum::getNames())],
            'linkable_type' => ['nullable', 'string', 'in:auction,category'],
            'linkable_id' => ['nullable', 'numeric'],
            'published' => ['nullable'],
            'link' => ['nullable', 'string', 'max:255'],
        ]);

        $poster = Poster::create([
            'title' => $request->title,
            'link' => $request->link,
            'group' => PosterGroupEnum::find($request->group),
            'is_active' => (bool)$request->published,
            'linkable_type' => $this->getLinkableType($request->linkable_type),
            'linkable_id' => $request->linkable_id,
        ]);

        $this->updatePosterImage($request, $poster);

        toastr()->success('پوستر با موفقیت ایجاد شد.');

        return response("success");
    }

    /**
     * @param Poster $poster
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Poster $poster): View
    {
        $this->authorize('posters.update');

        return view('back.posters.edit', compact('poster'));
    }

    /**
     * @param Poster $poster
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Poster $poster, Request $request): Response
    {
        $this->authorize('posters.update');

        $this->validate($request, [
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
            'group' => ['required', 'string', 'in:' . implode(',', PosterGroupEnum::getNames())],
            'linkable_type' => ['nullable', 'string', 'in:auction,category'],
            'linkable_id' => ['nullable', 'numeric'],
            'published' => ['nullable'],
            'link' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $this->updatePosterImage($request, $poster);
        }

        $poster->update([
            'title' => $request->title,
            'link' => $request->link,
            'group' => PosterGroupEnum::find($request->group),
            'is_active' => (bool)$request->published,
            'linkable_type' => $this->getLinkableType($request->linkable_type),
            'linkable_id' => $request->linkable_id,
        ]);

        toastr()->success('پوستر با موفقیت ویرایش شد.');

        return response("success");
    }

    /**
     * @param Poster $poster
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Poster $poster): Response
    {
        $this->authorize('posters.delete');

        if ($poster->image) {
            Storage::disk('local')->delete($poster->image);
        }

        $poster->delete();

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
        $this->authorize('posters.update');

        $this->validate($request, [
            'posters' => 'required|array'
        ]);

        $i = 1;

        foreach ($request->posters as $poster) {
            Poster::findOrFail($poster)->update([
                'ordering' => $i++,
            ]);
        };

        return response('success');
    }

    private function updatePosterImage(Request $request, Poster $poster)
    {
        if ($poster->image && Storage::exists($poster->image)) {
            Storage::disk('local')->delete($poster->image);
        }

        $name = uniqid() . '_' . time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('posters', $name);

        $poster->image = '/uploads/posters/' . $name;
        $poster->save();
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
