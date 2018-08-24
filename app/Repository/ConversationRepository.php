<?php
namespace App\Repository;
use App\User;
use App\Message;
use Carbon\Carbon;
use PhpParser\Builder;

/**
 * Class ConversationRepository
 * @package App\Repository
 */
class ConversationRepository {
    private $user;
    public function  __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversations (int $userId) {
        $conversations = $this->user->newQuery()
            ->select('name','id')
            ->where('id','!=', $userId)
            ->get();

        $unread = $this->unreadCount($userId);
        foreach ($conversations as $conversation) {
            if (isset($unread[$conversation->id])) {
                $conversation->unread = $unread[$conversation->id];
            }else{
                $conversation->unread = 0;
            }
        }
        return $conversations;
    }

    public function createMessage(string $content, int $from, int $to) {
        return $this->message->newQuery()->create([
            'content' => $content,
            'from_id' => $from,
            'to_id' => $to,
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * @param int $from
     * @param int $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getMessagesFor(int $from, int $to): \Illuminate\Database\Eloquent\Builder {
        return $this->message->newQuery()
            ->whereraw("((from_id = $from AND to_id = $to) OR (from_id = $to AND to_id = $from))")
            ->orderBy('created_at', 'DESC')
            ->with([
                'from' => function  ($query) { return $query->select('name', 'id'); }
            ]);
    }


    /**
     * @param int $userId
     * @return \Illuminate\Support\Collection|static
     */
    private function unreadCount (int $userId) {
        return $this->message->newQuery()
            ->where('to_id', $userId)
            ->groupBy('from_id')
            ->selectRaw('from_id, COUNT(id) as count')
            ->whereRaw('read_at IS NULL')
            ->get()
            ->pluck('count', 'from_id');
    }
}