@if ($errors->has($id))
<span class="help-block" data-id="{{ $id }}" title="{{ $errors->first($id) }}"><strong>{{ $errors->first($id) }}</strong></span>
@endif
