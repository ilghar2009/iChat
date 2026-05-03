<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return view('index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => auth()->user->user_id,
            'body' => $request->body,
        ]);

        return redirect()->view('index')->with('message', $message);
    }
}
