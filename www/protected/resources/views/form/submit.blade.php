<div class="form-group {{ $class or ''}}">
    @if (!empty($isInput))
    <input type="submit" class="btn {{ $controlClass or 'btn-primary btn-block btn-lg' }}" id="{{ $id }}" name="{{ $id }}"
     value="{{ $label or 'Submit' }}" />
    @else
    @include('components.button-submit', [
        'label' => (isset($label) ?  $label : null),
        'class' => (isset($controlClass) ? $controlClass : 'btn-primary btn-block'),
        'iconClass' => (isset($iconClass) ?  $iconClass : null)
    ])
    @endif
</div>