import Echo from "laravel-echo";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: '{{ config("broadcasting.connections.pusher.key") }}',
    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
    forceTLS: true,
    auth: {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    },
    authTransport: "ajax",
    authTransportOptions: {
        fetchOptions: { credentials: "include" },
    },
});
