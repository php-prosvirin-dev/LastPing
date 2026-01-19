<x-app-layout>
    <div class="py-12">
        <div
            x-data="checkInTimer({
                interval: {{ $userResource['interval_seconds'] }},
                lastCheckIn: {{ $userResource['last_check_in_at'] ?? 'null' }},
                logs: @js($userResource['check_ins'] ?? [])
            })"
            x-init="start()"
        >

            @include('dashboard.partials.welcome')

            @include('dashboard.partials.checkin-timer', [
                'interval_seconds' => $userResource['interval_seconds'],
                'last_check_in_at' => $userResource['last_check_in_at'],
            ])

            @include('dashboard.partials.log', [
                'check_ins' => $userResource['check_ins']
            ])
        </div>
</x-app-layout>
