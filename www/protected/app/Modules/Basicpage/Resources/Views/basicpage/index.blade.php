@extends('basicpage::basicpage.layout')

@section('component-content')
    <?php
    $pageId = 'page-basicpage-index';
    ?>

    <div class="{{ $pageId }}">

        <div class="page-header">
            <h1>Basic Pages</h1>
        </div>

        <div class="app-table-wrapper">
            <table class="table table-striped table-hover" id="data-table" width="100%">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Updated At</th>
                    <th style="width:50px"></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="page-footer">
            @include('components.button-link', [
                'url' => url('basicpage/create'),
                'label' => 'Add Basic Page',
                'iconClass' => 'glyphicon glyphicon-plus'
            ])
        </div>

    </div><!-- page id -->
@endsection

@push('component-styles')

@endpush

@push('component-scripts')
    <script type="text/javascript">
        var dataTable = null;
        jQuery(document).ready(function(){

            dataTable = $('#data-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": true,
                "order": [[ 2, "desc" ]],
                "searching": true,
                "responsive": true,
                "ajax": "{{url('basicpage/grid')}}",
                "columns": [
                    {
                        "data": "title"
                    },
                    {
                        "data": "slug"
                    },
                    {
                        "data": "updated_at"
                    },
                    {
                        "orderable": false,
                        "render": function render( data, type, row, meta ) {
                            return '<a href="' + "{{url('basicpage/view')}}" + '/' + row.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' +
                                '&nbsp;<a href="' + "{{url('page')}}" + '/' + row.id + '" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>' +
                                '&nbsp;<a href="#" onclick="return doDelete(' + row.id +')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                        }
                    }
                ]
            });
        });

        function doDelete(id) {
            var options = {
                url: "{{url('basicpage/delete')}}" + '/' + id
            };

            return myappUtil.dataTableDoRowDelete(dataTable, options);
        }
    </script>
@endpush
