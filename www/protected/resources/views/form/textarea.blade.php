<?php
// set errorId for array input, e.g. members.0.full_name
$errorId = (isset($errorId) ? $errorId : $id);
?>
<div class="form-group{{ empty($required) ? '' : ' required'}} {{ $errors->has($errorId) ? 'has-error' : '' }} {{ $class or ''}}">
    @if (isset($label))
        @include ('components.control-label', [
            'id' => $id,
            'class' => (isset($labelClass) ? $labelClass : null),
            'label' => $label,
            'pageId' => (isset($pageId) ? $pageId : null),
            'tooltipId' => (isset($tooltipId) ? $tooltipId : null),
            'note' => (isset($note) ? $note : null),
        ])
    @endif
    <textarea class="form-control {{ $controlClass or ''}}" rows="{{ $rows or 4 }}" id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder or ''}}"
    <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>>{{ $value or '' }}</textarea>
    @include ('components.input-error', [
        'id' => $errorId,
    ])
</div>