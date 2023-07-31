<?php

namespace App\Http\Controllers\Back;

use App\Enums\AuctionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\AuctionCollection;
use App\Jobs\AuctionWinnerJob;
use App\Models\{Auction, User};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Validation\Rule;

class AuctionController extends Controller
{
    public function index()
    {
        $this->authorize('auctions.index');
        return view('back.auctions.index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('auctions.index');

        $auctions = Auction::with(['user', 'category'])->filter($request);

        $auctions = datatable($request, $auctions);

        return new AuctionCollection($auctions);
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('auctions.delete');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', '!=', auth()->user()->id)->where('level', '!=', 'creator');
                })
            ]
        ]);

        foreach ($request->ids as $id) {
            $user = User::find($id);
            $this->destroy($user, true);
        }

        return response('success');
    }

    public function accept(Request $request)
    {
        $this->authorize('auctions.approve');

        $validated = $request->validate([
            'id' => ['required', 'numeric', 'exists:auctions,id']
        ]);

        Auction::where('id', $validated['id'])->update([
            'status' => AuctionStatusEnum::approved
        ]);

        AuctionWinnerJob::dispatchAfterResponse($request->id);

        return response('success');
    }

    public function reject(Request $request)
    {
        $this->authorize('auctions.reject');

        $validated = $request->validate([
            'id' => ['required', 'numeric', 'exists:auctions,id'],
            'reason' => ['required', 'string'],
        ]);
        Auction::where('id', $validated['id'])->update([
            'status' => AuctionStatusEnum::rejected,
            'reject_reason' => $validated['reason']
        ]);
        return response('success');
    }

    public function getAuctionByTitle()
    {
        $request = request();

        if ($request->has('q') and $request->filled('q')) {
            $auctions = Auction::where('title', 'LIKE', "%{$request->q}%")->get();
            $items = collect();
            $auctions->each(function ($auction) use ($items) {
                $items->push([
                    'id' => $auction->id,
                    'text' => $auction->title,
                    'title' => $auction->title,
                    'image' => $auction->picture,
                ]);
            });

            return response()->json([
                'items' => $items
            ]);
        }

        return response()->json([]);
    }
}
