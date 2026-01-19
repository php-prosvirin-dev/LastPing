<x-app-layout>
    <div class="py-12">
        @include('dashboard.partials.welcome')

        @include('dashboard.partials.checkin-timer', [
            'intervalSeconds' => $intervalSeconds,
            'lastCheckIn' => $lastCheckIn,
        ])
    </div>
</x-app-layout>
