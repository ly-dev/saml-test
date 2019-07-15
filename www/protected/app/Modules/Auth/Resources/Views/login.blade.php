@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12">
            @include('components.alert', [
                'class' => Session::get('alert-class'),
                'alertMessage' => Session::get('message')
            ])
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">

<div class="panel panel-default">
    <div class="panel-body">

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
            {{ csrf_field() }}

            <div class="col-md-12">
            @include('components.section-header', [
                    'label' => 'Login',
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Email',
                'id' => 'email',
                'type' => 'email',
                'value' => old('email'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Password',
                'id' => 'password',
                'type' => 'password',
                'value' => old('password'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.submit', [
                'label' => 'Continue'
            ])
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('auth/password/reset'),
                        'class' => 'btn-link btn-block',
                        'label' => 'Forgot Your Password?',
                    ])
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('samltest/login'),
                        'class' => 'btn-link btn-block',
                        'label' => 'SAML Test IDP Login',
                    ])
                </div>
            </div>
        </form>

    </div>
</div>

        </div>

        <div class="col-md-6">

<div class="panel panel-default">
    <div class="panel-body">

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
            {{ csrf_field() }}

            <div class="col-md-12">
            @include('components.section-header', [
                    'label' => 'Register',
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Your name',
                'id' => 'your_name',
                'type' => 'text',
                'value' => old('your_name'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Email',
                'id' => 'your_email',
                'type' => 'email',
                'value' => old('your_email'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Create a password',
                'id' => 'new_password',
                'type' => 'password',
                'value' => old('new_password'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.input', [
                'label' => 'Confirm the password',
                'id' => 'new_password_confirmation',
                'type' => 'password',
                'value' => old('new_password_confirmation'),
                'required' => true,
            ])
            </div>

            <div class="col-md-12">
            @include('form.checkbox-group', [
                'name' => 'agree',
                'values' => old('agree'),
                'options' => [[
                    'optionValue' => '1',
                    'optionLabel' => 'Agree with <a href="https://www.coinschedule.com/terms.html" target="_blank">terms of use</a> and <a href="https://www.coinschedule.com/privacypolicy.html" target="_blank">privacy policy</a>',
                    'escaped' => true,
                ]]
            ])
            </div>

            <div class="col-md-12">
            @include('form.submit', [
                'label' => 'Submit'
            ])
            </div>

        </form>

    </div>
</div>

        </div>
    </div>
</div>
@endsection
