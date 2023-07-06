<?php

namespace App\Http\Controllers\Back;

use App\Enums\AuctionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\AuctionCollection;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuctionController extends Controller
{
    public function index()
    {
        //$this->authorize('auctions.index');
        return view('back.auctions.index');
    }

    public function apiIndex(Request $request)
    {
        //$this->authorize('auctions.index');

        $auctions = Auction::with(['user','category'])->filter($request);

        $auctions = datatable($request, $auctions);

        return new AuctionCollection($auctions);
    }

    public function multipleDestroy(Request $request)
    {
        //$this->authorize('users.delete');

        $request->validate([
            'ids'   => 'required|array',
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
        $validated=$request->validate([
            'id'=>['required','numeric','exists:auctions,id']
        ]);
        Auction::where('id',$validated['id'])->update([
            'status' =>AuctionStatusEnum::approved
        ]);
        return response('success');
    }

    public function reject(Request $request)
    {
        $validated=$request->validate([
            'id'=>['required','numeric','exists:auctions,id'],
            'reason'=>['required','string'],
        ]);
        Auction::where('id',$validated['id'])->update([
            'status' => AuctionStatusEnum::rejected,
            'reject_reason' => $validated['reason']
        ]);
        return response('success');
    }
}
