<script>
  if (typeof Capacitor !== 'undefined' && Capacitor.isNative && user) {
    // Request permission to use push notifications
    // iOS will prompt user and return if they granted permission or not
    // Android will just grant without prompting
    Capacitor.Plugins.PushNotifications.requestPermission().then(result => {
      if (result.granted) {
        // Register with Apple / Google to receive push via APNS/FCM
        Capacitor.Plugins.PushNotifications.register();
      } else {
        // Show some error
      }
    });

    Capacitor.Plugins.PushNotifications.addListener('registration', (token) => {
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
        'data': JSON.stringify({'device': token.value}),
      };

      $.ajax(settings).done(function (response) {
        console.log(response);
      });
    });

    Capacitor.Plugins.PushNotifications.addListener('registrationError', (error) => {
      console.log('Error on registration: ' + JSON.stringify(error));
    });

    Capacitor.Plugins.PushNotifications.addListener('pushNotificationReceived', (notification) => {
        console.log('Push received: ' + JSON.stringify(notification));
      },
    );

    Capacitor.Plugins.PushNotifications.addListener('pushNotificationActionPerformed', (notification) => {
        console.log('Push action performed: ' + JSON.stringify(notification));
      },
    );
  }
</script>
