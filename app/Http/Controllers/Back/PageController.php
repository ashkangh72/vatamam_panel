<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Menu;
use App\Models\Page;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $this->authorize('pages.index');

        $pages = Page::latest()->paginate(10);

        return view('back.pages.index', compact('pages'));
    }

    public function create()
    {
        $this->authorize('pages.create');

        return view('back.pages.create');
    }

    public function store(Request $request)
    {
        $this->authorize('pages.create');

        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'required',
            'slug' => 'nullable|unique:pages,slug'
        ]);

        Page::create([
            'title' => $request->title,
            'content' => $request->input('content'),
            'slug' => SlugService::createSlug(Page::class, 'slug', $request->slug ?: $request->title),
            'published' => (bool)$request->published,
        ]);

        toastr()->success('صفحه با موفقیت ایجاد شد.');

        return response("success", 200);
    }

    public function edit(Page $page)
    {
        $this->authorize('pages.update');

        return view('back.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $this->authorize('pages.update');

        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'required',
        ]);

        $slug = SlugService::createSlug(Page::class, 'slug', $request->slug ?: $request->title);

        Menu::where('url', $page->link($page->slug))->update([
            'url' => $page->link($slug),
        ]);

        Link::where('url', $page->link($page->slug))->update([
            'url' => $page->link($slug),
        ]);

        $page->update([
            'title' => $request->title,
            'content' => $request->input('content'),
            'slug' => $slug,
            'published' => (bool)$request->published,
        ]);

        toastr()->success('صفحه با موفقیت ویرایش شد.');

        return response("success", 200);
    }

    public function destroy(Page $page)
    {
        $this->authorize('pages.delete');

        $page->delete();

        return response("success", 200);
    }
}
