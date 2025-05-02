// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
        });
    }

    // Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCeExqfPBn-waAHIdGVs1fc7rnVhRvTxTM",
    authDomain: "drivenotify-668ec.firebaseapp.com",
    projectId: "drivenotify-668ec",
    storageBucket: "drivenotify-668ec.firebasestorage.app",
    messagingSenderId: "533476272756",
    appId: "1:533476272756:web:1c1eec250528517ab0bf21",
    measurementId: "G-NTBP5WD0WV"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

function getTokenAndSend() {
    console.log('Checking notification permission:', Notification.permission);
    messaging.getToken({ vapidKey: 'BJFtevmdLx8OIxfx2wIdqDKSdEDQq5B6Ol6w1ouqavGLvBdk_4nC603tyjkfY8mD1M2pFIm_0zTuAwaE2wzxhzg' }).then((currentToken) => {
        if (currentToken) {
            console.log('FCM Token:', currentToken);
            // Send token to backend to save
            fetch('/driver/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin',
                body: JSON.stringify({ fcm_token: currentToken })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Token saved:', data);
            })
            .catch(error => {
                console.error('Error saving token:', error);
            });
        } else {
            console.log('No registration token available. Request permission to generate one.');
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
    });
}

if (Notification.permission === 'granted') {
    getTokenAndSend();
} else if (Notification.permission !== 'denied') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            getTokenAndSend();
        } else {
            console.log('Notification permission denied');
        }
    });
} else {
    console.log('Notification permission denied previously');
}

// Handle incoming messages while app is in foreground
messaging.onMessage(function(payload) {
    console.log('Message received. ', payload);
    alert(payload.notification.title + ": " + payload.notification.body);
});


    // Form validation for login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            // Simulate login success
            alert('Login successful! Redirecting...');
            window.location.href = 'client-dashboard.html';
        });
    }

    // Simulate delivery request submission
    const deliveryRequestBtn = document.querySelector('#newDeliveryModal .btn-primary');
    if (deliveryRequestBtn) {
        deliveryRequestBtn.addEventListener('click', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('newDeliveryModal'));
            alert('Delivery request submitted successfully!');
            modal.hide();
        });
    }

    // Track button functionality
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        if (btn.textContent.trim() === 'Track') {
            btn.addEventListener('click', function() {
                alert('Opening tracking map...');
            });
        }
    });

    // Chat button functionality
    document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
        if (btn.textContent.trim() === 'Chat') {
            btn.addEventListener('click', function() {
                alert('Opening chat window...');
            });
        }
    });
});

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
