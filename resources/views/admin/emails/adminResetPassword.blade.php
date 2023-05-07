@component('mail::message')
<h1>Reset Account</h1> ,
Welcome {{$data['admin']->name}}

@component('mail::button', ['url' => url('/reset/password/'.$data['token'])])
Click here to reset your password
@endcomponent
or copy this link
<a href="{{url('reset/password/'.$data['token'])}}">{{url('/reset/password/'.$data['token'])}}</a>

Thanks<br>
@endcomponent
