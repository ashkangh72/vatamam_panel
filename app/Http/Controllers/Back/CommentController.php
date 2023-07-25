<?php

namespace App\Http\Controllers\Back;

use App\Enums\CommentStatusEnum;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('comments.index');

        $comments = Comment::filter($request)
            ->whereNull('comment_id')
            ->with('comments')
            ->paginate(15);

        return view('back.comments.index', compact('comments'));
    }

    public function replies(Comment $comment)
    {
        $comments = $comment->comments;

        return response([
            'replies' => view('back.comments.replies', compact('comments'))->render(),
        ]);
    }

    public function show(Comment $comment)
    {
        $this->authorize('comments.show');

        return view('back.comments.show', compact('comment'))->render();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('comments.delete');

        $comment->delete();

        return response('success');
    }

    public function update(Comment $comment, Request $request)
    {
        $this->authorize('comments.update');

        $this->validate($request, [
            'status' => 'required',
            'body' => 'required',
            'replay' => 'nullable|string',
        ]);

        $comment->update([
            'body' => $request->body,
            'status' => CommentStatusEnum::from($request->status)
        ]);

        if ($request->replay) {
            $comment->comments()->create([
                'auction_id' => $comment->auction_id,
                'user_id' => auth()->user()->id,
                'body' => $request->replay,
                'status' => CommentStatusEnum::approved,
                'comment_id' => $comment->id,
            ]);
        }

        return response($comment);
    }
}
