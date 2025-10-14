<?php

namespace App\Http\Controllers\Back;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public int $ordering = 1;

    public function index()
    {
        $this->authorize('categories.index');

        $categories = Category::whereNull('category_id')
            ->with('children')
            ->orderBy('ordering')
            ->get();

        return view('back.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('categories.create');

        $this->validate($request, [
            'title' => 'required|string|unique:categories,title',
        ]);

        $ordering = Category::max('ordering') + 1;

        return Category::create([
            'title' => $request->title,
            'slug' => SlugService::createSlug(Category::class, 'slug', $request->title),
            'ordering' => $ordering,
            'meta_title' => $request->title,
        ]);
    }

    public function getCategoryByTitle(): JsonResponse
    {
        $request = request();

        if ($request->has('q') and $request->filled('q')) {
            $categories = Category::where('title', 'LIKE', "%{$request->q}%")->get();
            $items = collect();
            $categories->each(function ($category) use ($items) {
                $items->push([
                    'id' => $category->id,
                    'text' => $category->title,
                    'title' => $category->title,
                    'image' => $category->image,
                ]);
            });

            return response()->json([
                'items' => $items
            ]);
        }

        return response()->json([]);
    }

    public function edit(Category $category)
    {
        $this->authorize('categories.update');

        return view('back.categories.partials.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('categories.update');

        $this->validate($request, [
            'title' => 'required|string',
            'slug' => "nullable|unique:categories,slug,$category->id",
        ]);

        if ($request->slug == $category->slug) {
            $slug = $request->slug;
        } else {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->slug ?: $request->title);
        }

        $picture = $request->file('picture');
        if ($picture) {
            $oldPicture = $category->picture;
            if ($oldPicture && Storage::disk('local')->exists($oldPicture)) {
                Storage::disk('local')->delete($oldPicture);
            }

            $picture = $this->uploadImage($picture);
        }

        $category->update([
            'title' => $request->title,
            'slug' => $slug,
            'picture' => $picture,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'description' => $request->description,
        ]);

        return $category;
    }

    public function destroy(Category $category)
    {
        $this->authorize('categories.delete');

        foreach (Category::whereIn('id', $category->allChildCategories())->get() as $child_category) {
            $child_category->menus()->detach();
            $child_category->delete();
        }

        return $category;
    }

    public function sort(Request $request)
    {
        $this->authorize('categories.update');

        $this->validate($request, [
            'categories' => 'required|array',
        ]);

        $this->sort_category($request->categories);

        return 'success';
    }

    private function sort_category($categories, $category_id = null)
    {
        foreach ($categories as $category) {
            Category::find($category['id'])->update(['category_id' => $category_id, 'ordering' => $this->ordering++]);
            if (array_key_exists('children', $category)) {
                $this->sort_category($category['children'], $category['id']);
            }
        }
    }

    public function generate_slug(Request $request)
    {
        $this->authorize('categories.update');

        $request->validate([
            'title' => 'required',
        ]);

        $slug = SlugService::createSlug(Category::class, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }

    private function uploadImage($file): string
    {
        $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('categories', $name);

        return '/uploads/categories/' . $name;
    }
}
