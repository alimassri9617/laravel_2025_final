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