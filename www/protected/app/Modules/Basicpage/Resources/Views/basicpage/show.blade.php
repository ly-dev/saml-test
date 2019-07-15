@extends('layouts.app')

@section('content')
@inject('bladeutil', 'App\Services\BladeUtilService')
<div class="container">
    <div class="basic-page" data-page-id="{{ $model->id }}" data-page-slug="{{ $model->slug }}">
        <h1>{{ $model->title }}</h1>
        <?php echo $model->body; ?>
    </div>
</div>
@endsection
