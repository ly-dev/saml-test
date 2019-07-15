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
    @include ('components.file-input', [
        'id' => $id,
        'model' => (isset($model) ? $model : null),
        'class' => (isset($controlClass) ? $controlClass : null),
        'buttonLabel' => (isset($buttonLabel) ? $buttonLabel : null),
        'buttonChangeLabel' => (isset($buttonChangeLabel) ? $buttonChangeLabel : null),
        'accept' => (isset($accept) ? $accept : null),
        'controlAttributes' =>(isset($controlAttributes) ? $controlAttributes : null),
    ])
    @include ('components.input-error', [
        'id' => $errorId,
    ])
</div>