// server.js
const express = require('express');
const http = require('http');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*", // in production replace with your Laravel domain
    methods: ["GET", "POST"]
  }
});

io.on('connection', (socket) => {
  console.log('New client connected:', socket.id);

  socket.on('join-room', (roomId, userName) => {
    console.log(`${userName} joined room ${roomId}`);
    socket.join(roomId);
    socket.to(roomId).emit('user-joined', socket.id, userName);

    socket.on('signal', (payload) => {
      if (payload && payload.to) {
        io.to(payload.to).emit('signal', payload);
      }
    });

    socket.on('leave-room', (roomId) => {
      socket.leave(roomId);
      socket.to(roomId).emit('user-left', socket.id);
    });

    socket.on('disconnect', () => {
      socket.to(roomId).emit('user-left', socket.id);
    });
  });
});

server.listen(3000, () => {
  console.log('Signaling server running on http://localhost:3000');
});
