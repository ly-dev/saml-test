@extends('managedfile::managedfile.layout')

@section('component-content')

<div class="page-header">
    <h1>Managedfile</h1>
</div>

@include('components.alert', [
    'class' => Session::get('alert-class'),
    'alertMessage' => Session::get('message')
])

@endsection

@push('component-styles')

@endpush

@push('component-scripts')

@endpush
