<!DOCTYPE html>
<html>
<head>
    <title>Driver Delivery Calendar</title>
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
                    Welcome, {{ $driverName }}
                </span>
                <a href="{{ route('driver.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                <a href="{{ route('driver.logout') }}" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2>Delivery Calendar</h2>
        <a href="{{ route('driver.dashboard') }}" class="btn btn-secondary mb-3">Go Back</a>
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
                        title: {!! json_encode($event->event_title . ': ' . $event->event_description) !!},
                        start: '{{ $event->event_date }}',
                        url: '{{ route("driver.available-deliveries") }}/{{ $event->delivery_id }}',
                        allDay: true
                    },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
</body>
</html>
