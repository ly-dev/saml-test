@if (!empty($alertMessage))
<div class="alert {{ $class or 'alert-info' }} alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    @if (is_array($alertMessage))
    <ul><li><?php echo implode('</li><li>', $alertMessage); ?></li></ul>
    @else
    <span>{{ $alertMessage }}</span>
    @endif
</div>
@endif
