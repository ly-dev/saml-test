@extends('auth::user.layout')

@section('component-content')
@inject('bladeutil', 'App\Services\BladeUtilService')


<div class="row">
    <div class="col-md-10  col-md-offset-1 col-lg-8 col-lg-offset-2">

    @include('components.alert', [
        'class' => Session::get('alert-class'),
        'alertMessage' => Session::get('message')
    ])

<div class="panel panel-default">
    <div class="panel-body">

        @include('components.section-header', [
            'label' => 'Edit User',
        ])

        <form class="form-horizontal" role="form" method="POST" action="{{ url('auth/user/process') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $model->id or ''}}">

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'Name',
                'id' => 'name',
                'type' => 'text',
                'value' => (null !== old('name') ? old('name') : $model->name),
                'required' => true,
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.input', [
                'label' => 'E-mail',
                'id' => 'email',
                'type' => 'text',
                'value' => (null !== old('email') ? old('email') : $model->email),
                'required' => true,
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.select', [
                'label' => 'Status',
                'id' => 'status',
                'value' => (null !== old('status') ? old('status') : $model->status),
                'options' => App\Modules\Auth\Models\User::$STATUS_LABELS,
                'required' => true,
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
            @include('form.select', [
                'label' => 'Roles',
                'id' => 'roles',
                'value' => (null !== old('roles') ? old('roles') : $bladeutil->columnOfCollection($model->roles, 'id')),
                'options' => $bladeutil->collectionToOptions(Spatie\Permission\Models\Role::all(), 'id', 'name'),
                'isMultiple' => true,
                'required' => true,
            ])
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    @include('components.button-link', [
                        'url' => url('auth/user'),
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

    </div><!-- panel body -->
</div><!-- panel -->

    </div><!-- col -->
</div><!-- row -->
@endsection

@push('component-styles')
@endpush

@push('component-scripts')
<script type="text/javascript">
    jQuery(document).ready(function(){
        $('select[name="roles[]"]').select2({
        });
    });
</script>
@endpush
