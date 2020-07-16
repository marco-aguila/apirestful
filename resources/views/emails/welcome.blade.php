@component('mail::message')
# HOLA {{ $user->name }}
Gracias por crear una cuenta verificala usando este boton: 

@component('mail::button', ['url' =>  route('verify',$user->verification_token) ])
Confirmar mi cuenta
@endcomponent

GRACIAS,<br>
{{ config('app.name') }}
@endcomponent

