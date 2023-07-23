<?php

namespace App\Http\Controllers\Back;

use App\Models\SearchEngineRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchEngineRulesController extends Controller
{
    public function index()
    {
        $this->authorize('search-engine-rules.index');

        $searchEngineRules = SearchEngineRule::all();

        return view('back.search-engine-rules.index', compact('searchEngineRules'));
    }

    public function store(Request $request)
    {
        $this->authorize('search-engine-rules.create');

        $request->validate([
            'slug' => 'required|string',
        ]);

        SearchEngineRule::updateOrCreate(
            [
                'slug' => $request->slug,
            ],
            [
                'index' => FALSE,
                'follow' => FALSE,
            ]
        );
    }

    public function destroy(SearchEngineRule $searchEngineRule)
    {
        $this->authorize('search-engine-rules.delete');

        $searchEngineRule->delete();

        return response('success');
    }
}
