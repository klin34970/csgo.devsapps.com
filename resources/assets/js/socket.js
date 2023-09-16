import Echo from "laravel-echo"

window.io = require('socket.io-client');

if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001',
        auth: {
            headers: {
                'Authorization': 'Bearer ' + 'f01d66a880eb07cac726c0e507f5f41a'
            }
        }
    });
}