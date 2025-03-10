<x-mail::message>
    # Hi

    You have invitation to team #{{$invitation->team->name}}
    You can accept or reject it in your ({{$url}})[profile setting] after you sign-in in our website

<x-mail::button :url="$url">
sign-in
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
