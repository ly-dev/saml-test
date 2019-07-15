@extends('layouts.email')

@section('content')
<div>
    {{ $result['status'] }}
</div>

<div>
    {{ $result['message'] or '' }}
</div>

<script type="text/javascript">
(function() {
    if (window.opener) {
        window.opener.postMessage(window.location.href, '*');
        window.close();
    }
})();
</script>
@endsection
