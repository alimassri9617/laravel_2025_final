// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.

importScripts('https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.1/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyCeExqfPBn-waAHIdGVs1fc7rnVhRvTxTM",
    authDomain: "drivenotify-668ec.firebaseapp.com",
    projectId: "drivenotify-668ec",
    storageBucket: "drivenotify-668ec.firebasestorage.app",
    messagingSenderId: "533476272756",
    appId: "1:533476272756:web:1c1eec250528517ab0bf21",
    measurementId: "G-NTBP5WD0WV"
  };

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
  
});



