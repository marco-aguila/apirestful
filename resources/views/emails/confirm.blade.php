HOLA {{ $user->name }}
Has cambiado tu correo electronico, por favor  verificala en este enlace:
{{  route('verify',$user->verification_token) }}
