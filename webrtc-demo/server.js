// server.js
const express = require('express');
const http = require('http');
const path = require('path');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = new Server(server);

// Serve static files from ./public
app.use(express.static(path.join(__dirname, 'public')));

// Simple health route
app.get('/ping', (req, res) => res.send('ok'));

// Socket.IO signaling
io.on('connection', (socket) => {
  console.log('Socket connected:', socket.id);

  // When a client joins a room
  socket.on('join-room', (roomId, userName) => {
    console.log(`${socket.id} joining ${roomId} as ${userName}`);
    socket.join(roomId);

    // Tell existing clients in the room that a new user joined
    socket.to(roomId).emit('user-joined', socket.id, userName);

    // Relay generic signaling messages: { to, from, data }
    socket.on('signal', (payload) => {
      // forward to recipient
      if (payload && payload.to) {
        io.to(payload.to).emit('signal', payload);
      }
    });

    // Handle explicit leaving (optional)
    socket.on('leave-room', (r) => {
      socket.leave(r);
      socket.to(r).emit('user-left', socket.id);
    });

    socket.on('disconnect', () => {
      console.log('Socket disconnected:', socket.id);
      socket.to(roomId).emit('user-left', socket.id);
    });
  });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log(`Server running: http://localhost:${PORT}`));