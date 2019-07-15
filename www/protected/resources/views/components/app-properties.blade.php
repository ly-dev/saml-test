<script type="text/javascript">
// myappProperites object to exchange settings with php
var myappProperties = {
    baseUrl: '{{ url("/") }}',
    @if (Auth::guest())
    isGuest: 1
    @else
    isAdmin: {{ Auth::user()->isAdmin() ? 1 : 0 }},
    isModerator: {{ Auth::user()->isModerator() ? 1 : 0 }},
    isGuest: 0
    @endif
};
</script>