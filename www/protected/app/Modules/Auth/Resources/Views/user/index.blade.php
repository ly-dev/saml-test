@extends('auth::user.layout') @section('component-content')

<div class="page-header">
    <h1>Users</h1>
</div>

<div class="app-table-wrapper">
    <table class="table table-striped table-hover" id="data-table" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Roles</th>
                <th>Updated At</th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@endsection @push('component-styles') @endpush

@push('component-scripts')
<script type="text/javascript">
    jQuery(document).ready(function(){

        var dataTable = $('#data-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "responsive": true,
            "ajax": "{{url('auth/user/grid')}}",
            "columns": [
                {
                    "data": "name",
                },
                {
                    "data": "email"
                },
                {
                    "data": "status",
                },
                {
                    "orderable": false,
                    "data": "roles",
                },
                {
                    "data": "updated_at"
                },
                {
                    "orderable": false,
                    "render": function render( data, type, row, meta ) {
                        return '<a href="' + "{{url('auth/user/view')}}" + '/' + row.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    }

                }
            ]
        });
    });
</script>
@endpush
