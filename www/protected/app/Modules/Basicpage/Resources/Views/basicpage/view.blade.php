@extends('basicpage::basicpage.layout')

@section('component-content')
    @inject('bladeutil', 'App\Services\BladeUtilService')
    <?php
    $pageId = 'page-basicpage-view';
    ?>

    <div class="{{ $pageId }}">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('basicpage/process') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="id" id="id" class="form-control" value="{{ $basicpageModel->id or ''}}">

            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    @include('components.alert', [
                        'class' => Session::get('alert-class'),
                        'alertMessage' => Session::get('message')
                    ])

                    <div class="panel panel-default">
                        <div class="panel-body">

                            @include('components.section-header', [
                                'label' => (empty($basicpageModel->id) ? 'Create Basic Page' : ' Edit Basic Page'),
                            ])

                            <div class="col-md-10 col-md-offset-1">
                                @include('form.input', [
                                    'label' => 'Title',
                                    'id' => 'title',
                                    'type' => 'text',
                                    'value' => (null !== old('title') ? old('title') : $basicpageModel->title),
                                    'required' => true,
                                    'pageId' => $pageId,
                                    'tooltipId' => 'title'
                                ])
                            </div>


                            <div class="col-md-10 col-md-offset-1">
                                @include('form.input', [
                                    'label' => 'Slug',
                                    'id' => 'slug',
                                    'type' => 'text',
                                    'value' => (null !== old('slug') ? old('slug') : $basicpageModel->slug),
                                    'pageId' => $pageId,
                                    'tooltipId' => 'slug',
                                    'note' => 'This is the unique identifier for the page. Changing this could cause some pages not to load.'
                                ])
                            </div>

                            <div class="col-md-10 col-md-offset-1">
                                @include('form.textarea', [
                                    'label' => 'Body',
                                    'id' => 'body',
                                    'rows' => '5',
                                    'value' => (null !== old('body') ? old('body') : $basicpageModel->body),
                                    'pageId' => $pageId,
                                    'tooltipId' => 'body',
                                    'note' => '65535 characters maximum'
                                ])
                            </div>

                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    @include('components.button-link', [
                                        'url' => url('basicpage'),
                                        'class' => 'btn-default',
                                        'label' => 'Back',
                                        'iconClass' => 'glyphicon glyphicon-chevron-left'
                                    ])
                                    @include('components.button-submit', [
                                        'label' => 'Save',
                                        'iconClass' => 'glyphicon glyphicon-floppy-disk'
                                    ])
                                    @include('components.button-link', [
                                        'url' => url('basicpage/create'),
                                        'class' => 'btn-default pull-right form-add-another' . (empty($basicpageModel->id) ? '' : ' visible'),
                                        'label' => 'Add Another',
                                        'iconClass' => 'glyphicon glyphicon-plus'
                                    ])
                                </div>
                            </div>

                        </div><!-- panel body -->
                    </div><!-- panel -->

                </div><!-- col -->
            </div><!-- row -->
        </form>
    </div><!-- page id -->
@endsection

@push('component-styles')
@endpush

@push('component-scripts')
    <script src="<?php echo e(asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')); ?>"></script>
    <script>
        CKEDITOR.replace( 'body', {
            filebrowserImageBrowseUrl: '{{ url("/laravel-filemanager?type=Images") }}',
            //filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '{{ url("/laravel-filemanager?type=Files") }}',
            //filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        } );
    </script>
@endpush
