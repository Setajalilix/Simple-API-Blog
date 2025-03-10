<x-mail::message>
# Hi

You have invitation to team ** {{$invitation->team->name}} **
    You can accept or reject it in your [profile setting]({{$url}})

<x-mail::button :url="$url">
profile setting
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
