<script>
  if (typeof Capacitor.isNative === 'undefined' && user) {
    const messaging = firebase.messaging();

    // Get registration token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.
    messaging.getToken({vapidKey: 'BAHdePTbEJGCVXgs1PruHTKAxcokuFj9rO-1j1BUZlqW0_o9NppnjJ-PpQTY-SnwsVzMXtx9MTj4Of76FuPp8rI'}).then((currentToken) => {
      if (currentToken) {
        if (user.memberToken.device) {
          console.log(user.memberToken.device);
          return;
        }

        let authorization = 'Bearer ' + user.memberToken.token;

        let settings = {
          'url': '/api/v1/members/token-device',
          'method': 'POST',
          'timeout': 0,
          'headers': {
            'Authorization': authorization,
            'Content-Type': 'application/json'
          },
          'data': JSON.stringify({'device': currentToken}),
        };

        $.ajax(settings).done(function (response) {
          console.log(response);
        });

      } else {
        // Show permission request UI
        console.log('No registration token available. Request permission to generate one.');
      }
    }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
    });
  }
</script>
