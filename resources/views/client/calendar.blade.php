<!DOCTYPE html>
<html>
<head>
    <title>Client Delivery Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">DeliveryApp</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Welcome, {{ $clientName }}
                </span>
                <a href="{{ route('client.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                <a href="{{ route('client.logout') }}" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2>Delivery Calendar</h2>
        <button onclick="history.back()" class="btn btn-secondary mb-3">&larr; </button>
        <div id='calendar'></div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach($calendarEvents as $event)
                    {
                        id: '{{ $event->delivery_id }}',
                        title: {!! json_encode($event->event_title) !!},
                        description: {!! json_encode($event->event_description) !!},
                        start: '{{ $event->event_date }}',
                        url: '{{ route("client.deliveries.show", $event->delivery_id) }}',
                        allDay: true
                    },
                    @endforeach
                ],
                eventContent: function(arg) {
                    let deliveryId = arg.event.id;
                    let title = arg.event.title;
                    let description = arg.event.extendedProps.description;
                    let date = arg.event.startStr;

                    let containerEl = document.createElement('div');
                    containerEl.classList.add('fc-event-custom');

                    let idEl = document.createElement('div');
                    idEl.textContent = 'Delivery ID: ' + deliveryId;
                    idEl.style.fontWeight = 'bold';

                    let titleEl = document.createElement('div');
                    titleEl.textContent = title;

                    let descriptionEl = document.createElement('div');
                    descriptionEl.textContent = description;

                    let dateEl = document.createElement('div');
                    dateEl.textContent = 'Date: ' + date;

                    containerEl.appendChild(idEl);
                    containerEl.appendChild(titleEl);
                    containerEl.appendChild(descriptionEl);
                    containerEl.appendChild(dateEl);

                    return { domNodes: [containerEl] };
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
