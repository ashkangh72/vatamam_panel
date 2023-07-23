<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $this->authorize('links.index');

        $groups = config('general.linkGroups', []);
        $links = Link::orderBy('ordering')->get();

        return view('back.links.index', compact('groups', 'links'));
    }

    public function create()
    {
        $this->authorize('links.create');

        $groups = config('general.linkGroups', []);

        return view('back.links.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $this->authorize('links.create');

        $this->validate($request, [
            'title' => 'required',
            'url' => 'required',
            'link_group_id' => 'required',
        ]);

        Link::create([
            'title' => $request->title,
            'url' => $request->url,
            'link_group_id' => $request->link_group_id,
            'ordering' => Link::max('ordering') + 1,
        ]);

        toastr()->success('لینک با موفقیت ایجاد شد.');

        return response("success");
    }

    public function edit(Link $link)
    {
        $this->authorize('links.update');

        $groups = config('general.linkGroups', []);

        return view('back.links.edit', compact('link', 'groups'));
    }

    public function update(Link $link, Request $request)
    {
        $this->authorize('links.update');

        $this->validate($request, [
            'title' => 'required',
            'url' => 'required',
            'link_group_id' => 'required',
        ]);

        $link->update([
            'title' => $request->title,
            'url' => $request->url,
            'link_group_id' => $request->link_group_id,
        ]);

        toastr()->success('لینک با موفقیت ویرایش شد.');

        return response("success");
    }

    public function destroy(Link $link)
    {
        $this->authorize('links.delete');

        $link->delete();

        return response('success');
    }

    public function sort(Request $request)
    {
        $this->authorize('links.update');

        $this->validate($request, [
            'links' => 'required|array'
        ]);

        $i = 1;
        foreach ($request->links as $link) {
            Link::findOrFail($link)->update([
                'ordering' => $i++,
            ]);
        };

        return response('success');
    }

    public function groups()
    {
        $this->authorize('links.groups');

        $groups = config('general.linkGroups', []);

        return view('back.links.groups', compact('groups'));
    }

    public function updateGroups(Request $request)
    {
        $this->authorize('links.groups.update');

        $request->validate([
            'groups' => 'required|array',
        ]);

        foreach ($request->groups as $key => $value) {
            option_update('link_groups_' . $key, $value);
        }

        return response('success');
    }
}
