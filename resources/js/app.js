require('./bootstrap');

Echo.channel('payment-processed')
    .listen('.payment.processed', (e) => {
        console.log('message: ' + e.message);
        alert(e.message);
    });