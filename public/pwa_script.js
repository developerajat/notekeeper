if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('https://keepanything.com/serviceworker.js', {
        scope: '.'
    }).then(function(registration) {
        console.log('Laravel PWA: ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
        console.log('Laravel PWA: ServiceWorker registration failed: ', err);
    });
}