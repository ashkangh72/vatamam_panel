<?php

namespace App\Http\Controllers\Back;

use App\Events\ContactCreated as EventsContactCreated;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\ContactCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use phpDocumentor\Reflection\Types\This;

class MainController extends Controller
{
    public function index()
    {

        return view('back.index');
    }

    public function get_tags(Request $request)
    {
        $tags = Tag::where('name', 'like', '%' . $request->term . '%')->latest()->take(5)->pluck('name')->toArray();

        return response()->json($tags);
    }

    public function login()
    {
        return view('back.auth.login');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        auth()->user()->unreadNotifications->markAsRead();

        return view('back.notifications', compact('notifications'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendNotifications(Request $request): Response
    {
        if ($request->header('Authorization') == NULL) {
            return (new Response(['status' => Response::HTTP_NOT_ACCEPTABLE], Response::HTTP_NOT_ACCEPTABLE));
        }

        $token = explode(' ', $request->header('Authorization'))[1];
        if ($token != 'WE/0ZyUVLLAO/4PYosHimuCGfmiEy4CNmuyZzfPf5O0=') {
            return (new Response(['status' => Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED));
        }

        switch ($request->get('case')) {
            case 'contactCreated':
                $admins = User::whereIn('level', ['admin', 'creator'])->get();
                $contact = Contact::where('id', $request->get('row_id'))->first();

                Notification::send($admins, new ContactCreated($contact));
                event(new EventsContactCreated($contact));
                break;
        }

        return (new Response(['status' => Response::HTTP_OK], Response::HTTP_OK));
    }

    public function fileManager()
    {
        $this->authorize('file-manager');

        return view('back.file-manager');
    }

    public function fileManagerIframe()
    {
        $this->authorize('file-manager');

        return view('back.file-manager-iframe');
    }

    public function uploadFile(Request $request)
    {

        $user=User::find($request->header('USER-ID'));

        if (File::exists(public_path($user->image))) {

            File::delete(public_path($user->image));
        }
        $request->file('image')->move( public_path('uploads/profile'), $request->file('image')->getClientOriginalName());
        return ['ok'];
    }
}
