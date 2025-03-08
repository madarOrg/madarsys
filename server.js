// import { createServer } from 'http';
// import { Redis } from 'ioredis';
// import { Server } from 'socket.io';

// const server = createServer();

// const io = new Server(server, {
//     cors: {
//         origin: "*",
//     }
// });

// const redis = new Redis();

// redis.subscribe('posts', (err, count) => {
//     if (err) {
//         console.error('Failed to subscribe: %s', err.message);
//     } else {
//         console.log(`Subscribed successfully! This client is currently subscribed to ${count} channels.`);
//     }
// });

// redis.on('message', (channel, message) => {
//     const event = JSON.parse(message);
//     console.log(`Message received from channel ${event.event}: ${channel}`);
//     io.emit(event.event, channel, event.data);
// });

// io.on('connection', (socket) => {
//     console.log('a user connected');

//     socket.on('disconnect', () => {
//         console.log('user disconnected');
//     });
// });

// server.listen(6001, () => {
//     console.log('listening on *:6001');
// });


import { createServer } from 'http';
import { Redis } from 'ioredis';
import { Server } from 'socket.io';

const server = createServer();
console.log('فشل في الاشتراك: %s');

const io = new Server(server, {
    cors: {
        origin: "*", // تأكد من السماح بالاتصال من المصدر المناسب
    }
});

const redis = new Redis();

redis.subscribe('private-notifications.*', (err, count) => {
    if (err) {
        console.error('فشل في الاشتراك: %s', err.message);
    } else {
        console.log(`تم الاشتراك بنجاح! هذا العميل مشترك الآن في ${count} قنوات.`);
    }
});

redis.on('message', (channel, message) => {
    const event = JSON.parse(message);
    console.log(`تم تلقي رسالة من القناة ${event.event}: ${channel}`);
    
    // يتم بث الرسالة إلى جميع العملاء المتصلين
    io.emit(event.event, channel, event.data);
});

io.on('connection', (socket) => {
    console.log('تم الاتصال بمستخدم جديد');
    
    socket.on('disconnect', () => {
        console.log('تم قطع الاتصال بالمستخدم');
    });
});

server.listen(6001, () => {
    console.log('الاستماع على المنفذ *:6001');
});
