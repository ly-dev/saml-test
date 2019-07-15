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
                'label' => 'Change Password',
        ])

        <form action="{{ url('auth/password/save') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Current Password',
                'id' => 'old_password',
                'type' => 'password',
                'value' => old('old_password')
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'New Password',
                'id' => 'password',
                'type' => 'password',
                'value' => old('password')
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'New Password Confirmation',
                'id' => 'password_confirmation',
                'type' => 'password',
                'value' => old('password_confirmation')
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('/'),
                        'class' => 'btn-default',
                        'label' => 'Back',
                        'iconClass' => 'glyphicon glyphicon-chevron-left'
                    ])
                    @include('components.button-submit', [
                        'label' => 'Save',
                        'iconClass' => 'glyphicon glyphicon-floppy-disk'
                    ])
                </div>
            </div>
        </form>

    </div>
</div>

        </div>
    </div>
</div>
@endsection
