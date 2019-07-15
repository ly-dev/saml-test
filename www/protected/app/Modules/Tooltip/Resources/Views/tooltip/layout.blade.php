@extends('layouts.app')

@section('content')
    <div class="container">
        @yield('component-content')
    </div>
@endsection

@push('module-styles')
	@stack('component-styles')
@endpush

@push('module-scripts')
    @stack('component-scripts')
@endpush