<?php

namespace App\Http\Controllers\Back;

use App\Enums\CommentStatusEnum;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('comments.index');

        $tickets = Ticket::filter($request)
            ->with(['messages', 'user'])
            ->paginate(15);

        return view('back.tickets.index', compact('tickets'));
    }

    public function replies(Ticket $ticket)
    {
        $ticketMessages = $ticket->messages()->latest()->paginate(5);
        return view('back.tickets.replies', compact('ticketMessages', 'ticket'));
    }

    public function show(Comment $comment)
    {
        $this->authorize('comments.show');

        return view('back.comments.show', compact('comment'))->render();
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('comments.delete');

        $ticket->delete();

        return response('success');
    }

    public function close(Ticket $ticket)
    {
        $this->authorize('comments.delete');

        $ticket->update(['status' => 4]);

        return response('success');
    }

    public function reply(Ticket $ticket, Request $request)
    {
        $this->authorize('comments.delete');

        $ticket->update([
            'status' => 2
        ]);

        $ticket->messages()->create([
            'from' => 'admin',
            'user_id' => auth()->user()->id,
            'message' => $request->replay,
        ]);

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
