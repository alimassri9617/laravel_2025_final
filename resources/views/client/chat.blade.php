<!DOCTYPE html>
<html>
<head>
    <title>Chat with Driver</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #chatMessages {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #f9f9f9;
        }
        .message {
            margin-bottom: 10px;
        }
        .message.client {
            text-align: right;
        }
        .message.driver {
            text-align: left;
        }
        .message .text {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 70%;
        }
        .message.client .text {
            background-color: #007bff;
            color: white;
        }
        .message.driver .text {
            background-color: #e2e3e5;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h3>Chat with Driver: {{ $driver->fname }} {{ $driver->lname }}</h3>
        <p>Delivery ID: #{{ $delivery->id }}</p>
        <div id="chatMessages">
            @foreach($messages as $message)
                <div class="message {{ $message->sender_type }}">
                    <div class="text">
                        {{ $message->message }}
                        <br>
                        <small class="text-muted">{{ $message->created_at->format('H:i, M d') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        <form id="chatForm" action="{{ route('client.chat.store') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="sender_type" value="client">
            <input type="hidden" name="sender_id" value="{{ session('client_id') }}">
            <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
            <div class="input-group">
                <textarea class="form-control" name="message" placeholder="Type your message" required></textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Enable Pusher logging for debugging - comment out in production
        Pusher.logToConsole = true;

        // Scroll chat to bottom on page load
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Get the CSRF token first
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Pusher setup for real-time updates
        const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
            cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        });

        // Wait for connection to be established
        pusher.connection.bind('connected', function() {
            console.log('Pusher connected!');
            // Now try to subscribe to the private channel
            const channel = pusher.subscribe('private-delivery.{{ $delivery->id }}');

            channel.bind('App\\Events\\NewMessage', function(data) {
                console.log('New message received from Pusher:', data);
                // Prevent adding the same message twice if it was sent by this client
                // We already added it immediately on send
                if (data.sender_type !== 'client' || data.sender_id != {{ session('client_id') }} ) {
                     appendMessage(data.message, data.sender_type, data.created_at);
                }
            });

            // Handle subscription success
            channel.bind('pusher:subscription_succeeded', function() {
                console.log('Subscription to private channel succeeded.');
            });

             // Handle subscription error
            channel.bind('pusher:subscription_error', function(status) {
                console.error('Subscription error:', status);
            });
        });

        // Handle Pusher connection error
        pusher.connection.bind('error', function(err) {
            console.error('Pusher connection error:', err);
        });


        // Function to append a message to the chat box
        function appendMessage(message, senderType, timestamp) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', senderType);

            const date = new Date(timestamp);
            const formattedDate = `${date.getHours()}:${String(date.getMinutes()).padStart(2, '0')}, ${date.toLocaleString('default', { month: 'short' })} ${date.getDate()}`;

            messageDiv.innerHTML = `<div class="text">${message}<br><small class="text-muted">${formattedDate}</small></div>`;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the latest message
        }


        // AJAX form submission for sending messages
        const chatForm = document.getElementById('chatForm');
        const messageInput = chatForm.querySelector('textarea');

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            const message = messageInput.value.trim();
            if (!message) {
                return; // Don't send empty messages
            }

            // Immediately display the message in the chat box
            const now = new Date(); // Use current time for immediate display
            appendMessage(message, 'client', now);

            // Prepare form data for backend submission
            const formData = new FormData(chatForm);

            // Send the message to the backend asynchronously
            fetch(chatForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Message sent successfully to backend.');
                    // The message should appear via Pusher on the driver's side
                    // and potentially update the timestamp on the client side if needed,
                    // but for immediate display we already added it.
                } else {
                    console.error('Error sending message to backend:', data.error);
                    // Optionally, show an error message to the user
                }
            })
            .catch(error => {
                console.error('Network error sending message:', error);
                // Optionally, show an error message to the user
            });

            // Clear the input field after sending
            messageInput.value = '';
        });
    </script>
    <script>
<script>
  // Reload the page every 2000 milliseconds (2 seconds)
  setInterval(() => {
    window.location.reload();
  }, 2000);
</script>
</script>
</body>
</html>