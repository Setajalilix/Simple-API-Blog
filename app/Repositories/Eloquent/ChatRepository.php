<?php

namespace App\Repositories\Eloquent;

use App\Models\Chat;
use App\Repositories\Contracts\IChat;

class ChatRepository extends BaseRepository implements IChat
{

    public function model(): string
    {
        return Chat::class;
    }

    public function createParticipant($chatId, array $data)
    {
        $chat= $this->model->find($chatId);
        $chat->participants()->sync($data['participant']);
    }

    public function getUserChats()
    {
        return auth()->user()->chats()->with(['message','participants'])->get();
    }
}
