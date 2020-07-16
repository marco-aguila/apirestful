@component('mail::message')
# HOLA {{ $user->name }}
 Has cambiado tu correo electronico, por favor  verificala en este boton:
 
@component('mail::button', ['url' => route('verify',$user->verification_token) ])
Confirmar mi cuenta
@endcomponent

GRACIAS,<br>
{{ config('app.name') }}
@endcomponent

