<?php

namespace App\Http\Controllers\Back;

use App\Models\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{
    public function index()
    {
        $this->authorize('redirects');

        $redirects = Redirect::all();

        return view('back.redirects.index', compact('redirects'));
    }

    public function store(Request $request)
    {
        $this->authorize('redirects.create');

        $request->validate([
            'http_code' => 'required|numeric',
            'old_url' => 'required|string',
            'new_url' => 'required|string',
        ]);

        Redirect::updateOrCreate(
            [
                'old_url' => $request->old_url,
            ],
            [
                'http_code' => $request->http_code,
                'new_url' => $request->new_url,
            ]
        );
    }

    public function destroy(Redirect $redirect)
    {
        $this->authorize('redirects.delete');

        $redirect->delete();

        return response('success');
    }
}
