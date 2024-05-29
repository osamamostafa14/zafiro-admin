<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Events\PusherBroadcast;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Conversation;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Database;
use Pusher\Pusher;

class MessagesController extends Controller
{
    protected $database;
    public $user_id;

    public function __construct(Database $database)
    {
        $this->database = $database; //Undefined property '$database'.intelephense(P1014)

    }


    public function index($user_id)
    {
        $user = Admin::find(auth()->id());
        $conversations = Conversation::where('user_id', $user_id)->get();
      
        return view('admin-views.chat.index', ['conversations' => $conversations, 'user_id' => $user_id]);
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        broadcast(new PusherBroadcast($request->get('message')))->toOthers(); 
      
        // Create a new message reference
        $conversation = new Conversation();
        $conversation->user_id = $request->user_id;
        $conversation->message = $request->message;
        $conversation->save();

        return view('admin-views.chat.broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request)
    {
        $conversations = $this->database->getReference('messages')->getValue();
        return view('admin-views.chat.receive', ['message' => $request->get('message'), 'conversations' => $conversations]);
    }


}
