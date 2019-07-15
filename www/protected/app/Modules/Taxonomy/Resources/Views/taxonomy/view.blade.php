@extends('taxonomy::taxonomy.layout')

@section('component-content')
@inject('bladeutil', 'App\Services\BladeUtilService')

<div class="row">
    <div class="col-md-10 col-md-offset-1">

@include('components.alert', [
    'class' => Session::get('alert-class'),
    'alertMessage' => Session::get('message')
])

<div class="panel panel-default">
    <div class="panel-body">

        @include('components.section-header', [
            'label' => (empty($model->id) ? 'Create ' : 'Edit ' ) . $title,
        ])

        <form class="form-horizontal" role="form" method="POST" action="{{ url('taxonomy/process/' . $term) }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="id" id="id" class="form-control" value="{{ $model->id or ''}}">

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Name',
                'id' => 'name',
                'type' => 'text',
                'value' => (null !== old('name') ? old('name') : $model->name),
                'required' => true,
            ])
            </div>

            @if ($term == 'link-type')
            <div class="col-md-8 col-md-offset-2">
            @include('form.select', [
                'label' => 'Status',
                'id' => 'status',
                'value' => (null !== old('status') ? old('status') : $model->status),
                'options' => App\Modules\Taxonomy\Models\LinkType::$STATUS_LABELS,
                'required' => true,
            ])
            </div>
            @endif

            @if ($term == 'link-type')
            <div class="col-md-8 col-md-offset-2">
            @include('form.file', [
                'label' => 'Icon Image',
                'id' => 'icon_fid',
                'buttonLabel' => 'Upload Icon Image',
                'buttonChangeLabel' => 'Change Icon Image',
                'model' => (isset($model) && isset($model->iconFile) ? $model->iconFile : null),
                'note' => 'Please upload a svg, Maximum size is 200KB',
                'accept' => 'image/svg+xml'
            ])
            </div>
            @if (isset($model) && isset($model->iconFile))
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ $model->iconFile->asset_url }}" style="width:100%;margin: 10px"/>
            </div>
            @endif
            @endif

            @if ($term == 'marketing-extra-type')
            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Price <i class="fa fa-btc"></i>',
                'labelEscape' => true,
                'id' => 'price_btc',
                'type' => 'text',
                'value' => (null !== old('price_btc') ? old('price_btc') : $bladeutil->btcFormat($model->price_btc)),
                'controlAttributes' => 'min="0"',
                'required' => true,
            ])
            </div>
            @endif

            @if ($term == 'marketing-extra-type')
            <div class="col-md-8 col-md-offset-2">
            @include('form.textarea', [
                'label' => 'Description',
                'id' => 'description',
                'type' => 'text',
                'value' => (null !== old('description') ? old('description') : $model->description),
            ])
            </div>
            @endif

            @if ($term == 'variable')
            <div class="col-md-8 col-md-offset-2">
            @include('form.textarea', [
                'label' => 'Value',
                'id' => 'value',
                'type' => 'text',
                'value' => (null !== old('value') ? old('value') : $model->value),
            ])
            </div>
            @endif

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Sort Order',
                'id' => 'sort_order',
                'type' => 'number',
                'value' => (null !== old('sort_order') ? old('sort_order') : $model->sort_order),
                'controlAttributes' => 'min="0"',
                'required' => true,
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('taxonomy/' . $term),
                        'class' => 'btn-default',
                        'label' => 'Back',
                        'iconClass' => 'glyphicon glyphicon-chevron-left'
                    ])
                    @include('components.button-submit', [
                        'label' => 'Save',
                        'iconClass' => 'glyphicon glyphicon-floppy-disk'
                    ])
                    @include('components.button-link', [
                        'url' => url('taxonomy/view/' . $term . '/create'),
                        'class' => 'btn-default pull-right form-add-another' . (empty($model->id) ? '' : ' visible'),
                        'label' => 'Add Another',
                        'iconClass' => 'glyphicon glyphicon-plus'
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
