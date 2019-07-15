@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

@include('components.alert', [
    'class' => 'alert-success',
    'alertMessage' => Session::get('status')
])

<div class="panel panel-default">
    <div class="panel-body">
        @include('components.section-header', [
            'label' => 'Reset Password',
        ])

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/password/email') }}">
            {{ csrf_field() }}

            <div class="col-md-6 col-md-offset-3">
            @include('form.input', [
                'label' => 'Email',
                'id' => 'email',
                'type' => 'email',
                'value' => old('email')
            ])
            </div>

            <div class="col-md-6 col-md-offset-3">
            @include('form.submit', [
                'label' => 'Send Password Reset Link'
            ])
            </div>
        </form>
    </div>
</div>

        </div>
    </div>
</div>
@endsection
