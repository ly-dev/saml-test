@extends('layouts.email')

@section('content')

<p>
    Welcome on board, {{ $name }}. Please click below link to confirm your email
    address:<br/>
    <a href="{{ $link }}" target="_blank">{{ $link }}</a>
</p>
@endsection
