@component('mail::message')
    # 🚨 Emergency Alert

    {{ $messageText }}

    ---

    ### User Details
    - **Name:** {{ $user->name }}
    - **Email:** {{ $user->email }}
    - **Last Check-In:**
    {{ $lastCheckIn?->toDayDateTimeString() ?? 'Never' }}

    ---

    This message was sent automatically by **LastPing**
    because the user failed to check in within their configured time window.

    @component('mail::button', ['url' => config('app.url')])
        Open LastPing
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
