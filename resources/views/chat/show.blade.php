<!DOCTYPE html>
<html>
<head>
    <title>Chat with Driver</title>
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
        // Scroll chat to bottom
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Pusher setup for real-time updates
        Pusher.logToConsole = false;
        var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true,
            forceTLS: true,
            enabledTransports: ['ws', 'wss', 'xhr_polling', 'xhr_streaming', 'sockjs'],
            authEndpoint: '/broadcasting/auth'
        });

        var channel = pusher.subscribe('private-delivery.{{ $delivery->id }}');
        channel.bind('App\\Events\\NewMessage', function(data) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', data.sender_type);
            messageDiv.innerHTML = `<div class="text">${data.message}<br><small class="text-muted">${new Date(data.created_at).toLocaleString()}</small></div>`;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });

        // AJAX form submission for sending messages
        const chatForm = document.getElementById('chatForm');
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(chatForm);
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
                chatForm.message.value = '';
            })
            .catch(error => {
                alert('Error sending message');
                console.error(error);
            });
        });
    </script>
</body>
</html>
