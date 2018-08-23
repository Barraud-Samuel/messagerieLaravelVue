<?php
namespace App\Repository;
use App\User;
use App\Message;
use Carbon\Carbon;
use PhpParser\Builder;

class ConversationRepository {
    private $user;
    public function  __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversations (int $userId) {
        return $this->user->newQuery()
            ->select('name','id')
            ->where('id','!=', $userId)
            ->get();
    }

    public function createMessage(string $content, int $from, int $to) {
        return $this->message->newQuery()->create([
            'content' => $content,
            'from_id' => $from,
            'to_id' => $to,
            'created_at' => Carbon::now()
        ]);
    }

    public function getMessagesFor(int $from,int $to):\Illuminate\Database\Eloquent\Builder {
        return $this->message->newQuery()
            ->newQuery("((from_id = $from AND to_id = $to) OR (from_id = $to AND to_id = $from))")
            ->orderBy('created_at', 'DESC');
    }
}