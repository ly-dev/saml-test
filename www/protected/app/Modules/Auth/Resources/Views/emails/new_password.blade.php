@extends('layouts.email')

@section('content')
<p>Here are your new log-in details:</p>
<ul>
	<li><strong>Email: {{$params['email']}}</strong></li>
	<li><strong>Password: <a href="{{ $params['passwordResetLink'] }}" target="_blank">reset password link</a></strong></li>
</ul>

<p>It is highly recommended to change the password regularly. You may always get a new password reset link sent to your email box by clicking the <a href="{{ url('auth/password/reset') }}" target="_blank">Forgot Your Password?</a> link on login form screen.</p>
@endsection