{{-- resources/views/sessions/video.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call - {{ $session->title }}</title>
    @vite('resources/css/videocall.css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="video-call-container">

    <!-- Left: Video Grid -->
    <div class="video-section">
        <div class="video large">
            <div class="username">{{ auth()->user()->first_name ?? 'You' }}</div>
        </div>
    </div>

    <!-- Right: Chat + Controls -->
    <div class="sidebar">
        <div class="chat-box">
            <h3>Chat</h3>
            <div class="messages" id="messages"></div>
            <div class="chat-input">
                <input type="text" id="chatMessage" placeholder="Type a message...">
                <button id="sendChat"><span class="material-icons">send</span></button>
            </div>
        </div>
        <div class="controls">
            <button class="control" id="toggleMic"><span class="material-icons">mic</span></button>
            <button class="control" id="toggleCamera"><span class="material-icons">videocam</span></button>
            <button class="control end" id="endCall"><span class="material-icons">call_end</span></button>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<script>
const userId = {{ auth()->user()->id }};
const roomId = "{{ $session->id }}";
let localStream, peerConnections = {};

// Local video
const localVideo = document.querySelector('.video.large');
navigator.mediaDevices.getUserMedia({ video:true, audio:true })
.then(stream => {
    localStream = stream;
    const v = document.createElement('video');
    v.srcObject = stream;
    v.autoplay = true;
    v.muted = true;
    localVideo.appendChild(v);
})
.catch(console.error);

// Laravel Echo (Pusher)
window.Pusher = Pusher;
const echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ env("PUSHER_APP_KEY") }}',
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: true,
});

const channel = echo.private('video-room.' + roomId);

// ✅ Listen for WebRTC signals
channel.listen('.VideoCallSignal', e => {
    console.log("Got signal:", e);
    if(!peerConnections[e.user.id]){
        startPeer(e.user.id, false, e.signal);
    } else if(e.signal.candidate){
        peerConnections[e.user.id].addIceCandidate(new RTCIceCandidate(e.signal.candidate));
    }
});

// ✅ Listen for chat
channel.listen('.VideoCallChat', e => {
    console.log("Chat:", e);
    addChatMessage(e.user.name, e.message);
});

// Start WebRTC Peer
function startPeer(id, isInitiator=true, remoteSignal=null){
    const pc = new RTCPeerConnection();
    localStream.getTracks().forEach(track=>pc.addTrack(track, localStream));

    pc.ontrack = e => {
        const videoEl = document.createElement('video');
        videoEl.srcObject = e.streams[0];
        videoEl.autoplay = true;
        document.querySelector('.video-section').appendChild(videoEl);
    };

    pc.onicecandidate = e => {
        if(e.candidate){
            axios.post(`/mentor/sessions/${roomId}/signal`, { signal:{candidate:e.candidate} });
        }
    };

    peerConnections[id] = pc;

    if(isInitiator){
        pc.createOffer()
        .then(offer=>pc.setLocalDescription(offer))
        .then(()=>axios.post(`/mentor/sessions/${roomId}/signal`, { signal:{sdp: pc.localDescription} }));
    } else if(remoteSignal){
        pc.setRemoteDescription(new RTCSessionDescription(remoteSignal.sdp))
        .then(()=>pc.createAnswer())
        .then(answer=>pc.setLocalDescription(answer))
        .then(()=>axios.post(`/mentor/sessions/${roomId}/signal`, { signal:{sdp: pc.localDescription} }));
    }
}

// Add Chat Message to UI
function addChatMessage(user, message) {
    const mEl = document.createElement('div');
    mEl.className = 'message';
    mEl.innerHTML = `<span>${user}:</span> ${message}`;
    document.getElementById('messages').appendChild(mEl);
}

// Chat send
const chatInput = document.getElementById('chatMessage');
const sendChat = document.getElementById('sendChat');
sendChat.addEventListener('click', () => {
    const msg = chatInput.value.trim();
    if(!msg) return;
    axios.post(`/mentor/sessions/${roomId}/chat`, { message: msg });
    chatInput.value = '';
});

// Controls
document.getElementById('toggleMic').onclick = () => {
    localStream.getAudioTracks().forEach(track => track.enabled = !track.enabled);
};
document.getElementById('toggleCamera').onclick = () => {
    localStream.getVideoTracks().forEach(track => track.enabled = !track.enabled);
};
document.getElementById('endCall').onclick = () => {
    Object.values(peerConnections).forEach(pc => pc.close());
    window.location.href = "{{ route('mentor.sessions.index') }}";
};
</script>
</body>
</html>
