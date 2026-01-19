@component('mail::message')
    # ⚠️ Check-in missed

    Hi {{ $user->name }},

    You missed your scheduled check-in.

    This is **not an emergency yet**, but if you don’t check in soon, your emergency contacts **will be notified automatically**.

    ---

    ### ⏰ Important details

    - **Last check-in:**
    {{ optional($user->last_check_in_at)->timezone($user->timezone)->toDayDateTimeString() }}

    - **Grace period ends:**
    {{ $user->graceExpiresAt()->timezone($user->timezone)->toDayDateTimeString() }}

    ---

    @component('mail::button', ['url' => route('dashboard')])
        Check in now
    @endcomponent

    If you’re okay, please check in as soon as possible.

    Thanks,
    **{{ config('app.name') }}**
@endcomponent
