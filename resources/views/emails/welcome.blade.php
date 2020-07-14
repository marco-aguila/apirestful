HOLA {{ $user->name }}
Gracias por crear una cuenta verificala usando este enlace: 
{{  route('verify',$user->verification_token) }}
