<?php

/** @var Botble\Member\Models\Member $user */
$user = auth()->guard('member')->user();

if ($user) {
  $data = $user->toArray();
  $data['memberToken'] = $user->getMemberToken()->toArray();
  $userJson = json_encode($data);
} else {
  $userJson = 'null';
}
?>

{!! Theme::partial('capacitor') !!}

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional

  const firebaseConfig = {
    apiKey: "AIzaSyCHI1fPqoPqsDp98dOJcDKeJ5lFVX7GMlY",
    authDomain: "eh-notifications.firebaseapp.com",
    projectId: "eh-notifications",
    storageBucket: "eh-notifications.appspot.com",
    messagingSenderId: "764713474979",
    appId: "1:764713474979:web:448998667371ac4f921816",
    measurementId: "G-HCQL1XMPYP"
  };

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

  var user = <?= $userJson ?>;
</script>

{!! Theme::partial('firebase_cloud_messaging_android') !!}
{!! Theme::partial('firebase_cloud_messaging_web') !!}