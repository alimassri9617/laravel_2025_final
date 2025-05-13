<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat with Client</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Existing CSS unchanged */
    body, html {
      height: 100%;
    }
    .card-header:first-child {
      border-radius: 0;
    }
    .chat-container {
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .chat-body {
      flex: 1 1 auto;
      overflow-y: auto;
      padding: 1rem;
      background: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
    }
    .chat-footer {
      flex: 0 0 auto;
      padding: 0.75rem;
      background: #fff;
      border-top: 1px solid #dee2e6;
    }
    .message {
      margin-bottom: 1rem;
      display: flex;
    }
    .message.client {
      justify-content: flex-start;
    }
    .message.driver {
      justify-content: flex-end;
    }
    .message .text {
      max-width: 75%;
      padding: 0.75rem 1rem;
      border-radius: 1rem;
      word-break: break-word;
    }
    .message.client .text {
      background-color: #e2e3e5;
      color: #000;
    }
    .message.driver .text {
      background-color: #0d6efd;
      color: #fff;
    }
    .message small {
      display: block;
      margin-top: 0.25rem;
      font-size: 0.75rem;
      color:rgb(39, 39, 39);
    }
    textarea {
      resize: none;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-light d-flex flex-column">
  <div class="container-fluid chat-container p-0">
    <div class="card border-0 h-100 rounded-0">
      <div class="card-header bg-primary text-white py-2">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0">Chat with Client: {{ $client->fname ?? 'Client' }} {{ $client->lname ?? '' }}</h6>
            <small>Delivery ID: #{{ $delivery->id }}</small>
          </div>
        </div>
      </div>

      <div class="chat-body" id="chatMessages">
        @foreach($messages as $message)
          <div class="message {{ $message->sender_type }}">
            <div class="text">
              {{ $message->message }}
              <small>{{ $message->created_at->format('H:i, M d') }}</small>
            </div>
          </div>
        @endforeach
      </div>

      <div class="chat-footer">
        <form id="chatForm" action="{{ route('client.chat.store') }}" method="POST" class="d-flex align-items-center">
          @csrf
          <input type="hidden" name="sender_type" value="driver">
          <input type="hidden" name="sender_id" value="{{ session('driver_id') }}">
          <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">

          <textarea class="form-control me-2" name="message" placeholder="Type your message..." rows="1" required
                    oninput="autoResize(this)"></textarea>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    function autoResize(textarea) {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
      cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
      encrypted: true,
      authEndpoint: '/broadcasting/auth',
      auth: { headers: { 'X-CSRF-TOKEN': csrfToken } }
    });

    const channel = pusher.subscribe('private-delivery.{{ $delivery->id }}');
    channel.bind('App\\Events\\NewMessage', data => {
      // Skip echo of own messages
      if (data.sender_type !== 'driver' || data.sender_id != {{ session('driver_id') }}) {
        appendMessage(data.message, data.sender_type, data.created_at);
      }
    });

    function appendMessage(msg, sender, ts) {
      const wrapper = document.createElement('div');
      wrapper.classList.add('message', sender);

      const date = new Date(ts);
      const formatted = `${date.getHours()}:${String(date.getMinutes()).padStart(2,'0')}, ` +
                        `${date.toLocaleString('default',{month:'short'})} ${date.getDate()}`;

      wrapper.innerHTML = `<div class="text">${msg}<small>${formatted}</small></div>`;
      chatMessages.appendChild(wrapper);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    document.getElementById('chatForm').addEventListener('submit', e => {
      e.preventDefault();
      const ta = e.target.querySelector('textarea');
      const text = ta.value.trim();
      if (!text) return;
      appendMessage(text, 'driver', new Date());
      const fd = new FormData(e.target);
      fetch(e.target.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': fd.get('_token'), 'Accept': 'application/json' },
        body: fd
      })
      .then(res => res.json())
      .then(json => { if (!json.success) console.error(json.error); })
      .catch(console.error);
      ta.value = '';
      autoResize(ta);
    });

    // Auto-reload fallback every 10 seconds
    setInterval(() => window.location.reload(), 10000);
  </script>
</body>
</html>
