<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session; // <-- adjust if your model is named differently
use App\Events\VideoCallEvent;
use App\Events\VideoCallChat;

class VideoCallController extends Controller
{
    public function index($room_id)
{
    $session = Session::findOrFail($room_id);
    return view('sessions.video', compact('session'));
}

    public function signal(Request $request, $room_id)
    {
        $signal = $request->signal;
        broadcast(new VideoCallEvent(auth()->user(), $signal, $room_id))->toOthers();
        return response()->json(['ok' => true]);
    }

    public function chat(Request $request, $room_id)
    {
        $message = $request->message;
        broadcast(new VideoCallChat(auth()->user(), $message, $room_id))->toOthers();
        return response()->json(['ok' => true]);
    }
}
