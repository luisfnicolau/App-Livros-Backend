<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Message;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    
    public function index(Request $request)
    {
        $chatId = $request->get('chat_id');
        $message = 'fail';
        $messages = DB::table('messages')
                ->where('chat_id', $chatId)
                ->get();
        if($messages){
            DB::table('messages')
                    ->where('chat_id', $chatId)
                    ->update(['read' => 1]);
            DB::table('chats')
                    ->where('id', $chatId)
                    ->update(['messages_not_read' => 0]);
                    $message = 'succes';
        }
        
        return response()->json(array('message' => $message, 'messages' => $messages));
    }
    
    
    public function store(Request $request)
    {
        $senderId = $request->get('sender_id');
        $chatId = request()->get('chat_id');
        $text = $request->get('message');
        
        $message = new Message();
        $message->sender_id = $senderId;
        $message->chat_id = $chatId;
        $message->message = $text;
        
        if ($message->save()){
            $chat = DB::table('chats')
                    ->where('id', $chatId)
                    ->first();
            DB::table('chats')
                    ->where('id', $chatId)
                    ->update(['messages_not_read' =>$chat->messages_not_read + 1]);
                    $message = 'succes';
            return response()->json (array('message'=>'sucess'));
        }
        return response()->json (array('message'=>'fail'));
    }
}
