<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Http\Requests\StoreConversationRequest;

class ConversationController extends Controller
{
    public function list($sender)
    {
        $response_data = Conversation::where('sender_id', $sender)
            ->latest('created_at')
            ->with('receiver')
            ->get();

        $response_data = $response_data->unique(function ($item) {
            return $item->receiver_id;
        })->values();

        $transformed_data = $response_data->map(function ($item) {
            return [
                'id' => $item->receiver_id,
                'name' => $item->receiver->name,
                'text' => $item->text,
            ];
        });

        return response()->json($transformed_data);

    }

    public function store(StoreConversationRequest $request)
    {
        $response_data = Conversation::create($request->all());

        if ($response_data) {
            $message = 'Sent Successfully';
        } else {
            $message = 'Error occurred when sending';
        }

        return response()->json($message);
    }

    public function show($sender, $receiver)
    {
        $response_data = Conversation::where(function ($query) use ($sender, $receiver) {
            $query->where('sender_id', $sender)
                ->orWhere('sender_id', $receiver);
        })
            ->where(function ($query) use ($sender, $receiver) {
                $query->where('receiver_id', $sender)
                    ->orWhere('receiver_id', $receiver);
            })
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->get();

        $transformedData = $response_data->map(function ($data) {
            return $this->transformConversation($data);
        });

        return response()->json($transformedData);
    }

    public function destroy($conversation)
    {
        Conversation::where('sender_id', $conversation)->delete();
    }

    public function transformConversation($data)
    {
        $data = $data->toArray();

        $modifiedChatData['sender_id'] = $data['sender']['id'];
        $modifiedChatData['sender'] = $data['sender']['name'];
        $modifiedChatData['receiver_id'] = $data['receiver']['id'];
        $modifiedChatData['receiver'] = $data['receiver']['name'];
        $modifiedChatData['text'] = $data['text'];

        return $modifiedChatData;
    }
}
