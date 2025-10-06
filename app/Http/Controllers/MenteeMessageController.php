<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;

class MenteeMessageController extends Controller
{
    // Show all conversations for a mentee
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::with(['mentor', 'latestMessage'])
            ->where('mentee_user_id', $user->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('mentee.mentee-d-message', compact('conversations'));
    }

    // Start or show chat with a specific mentor
    public function chat(Mentor $mentor)
    {
        $mentee = Auth::user();

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            ['mentee_user_id' => $mentee->id, 'mentor_id' => $mentor->id],
            ['last_message_at' => now()]
        );

        // Get all messages (oldest first)
        $messages = $conversation->messages()->orderBy('created_at')->get();

        // Mark mentor->mentee messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'mentor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('mentee.mentee-d-chatbox', compact('mentor', 'conversation', 'messages'));
    }

    // Send a new message inside a conversation
    public function send(Request $request, Conversation $conversation)
{
    // Ensure the logged-in mentee owns this conversation
    abort_unless($conversation->mentee_user_id === Auth::id(), 403);

    $data = $request->validate([
        'body' => ['required','string','max:5000'],
    ]);

    Message::create([
        'conversation_id' => $conversation->id,
        'sender_type'     => 'mentee',
        'sender_id'       => Auth::id(),
        'body'            => $data['body'],
    ]);

    $conversation->update(['last_message_at' => now()]);

    return back();
}


    // ✅ Show a specific conversation (for /mentee/messages/{conversation})
    public function show(Conversation $conversation)
{
    // Ensure this conversation belongs to the logged-in mentee
    abort_unless($conversation->mentee_user_id === Auth::id(), 403);

    // Load mentor
    $conversation->load('mentor');

    $messages = $conversation->messages()->orderBy('created_at')->get();

    return view('mentee.mentee-d-chatbox', [
        'conversation' => $conversation,
        'messages'     => $messages,
        'mentor'       => $conversation->mentor, // ✅ pass mentor
    ]);
}

}
