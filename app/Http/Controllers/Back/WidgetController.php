<?php

namespace App\Http\Controllers\Back;

use App\Enums\WidgetKeyEnum;
use App\Http\Controllers\Controller;
use App\Models\{Auction, Category, HistoricalPeriod, Originality, Widget};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WidgetController extends Controller
{
    public function __construct()
    {
        if (!config('general.widgets')) {
            abort(404);
        }
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('widgets.index');

        $widgets = Widget::orderBy('ordering')->get();

        return view('back.widgets.index', compact('widgets'));
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('widgets.create');

        return view('back.widgets.create');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Request $request): Response
    {
        $this->authorize('widgets.create');

        $keys = implode(',', WidgetKeyEnum::getNames());

        $request->validate([
            'key' => [
                'required',
                "in:$keys",
                'unique:widgets'
            ],
            'options' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $key = config('general.widgets.' . $request->key);

        Validator::make($request->options, $key['rules'])->validate();

        $widget = Widget::create([
            'title' => $request->title,
            'key' => WidgetKeyEnum::find($request->key),
            'is_active' => $request->is_active,
        ]);

        $options = $this->getRequestOptions($key, $request, $widget);

        $this->saveWidgetOptions($widget, $options);

        toastr()->success('ابزارک با موفقیت ایجاد شد');

        return response('success');
    }

    /**
     * @param Widget $widget
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Widget $widget): View
    {
        $this->authorize('widgets.update');

        $template = $this->template($widget->key->name, $widget);

        return view('back.widgets.edit', compact('widget', 'template'));
    }

    /**
     * @param Widget $widget
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(Widget $widget, Request $request): Response
    {
        $this->authorize('widgets.update');

        $keys = implode(',', WidgetKeyEnum::getNames());

        $request->validate([
            'key' => "required|in:$keys",
            'options' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $key = config('general.widgets.' . $request->key);

        Validator::make($request->options, $key['rules'])->validate();

        $widget->update([
            'title' => $request->title,
            'key' => WidgetKeyEnum::find($request->key),
            'is_active' => $request->is_active,
        ]);

        $options = $this->getRequestOptions($key, $request, $widget);

        $widget->options()->delete();

        $this->saveWidgetOptions($widget, $options);

        toastr()->success('ابزارک با موفقیت ویرایش شد');
        return response('success');
    }

    /**
     * @param Widget $widget
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Widget $widget): Response
    {
        $this->authorize('widgets.delete');

        $widget->delete();

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
        $this->authorize('widgets.update');

        $this->validate($request, [
            'widgets' => 'required|array'
        ]);

        $i = 1;

        foreach ($request->widgets as $widget) {
            Widget::findOrFail($widget)->update([
                'ordering' => $i++,
            ]);
        };

        return response('success');
    }

    /**
     * @param $key
     * @param null $widget
     * @return View|string
     * @throws AuthorizationException
     */
    public function template($key, $widget = null): View|string
    {
        $options = config('general.widgets.' . $key . '.options');

        if (!$options) {
            return '';
        }

        $categories = Category::orderBy('ordering')->get();
        $auctions = Auction::approved()->notEnded()->auction()->get();
        $products = Auction::approved()->notEnded()->product()->get();
        $historicalPeriods = HistoricalPeriod::get();
        $originality = Originality::get();

        return view('back.widgets.template', compact('options', 'widget', 'categories', 'historicalPeriods', 'originality', 'auctions', 'products'));
    }

    private function getRequestOptions($key, $request, Widget $widget): array
    {
        $options = [];

        foreach ($key['options'] as $key => $option) {
            switch ($option['input-type']) {
                case 'select':
                case 'categories':
                case 'auctions':
                case 'products':
                case 'input':
                case 'historical_period':
                case 'originality':
                case 'condition':
                case 'timezone': {
                        $options[$key]['input-type'] = $option['input-type'];
                        $options[$key]['key'] = $option['key'];
                        $options[$key]['value'] = $request->input('options.' . $option['key']);
                        break;
                    }
                case 'file': {
                        $file = $request->file('options.' . $option['key']);

                        if ($file) {
                            $oldFile = $widget->option($option['key']);
                            if ($oldFile && Storage::disk('local')->exists($oldFile)) {
                                Storage::disk('local')->delete($oldFile);
                            }

                            $options[$key]['value'] = $this->uploadImage($file);
                        } else {
                            $options[$key]['value'] = $widget->option($option['key']);
                        }

                        $options[$key]['input-type'] = $option['input-type'];
                        $options[$key]['key'] = $option['key'];

                        break;
                    }
            }
        }

        return $options;
    }

    private function saveWidgetOptions(Widget $widget, $options): void
    {
        foreach ($options as $option) {
            switch ($option['input-type']) {
                case 'categories': {
                        $value = is_array($option['value']) && !empty($option['value']) ? 'on' : 'off';

                        $inserted_option = $widget->options()->create([
                            'key' => $option['key'],
                            'value' => $value
                        ]);

                        $inserted_option->categories()->sync($option['value']);

                        break;
                    }
                case 'products':
                case 'auctions': {
                        $value = is_array($option['value']) && !empty($option['value']) ? 'on' : 'off';

                        $inserted_option = $widget->options()->create([
                            'key' => $option['key'],
                            'value' => $value
                        ]);

                        $inserted_option->auctions()->sync($option['value']);

                        break;
                    }

                default: {
                        if (is_null($option['value'])) break;

                        $widget->options()->create([
                            'key' => $option['key'],
                            'value' => $option['value']
                        ]);
                    }
            }
        }
    }

    private function uploadImage($file): string
    {
        $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('widgets', $name);

        return '/uploads/widgets/' . $name;
    }
}
