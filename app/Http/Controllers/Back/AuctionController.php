<?php

namespace App\Http\Controllers\Back;

use Carbon\Carbon;
use App\Models\Auction;
use App\Enums\AuctionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\AuctionBidCollection;
use App\Http\Resources\Datatable\AuctionCollection;
use App\Jobs\{AuctionWinnerJob, FollowedAuctionJob, NoticeAuctionJob};
use App\Models\AuctionBid;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuctionController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('auctions.index');

        return view('back.auctions.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function apiIndex(Request $request)
    {
        $this->authorize('auctions.index');

        $auctions = Auction::with(['user', 'category'])
            ->where('type', 'auction')
            ->orderBy('status', 'asc')
            ->orderByDesc('created_at')
            ->filter($request);

        $auctions = datatable($request, $auctions);

        return new AuctionCollection($auctions);
    }

    public function indexAuctionBids(Auction $auction)
    {
        $this->authorize('auctions.index');

        return view('back.auctions.index_bids', compact('auction'));
    }

    /**
     * @throws AuthorizationException
     */
    public function apiIndexAuctionBids(Auction $auction, Request $request)
    {
        $this->authorize('auctions.index');

        $auctions = AuctionBid::where('auction_id', $auction->id)
            ->filter($request);

        $auctions = datatable($request, $auctions);

        return new AuctionBidCollection($auctions);
    }

    /**
     * @throws AuthorizationException
     */
    public function indexProducts()
    {
        $this->authorize('auctions.index');

        return view('back.auctions.index_products');
    }

    /**
     * @throws AuthorizationException
     */
    public function apiIndexProducts(Request $request)
    {
        $this->authorize('auctions.index');
        $auctions = Auction::with(['user', 'category'])
            ->where('type', 'product')
            ->orderBy('status', 'asc')
            ->orderByDesc('created_at')
            ->filter($request);

        $auctions = datatable($request, $auctions);

        return new AuctionCollection($auctions);
    }

    /**
     * @throws AuthorizationException
     */
    public function multipleDestroy(Request $request)
    {
        $this->authorize('auctions.delete');

        $request->validate([
            'ids' => 'required|array',
            // 'ids.*' => [
            //     Rule::exists('auctions', 'id')->where(function ($query) {
            //         $query->where('status', '!=', AuctionStatusEnum::approved)
            //             ->orWhere('is_ended', true);
            //     })
            // ]
        ]);

        foreach ($request->ids as $id) {
            $auction = Auction::find($id);
            $auction->slug = $auction->slug . '_' . now() . '_deleted';
            $auction->delete();
        }

        return response('success');
    }

    /**
     * @throws AuthorizationException
     */
    public function accept(Request $request)
    {
        $this->authorize('auctions.approve');

        $validated = $request->validate([
            'id' => ['required', 'numeric', 'exists:auctions,id']
        ]);

        $auction = Auction::where('id', $validated['id'])->first();
        if ($auction->type == 'auction') {
            // $difference = Carbon::parse($auction->updated_at)->diffInMinutes(Carbon::parse($auction->end_at));
            // $auction->end_at = Carbon::now()->addMinutes($difference);
            $auction->status = AuctionStatusEnum::approved;
            $auction->save();

            // dispatch(new AuctionWinnerJob($auction->id))->delay(Carbon::parse($auction->end_at)->addMinute());
            // dispatch(new FollowedAuctionJob($auction->id))->delay(Carbon::parse($auction->end_at)->subHours(3));
            dispatch(new NoticeAuctionJob($auction))->delay(Carbon::now()->addMinutes(10));

            $auction->user->sendAuctionBeforeEndNotification($auction);
        } else {
            $auction->status = AuctionStatusEnum::approved;
            $auction->save();

            dispatch(new NoticeAuctionJob($auction))->delay(Carbon::now()->addMinutes(10));
        }
        $auction->user->sendAuctionAcceptNotification($auction);

        return response('success');
    }

    /**
     * @throws AuthorizationException
     */
    public function reject(Request $request)
    {
        $this->authorize('auctions.reject');

        $validated = $request->validate([
            'id' => ['required', 'numeric', 'exists:auctions,id'],
            'reason' => ['required', 'string'],
        ]);

        $auction = Auction::where('id', $validated['id'])->first();
        $auction->update([
            'status' => AuctionStatusEnum::rejected,
            'reject_reason' => $validated['reason']
        ]);

        $auction->user->sendAuctionRejectNotification(Auction::find($auction->id));

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

    public function show(Auction $auction)
    {
        $this->authorize('auctions.index');

        return view('back.auctions.show', compact('auction'));
    }
}
