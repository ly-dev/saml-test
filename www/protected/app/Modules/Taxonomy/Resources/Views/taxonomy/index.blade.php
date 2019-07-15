@extends('taxonomy::taxonomy.layout')

@section('component-content')

@include('taxonomy::taxonomy.header')

<div class="app-table-wrapper" style="margin-top: 60px">
    <table class="table table-striped table-hover" id="data-table" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                @if (!empty($meta['columnNames']))
                @foreach ($meta['columnNames'] as $columnName)
                <th><?php echo $columnName; ?></th>
                @endforeach
                @endif
                <th>Sort Order</th>
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
        'url' => url('taxonomy/view/'. $term . '/create'),
        'label' => 'Add ' . $modelName,
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
        "ajax": "{{url('taxonomy/grid/' . $term)}}",
        "columns": [
            {
                    "data": "id",
            },
            {
                    "data": "name",
            },
            @if (!empty($meta['listFields']))
            @foreach ($meta['listFields'] as $listField)
            {
                <?php echo $listField; ?>
            },
            @endforeach
            @endif
            {
                    "data": "sort_order",
            },
            {
                    "data": "updated_at"
            },
            {
                    "orderable": false,
                    "render": function render( data, type, row, meta ) {
                    return '<a href="' + "{{url('taxonomy/view/' . $term)}}" + '/' + row.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' +
                               '&nbsp;<a href="#" onclick="return doDelete(' + row.id +')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
            }
        ]
    });
});

function doDelete(id) {
    var options = {
        url: "{{url('taxonomy/delete/' . $term)}}" + '/' + id
    };

    return myappUtil.dataTableDoRowDelete(dataTable, options);
}
</script>
@endpush
