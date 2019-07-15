@extends('tooltip::tooltip.layout')

@section('component-content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">

@include('components.alert', [
    'class' => Session::get('alert-class'),
    'alertMessage' => Session::get('message')
])

<div class="panel panel-default">
    <div class="panel-body">

        @include('components.section-header', [
            'label' => (empty($model->page_id) && empty($model->tooltip_id) ? 'Create ' : 'Edit ' ) . 'Tooltip',
        ])

        <form class="form-horizontal" role="form" method="POST" action="{{ url('tooltip/process') }}">
            {{ csrf_field() }}

            <input type="hidden" name="id" id="id" class="form-control" value="{{ $model->id or ''}}">

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Page ID',
                'id' => 'page_id',
                'type' => 'text',
                'value' => (null !== old('page_id') ? old('page_id') : $model->page_id),
                'placeholder' => 'Page ID',
                'pageId' => 'tooltip_view',
                'tooltipId' => 'page_id'
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Tooltip ID',
                'id' => 'tooltip_id',
                'type' => 'text',
                'value' => (null !== old('tooltip_id') ? old('tooltip_id') : $model->tooltip_id),
                'placeholder' => 'Tooltip ID',
                'pageId' => 'tooltip_view',
                'tooltipId' => 'tooltip_id'
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Tooltip Title',
                'id' => 'title',
                'type' => 'text',
                'value' => (null !== old('title') ? old('title') : $model->title),
                'placeholder' => 'Tooltip Title',
                'pageId' => 'tooltip_view',
                'tooltipId' => 'title'
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.textarea', [
                'label' => 'Tooltip Content',
                'id' => 'content',
                'rows' => '5',
                'value' => (null !== old('content') ? old('content') : $model->description),
                'placeholder' => 'Tooltip Content',
                'pageId' => 'tooltip_view',
                'tooltipId' => 'content'
            ])
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('tooltip'),
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
@endsection

@push('component-styles')
@endpush

@push('component-scripts')
@endpush
