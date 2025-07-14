setTimeout(() => {
    window.Echo.channel('notification')
        .listen('.PatrolNotitificationEvent', (e) => {
            console.log(e);
        }).error((error) => {
            console.error('Error:', error);
        });
}, 1000);