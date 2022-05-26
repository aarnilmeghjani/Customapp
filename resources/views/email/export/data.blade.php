@component('mail::message')

    {{ $data }}

    Thanks,
    {{ config('app.name') }}
@endcomponent
