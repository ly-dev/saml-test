@extends('layouts.app')

@section('content')
<div class="container home">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Welcome</h1>

            @include('components.alert', [
                'class' => Session::get('alert-class'),
                'alertMessage' => Session::get('message')
            ])

            @if (Auth::guest())
            <p>Please login to get started.</p>
            @else
            <p>Please choose the menu item to execute tasks.</p>
            @endif

            @if (!empty($apkFiles))
            <p style="margin-top: 60px">
                Download and try Android App here:
            </p>
            <ul>
                @foreach ($apkFiles as $apkFile)
                <li><a href="{{ $apkFile['url'] }}" target="_blank">{{ $apkFile['basename'] }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $apkFile['size'] }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>
@endsection
