<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat with Driver</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    html, body {
      height: 100%;
    }
    .chat-container {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .chat-header {
      flex: 0 0 auto;
      background: #0d6efd;
      color: white;
      padding: 0.75rem 1rem;
    }
    .chat-body {
      flex: 1 1 auto;
      overflow-y: auto;
      background: #f8f9fa;
      padding: 1rem;
    }
    .chat-footer {
      flex: 0 0 auto;
      padding: 0.75rem;
      border-top: 1px solid #dee2e6;
      background: #fff;
    }
    .message {
      margin-bottom: 1rem;
      display: flex;
    }
    .message.client {
      justify-content: flex-end;
    }
    .message.driver {
      justify-content: flex-start;
    }
    .message .text {
      max-width: 75%;
      padding: 0.75rem 1rem;
      border-radius: 1rem;
      word-break: break-word;
    }
    .message.client .text {
      background-color: #0d6efd;
      color: #fff;
    }
    .message.driver .text {
      background-color: #e2e3e5;
      color: #000;
    }
    .message small {
      display: block;
      margin-top: 0.25rem;
      font-size: 0.75rem;
      color: #6c757d;
    }
    textarea {
      resize: none;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container-fluid h-100 p-0">
    <div class="card border-0 rounded-0 h-100 chat-container">
      
      <div class="chat-header">
        <h5 class="mb-0">Chat with Driver: {{ $driver->fname }} {{ $driver->lname }}</h5>
        <small>Delivery ID: #{{ $delivery->id }}</small>
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
        <form id="chatForm" action="{{ route('client.chat.store') }}" method="POST" class="d-flex">
          @csrf
          <input type="hidden" name="sender_type" value="client">
          <input type="hidden" name="sender_id" value="{{ session('client_id') }}">
          <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
          
          <textarea name="message" class="form-control me-2" rows="1" placeholder="Type your message…" required
                    oninput="autoResize(this)"></textarea>
          <button type="submit" class="btn btn-primary align-self-end">
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>
    // Scroll to bottom on load
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    function autoResize(el) {
      el.style.height = 'auto';
      el.style.height = el.scrollHeight + 'px';
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
      cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
      encrypted: true,
      authEndpoint: '/broadcasting/auth',
      auth: { headers: { 'X-CSRF-TOKEN': csrfToken } }
    });

    const channel = pusher.subscribe('private-delivery.{{ $delivery->id }}');
    channel.bind('App\\Events\\NewMessage', data => {
      if (data.sender_type !== 'client' || data.sender_id != {{ session('client_id') }}) {
        appendMessage(data.message, data.sender_type, data.created_at);
      }
    });

    function appendMessage(msg, sender, ts) {
      const div = document.createElement('div');
      div.classList.add('message', sender);
      const date = new Date(ts);
      const time = `${date.getHours()}:${String(date.getMinutes()).padStart(2,'0')}, `
                 + `${date.toLocaleString('default',{month:'short'})} ${date.getDate()}`;
      div.innerHTML = `<div class="text">${msg}<small>${time}</small></div>`;
      chatMessages.appendChild(div);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    document.getElementById('chatForm').addEventListener('submit', e => {
      e.preventDefault();
      const ta = e.target.querySelector('textarea');
      const text = ta.value.trim();
      if (!text) return;
      appendMessage(text, 'client', new Date());
      const fd = new FormData(e.target);
      fetch(e.target.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': fd.get('_token'), 'Accept': 'application/json' },
        body: fd
      }).then(res => res.json())
        .then(json => { if (!json.success) console.error(json.error); })
        .catch(console.error);
      ta.value = '';
      autoResize(ta);
    });

    setInterval(() => {
    window.location.reload();
  }, 10000);
  </script>
  
</body>
</html>
