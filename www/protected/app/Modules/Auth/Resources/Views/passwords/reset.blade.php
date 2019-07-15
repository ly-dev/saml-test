@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

@include('components.alert', [
    'class' => Session::get('alert-class'),
    'alertMessage' => Session::get('message')
])

<div class="panel panel-default">
    <div class="panel-body">

        @include('components.section-header', [
                'label' => 'Reset Password',
        ])

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/password/reset') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="col-md-6 col-md-offset-3">
            @include('form.input', [
                'label' => 'Email',
                'id' => 'email',
                'type' => 'email',
                'value' => (isset($email) ?  $email : old('email'))
            ])
            </div>

            <div class="col-md-6 col-md-offset-3">
            @include('form.input', [
                'label' => 'Password',
                'id' => 'password',
                'type' => 'password'
            ])
            </div>

            <div class="col-md-6 col-md-offset-3">
            @include('form.input', [
                'label' => 'Password Confirmation',
                'id' => 'password_confirmation',
                'type' => 'password'
            ])
            </div>

            <div class="col-md-6 col-md-offset-3">
            @include('form.submit', [
                'label' => 'Reset Password'
            ])
            </div>
        </form>
    </div>
</div>

        </div>
    </div>
</div>
@endsection
