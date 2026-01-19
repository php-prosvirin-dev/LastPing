<x-app-layout>
    <div class="py-12">
        @include('dashboard.partials.welcome')

        @include('dashboard.partials.checkin-timer', [
            'interval_seconds' => $user['interval_seconds'],
            'last_check_in_at' => $user['last_check_in_at'],
        ])
    </div>
</x-app-layout>
