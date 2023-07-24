<?php

namespace App\Http\Controllers\Back;

use App\Enums\MenuTypeEnum;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public int $ordering = 1;

    public function index()
    {
        $this->authorize('menus.index');

        $menus = Menu::whereNull('menu_id')
            ->with('menus', 'category')
            ->orderBy('ordering')
            ->get();

        $categories = Category::orderBy('ordering')->get();

        return view('back.menus.index', compact('menus', 'categories'));
    }

    public function show(Menu $menu)
    {
        $this->authorize('menus.show');

        return response()->json(['menu' => $menu->load('category'), 'title' => $menu->title]);
    }

    public function store(Request $request)
    {
        $this->authorize('menus.create');

        $this->validate($request, [
            'type' => 'required',
        ]);

        $ordering = Menu::max('ordering') + 1;

        $type = MenuTypeEnum::from($request->type);

        switch ($type) {
            case MenuTypeEnum::category: {
                $this->validate($request, [
                    'category' => 'required|exists:categories,id',
                ]);

                $category = Category::find($request->category);

                $menu = Menu::create([
                    'title'       => $request->category_title ?: $category->title,
                    'ordering'    => $ordering,
                    'menuable_id' => $request->category,
                    'type'        => MenuTypeEnum::category,
                    'children'    => (bool)$request->children,
                ]);

                $title = $menu->category->title . ' ( دسته بندی )';
                break;
            }
            case MenuTypeEnum::normal: {
                $this->validate($request, [
                    'title' => 'required',
                    'url' => 'required',
                ]);

                $menu = Menu::create([
                    'ordering' => $ordering,
                    'title' => $request->title,
                    'url' => $request->url,
                    'type' => MenuTypeEnum::normal,
                ]);

                $title = $menu->title;
                break;
            }
        }

        return response()->json(['menu' => $menu, 'title' => $title]);
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('menus.update');

        $this->validate($request, [
            'type' => 'required',
        ]);

        $type = MenuTypeEnum::from($request->type);

        switch ($type) {
            case MenuTypeEnum::category: {
                $this->validate($request, [
                    'category' => 'required|exists:categories,id',
                ]);

                $category = Category::find($request->category);

                $menu->update([
                    'title'       => $request->category_title ?: $category->title,
                    'menuable_id' => $request->category,
                    'url' => null,
                    'type' => MenuTypeEnum::category,
                    'children'    => (bool)$request->children,
                ]);

                $title = $menu->category->title . ' ( دسته بندی )';
                break;
            }
            case MenuTypeEnum::normal: {
                $this->validate($request, [
                    'title' => 'required',
                    'url' => 'required',
                ]);

                $menu->update([
                    'title' => $request->title,
                    'url' => $request->url,
                    'menuable_id' => null,
                    'type' => MenuTypeEnum::normal,
                ]);

                $title = $menu->title;
                break;
            }
        }

        return response()->json(['menu' => $menu, 'title' => $title]);
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('menus.delete');

        $menu->delete();
    }

    public function sort(Request $request)
    {
        $this->authorize('menus.update');

        $this->validate($request, [
            'menus' => 'required|array',
        ]);

        $menus = $request->menus;

        $this->sort_category($menus);

        return 'success';
    }

    private function sort_category($menus, $menu_id = null)
    {
        foreach ($menus as $category) {
            Menu::find($category['id'])->update(['menu_id' => $menu_id, 'ordering' => $this->ordering++]);
            if (array_key_exists('children', $category)) {
                $this->sort_category($category['children'], $category['id']);
            }
        }
    }
}
