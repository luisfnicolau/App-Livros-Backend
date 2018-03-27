<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Chat;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $message = 'error';
        if($request->has('chat_id')){
            $chats = DB::table('chats')
                ->where('id', $request->get('chat_id'))
                ->get();
        } else if($request->get('client_id') == $request->get('book_owner_id')) {
            $chats = DB::table('chats')
                ->where('client_id', $request->get('client_id'))
                ->orwhere('book_owner_id', $request->get('book_owner_id'))
                ->get();
        } else if($request->get('book_id') != -1){
            $chats = DB::table('chats')
                ->where('client_id', $request->get('client_id'))
                ->where('book_id', $request->get('book_id'))
                ->get();
        } else {
            $bookOwnerId = $request->get('book_owner_id');
            $clientId = $request->get('client_id');
            $chats = DB::table('chats')
                    ->where('client_id', $clientId)
                    ->where('book_owner_id', $bookOwnerId)
                   ->get();
        }
        
//        foreach ($chats->toArray() as $chat){
//            $messages = DB::table('messages')
//                    ->where('chat_id', $chat->id)
//                    ->where('read', 0)
//                   ->get();
//            $chat->messages_not_read = $messages->count();
//            $chat->save();
//        }
        
        if($chats)
            $message = 'sucess';
        
        return response()->json(array('message' => $message, 'data' => $chats->toArray()));
    }

    public function store(Request $request)
    {
        $message = 'error';

        $bookOwnerId = $request->get('book_owner_id');
        $clientId = $request->get('client_id');
        $bookId = $request->get('book_id');

        
        $chats[0] = new Chat();
        $chats[0]->book_owner_id = $bookOwnerId;
        $chats[0]->client_id = $clientId;
        $chats[0]->book_id = $bookId;
        
        if($chats[0]->save()){
            $message = 'sucess';
        }
        
        return response()->json(array('message' => $message, 'data' => $chats));

    }
}
