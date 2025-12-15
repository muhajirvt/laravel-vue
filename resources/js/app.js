import './bootstrap';
import { createApp } from 'vue';
import Users from './App.vue';
import "@fortawesome/fontawesome-free/css/all.min.css";
import Echo from 'laravel-echo';
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

createApp(Users).mount('#app');

window.Echo.channel('public-chat')
    .listen('PublicMessageSent', (e) => {
        console.log('Received:', e.message);
    });


