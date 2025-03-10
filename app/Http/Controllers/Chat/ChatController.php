<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\IChat;
use App\Repositories\Contracts\IMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private IChat $chat;
    private IMessage $message;


    public function __construct(IChat $chat, IMessage $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'body' => 'required',
            'recipient' => 'required'
        ]);
        $body = $request->body;
        $user = Auth::id();
        $recipient = $request->recipient;

        $chat = $this->$user->getChatWithUser($recipient);
        if (!$chat) {
            $chat = $this->chat->create([]);
        }
        $message = $chat->messages()->create([
            'body' => $body,
            'chat_id' => $chat->id,
            'user_id' => $user,
            'last_read' => null
        ]);
        return new MessageResource($message);

    }

    public function getUserChats()
    {
        $chats = $this->chat->getUserChats();
        return MessageResource::collection($chats);
    }

    public function getUserMessages($chatId)
    {
        $message = $this->message->findWhere('chat_id', $chatId);
        return MessageResource::collection($message);

    }

    public function destroyMessage($id)
    {
        $message = $this->message->find($id);
        $this->authorize('delete', $message);
        $this->message->delete($message);
    }

    public function markAsRead($id)
    {
        $chat = $this->message->find($id);
        $chat->markAsReadForUser(Auth::id());

        return response()->json(['message' => 'Read'], 200);
    }

}
