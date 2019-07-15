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
                'label' => 'Email Verification',
        ])

        <form action="{{ url('auth/email-verification') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="col-md-8 col-md-offset-2">
                <h2>We've sent you an email...</h2>
                <p>Please get the email at {{ $model->email }} and click the link in the email to complete your registration, and create your Crowd fund listing. If you haven't received your email in 1 hour, please check your spam folder, or contact us directly.</p>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('auth/resend-email-verification'),
                        'class' => 'btn-link btn-block',
                        'label' => 'Resend the verification email',
                    ])
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2">
                @include('form.submit', [
                    'label' => 'Continue'
                ])
            </div>

        </form>

    </div>
</div>

        </div>
    </div>
</div>
@endsection
