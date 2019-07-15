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
            'labelEscape' => (isset($labelEscape) ? $labelEscape : null),
            'pageId' => (isset($pageId) ? $pageId : null),
            'tooltipId' => (isset($tooltipId) ? $tooltipId : null),
            'note' => (isset($note) ? $note : null),
            'iconPath' => (isset($iconPath) ? $iconPath : null),
        ])
    @endif
    <input type="{{ $type }}" class="form-control {{ $controlClass or ''}}" id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder or ''}}"
    value="{{ $value or '' }}" <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>/>
    @include ('components.input-error', [
        'id' => $errorId,
    ])
</div>