<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests\Back\Faq\{
    StoreFaqRequest,
    UpdateFaqRequest
};
use Illuminate\Contracts\{
    Foundation\Application,
    Routing\ResponseFactory
};
use Illuminate\Http\{
    Request,
    Response
};
use App\Models\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\Datatable\Faq\FaqCollection;

class FaqController extends Controller
{
    public function index()
    {
        return view('back.faqs.index');
    }

    /**
     * @param Request $request
     * @return FaqCollection
     * @throws AuthorizationException
     */
    public function apiIndex(Request $request): FaqCollection
    {
        $this->authorize('faqs.index');

        $faqs = Faq::filter($request);

        $faqs = datatable($request, $faqs);

        return new FaqCollection($faqs);
    }

    public function create()
    {
        return view('back.faqs.create');
    }

    public function store(StoreFaqRequest $request)
    {
        $data             = $request->validated();
        $data['ordering'] = Faq::max('ordering') + 1;

        Faq::create($data);

        toastr()->success('سوال متداول با موفقیت ایجاد شد.');

        return response('success');
    }

    public function edit(Faq $faq)
    {
        return view('back.faqs.edit', compact('faq'));
    }

    public function update(Faq $faq, UpdateFaqRequest $request)
    {
        $data = $request->validated();

        $faq->update($data);

        toastr()->success('سوال متداول با موفقیت ویرایش شد.');

        return response('success');
    }

    public function destroy(Faq $faq, $multiple = false)
    {
        $faq->delete();

        if (!$multiple) {
            toastr()->success('سوال متداول با موفقیت حذف شد.');
        }

        return response('success');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function multipleDestroy(Request $request)
    {
        $this->authorize('faqs.delete');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required|exists:faqs,id'
        ]);

        foreach ($request->ids as $id) {
            $faq = Faq::find($id);
            $this->destroy($faq, true);
        }

        return response('success');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function sort(Request $request)
    {
        $this->authorize('faqs.update');

        $this->validate($request, [
            'faqs' => 'required|array',
            'orderings' => 'required|array',
        ]);

        $orderings = implode(',', $request->orderings);
        $count = 0;

        $faqs = Faq::whereIn('id', $request->faqs)
            ->orderByRaw('FIELD(ordering,' . $orderings . ')')
            ->get();

        $sorted = $faqs->sortBy('ordering')
            ->pluck('ordering')
            ->toArray();

        foreach ($faqs as $faq) {
            $faq->update([
                'ordering' => $sorted[$count++],
            ]);
        };

        return response('success');
    }
}
