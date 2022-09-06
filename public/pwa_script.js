if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('https://appdukaan.com/notes/serviceworker.js', {
        scope: '.'
    }).then(function(registration) {
        console.log('Laravel PWA: ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
        console.log('Laravel PWA: ServiceWorker registration failed: ', err);
    });
}