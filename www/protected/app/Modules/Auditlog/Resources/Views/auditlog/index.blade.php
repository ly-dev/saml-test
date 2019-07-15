@extends('auditlog::auditlog.layout') @section('component-content')
<div class="page-header">
    <h1>Audit Logs</h1>
</div>
<div class="app-table-wrapper">
    <table class="table table-striped table-hover" id="data-table"
        width="100%"
    >
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Severity</th>
                <th>Category</th>
                <th>Activity</th>
                <th>IP&nbsp;Address</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="page-header">
    <a class="btn btn-default" href="{{ url('/') }}"><i
        class="glyphicon glyphicon-chevron-left"
    ></i> Back</a>
</div>
@endsection @push('component-styles') @endpush
@push('component-scripts')
<script type="text/javascript">
	jQuery(document).ready(function(){

		var dataTable = $('#data-table').DataTable({
		    "processing": true,
		    "serverSide": true,
		    "order": [[ 0, "desc" ]],
            "searching": true,
            "responsive": true,
		    "ajax": "{{url('audit-log/grid')}}",
		    "columns": [
                {
                    "data": "updated_at"
                },
                {
                    "data": "severity"
                },
                {
                    "data": "category"
                },
                {
                	"orderable": false,
                    "data": "activity"
                },
                {
                    "data": "ip_address"
                },
                {
                    "data": "user"
                }
		    ]
		});
	});
</script>
@endpush
