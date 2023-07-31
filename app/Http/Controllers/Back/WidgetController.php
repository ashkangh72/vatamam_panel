<?php

namespace App\Http\Controllers\Back;

use App\Enums\WidgetKeyEnum;
use App\Http\Controllers\Controller;
use App\Models\{Category, Widget};
use Illuminate\Contracts\View\View;
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

    public function index(): View
    {
        $widgets = Widget::orderBy('ordering')->get();

        return view('back.widgets.index', compact('widgets'));
    }

    public function create(): View
    {
        return view('back.widgets.create');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        $keys = implode(',', WidgetKeyEnum::getNames());

        $request->validate([
            'key' => "required|in:$keys",
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

    public function edit(Widget $widget): View
    {
        $template = $this->template($widget->key->name, $widget);

        return view('back.widgets.edit', compact('widget', 'template'));
    }

    /**
     * @param Widget $widget
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update(Widget $widget, Request $request): Response
    {
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
     */
    public function destroy(Widget $widget): Response
    {
        $widget->delete();

        return response('success');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function sort(Request $request): Response
    {
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

    public function template($key, $widget = null): View|string
    {
        $options = config('general.widgets.' . $key . '.options');

        if (!$options) {
            return '';
        }

        $categories = Category::orderBy('ordering')->get();

        return view('back.widgets.template', compact('options', 'widget', 'categories'));
    }

    private function getRequestOptions($key, $request, Widget $widget): array
    {
        $options = [];

        foreach ($key['options'] as $key => $option) {
            switch ($option['input-type']) {
                case 'select':
                case 'categories':
                case 'input':
                {
                    $options[$key]['input-type'] = $option['input-type'];
                    $options[$key]['key'] = $option['key'];
                    $options[$key]['value'] = $request->input('options.' . $option['key']);
                    break;
                }

                case 'file':
                {
                    $file = $request->file('options.' . $option['key']);

                    if ($file) {
                        $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('widgets', $name);
                        $options[$key]['value'] = '/uploads/' . $path;
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
                case 'categories':
                {
                    $value = is_array($option['value']) && !empty($option['value']) ? 'on' : 'off';

                    $inserted_option = $widget->options()->create([
                        'key' => $option['key'],
                        'value' => $value
                    ]);

                    $inserted_option->categories()->sync($option['value']);

                    break;
                }

                default:
                {
                    $widget->options()->create([
                        'key' => $option['key'],
                        'value' => $option['value']
                    ]);
                }
            }
        }
    }
}
