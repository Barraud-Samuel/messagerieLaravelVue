<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessage;
use App\Repository\ConversationRepository;
use App\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{

    private $r;
    private $auth;

    public function __construct(ConversationRepository $conversationRepository, AuthManager $auth)
    {
        $this->r = $conversationRepository;
        $this->auth = $auth;
    }

    public function index () {
        return view('conversations.index',[
            'users' => $this->r->getConversations($this->auth->user()->id)
        ]);
    }

    public function show (User $user) {
        return view('conversations.show',[
            'users' => $this->r->getConversations($this->auth->user()->id),
            'user' => $user,
            'messages' => $this->r->getMessagesFor($this->auth->user()->id,$user->id)->paginate(50)
        ]);
    }

    public function store (User $user, StoreMessage $request) {
        $this->r->createMessage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );
        return redirect(route('conversations.show',['id'=>$user->id]));
    }
}
