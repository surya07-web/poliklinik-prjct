import './bootstrap';

window.Echo.channel('antrian')
    .listen('.antrian.updated', (e) => {
        console.log('Realtime jalan:', e);

        location.reload();
    });