<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User; // mentees are stored in users table
use Illuminate\Support\Facades\Auth;

class MentorMessageController extends Controller
{
    // 📌 Show all conversations for a mentor
    public function index()
    {
        $mentor = Auth::user();

        $conversations = Conversation::with(['mentee', 'latestMessage'])
            ->where('mentor_id', $mentor->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('mentor.mentor-d-message', compact('conversations'));
    }

    // 📌 Open or create chat with a specific mentee
    public function chat(User $mentee)
    {
        $mentor = Auth::user();

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            ['mentor_id' => $mentor->id, 'mentee_user_id' => $mentee->id],
            ['last_message_at' => now()]
        );

        // Get all messages (oldest first)
        $messages = $conversation->messages()->orderBy('created_at')->get();

        // Mark mentee->mentor messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'mentee')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('mentor.mentor-d-chatbox', compact('mentee', 'conversation', 'messages'));
    }

    // 📌 Send a message as mentor
    public function send(Request $request, Conversation $conversation)
    {
        // Ensure the logged-in mentor owns this conversation
        abort_unless($conversation->mentor_id === Auth::id(), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'mentor',   // 👈 now mentor
            'sender_id'       => Auth::id(),
            'body'            => $data['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }

    // 📌 Show conversation directly via ID
    public function show(Conversation $conversation)
    {
        // Ensure this conversation belongs to the logged-in mentor
        abort_unless($conversation->mentor_id === Auth::id(), 403);

        $conversation->load('mentee');

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('mentor.mentor-d-chatbox', [
            'conversation' => $conversation,
            'messages'     => $messages,
            'mentee'       => $conversation->mentee, // ✅ pass mentee
        ]);
    }
}
