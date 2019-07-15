@extends('tooltip::tooltip.layout')

@section('component-content')

<div class="page-header">
    <h1>Tooltips</h1>
</div>

<div class="app-table-wrapper">
    <table class="table table-striped table-hover" id="data-table" width="100%">
        <thead>
            <tr>
                <th>Page ID</th>
                <th>Tooltip ID</th>
                <th>Title</th>
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
        'url' => url('tooltip/create'),
        'label' => 'Add Tooltip',
        'iconClass' => 'glyphicon glyphicon-plus'
    ])
</div>

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
        "order": [[ 0, "asc" ]],
        "searching": true,
        "responsive": true,
        "ajax": "{{url('tooltip/grid')}}",
        "columns": [
            {
                    "data": "page_id",
            },
            {
                    "data": "tooltip_id",
            },
            {
                    "render": function render( data, type, row, meta ) {
                        return row.title + '&nbsp;&nbsp;<i class="app-tooltip" aria-hidden="true" data-pageid="' + row.page_id + '" data-tooltipid="' + row.tooltip_id + '"></i>';
                    }
            },
            {
                    "data": "updated_at"
            },
            {
                    "orderable": false,
                    "render": function render( data, type, row, meta ) {
                    return '<a href="' + "{{url('tooltip/view')}}" + '/' + row.page_id + '/' + row.tooltip_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' +
                               '&nbsp;<a href="#" onclick="return doDelete(' + "'" + row.page_id + "','" + row.tooltip_id + "'" +')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
            }
        ],
        "initComplete": function(settings, json) {
            $('.app-tooltip').each(function( index ) {
                myappUtil.loadTooltip(this);
            });
        }
    });
});

function doDelete(page_id, tooltip_id) {
    var options = {
        url: "{{url('tooltip/delete')}}" + '/' + page_id + '/' + tooltip_id
    };

    return myappUtil.dataTableDoRowDelete(dataTable, options);
}
</script>
@endpush
