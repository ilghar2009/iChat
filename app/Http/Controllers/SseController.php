<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class SseController extends Controller
{
    public function stream(){
        header('Content-Type:text/event-stream');
        header('Cache-Control:no-cache');
        header('Connection:keep-alive');

        while(true){
            if(connection_aborted()){
                break;
            }

            $newMessage = Message::where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get();

            if(isset($newMessage)){
                foreach($newMessage as $message){
                    echo "id: {$message->id}\n";
                    echo "data: " . json_encode([
                            'id' => $message->id,
                            'user_id' => $message->user_id,
                            'user_name' => $message->user->name ?? 'Unknown', // نام کاربر
                            'body' => $message->body,
                            'created_at' => $message->created_at->diffForHumans() // زمان نسبی مثل "۲ دقیقه پیش"
                        ]) . "\n\n";

                    // ارسال داده‌ها به کلاینت
                    flush();

                    // آپدیت کردن lastId برای اینکه دفعه بعد فقط پیام‌های جدیدتر را بگیرد
                    $lastId = $message->id;
                }
            }
        }
    }
}
