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
    <select type="text" class="form-control {{ $controlClass or ''}}" id="{{ $id }}" name="{{ $id }}{{ empty($isMultiple) ? '' : '[]' }}" placeholder="{{ $placeholder or ''}}" {{ empty($isMultiple) ? '' : 'multiple' }}
    <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>>
        @if (isset($options))
            @foreach ($options as $optionId=>$optionLabel)
            <option value="{{ $optionId }}"{{ isset($value) && (empty($isMultiple) ? ($value == $optionId) : (in_array($optionId, $value))) ? ' selected' : '' }}>{{$optionLabel}}</option>
            @endforeach
        @endif
    </select>
    @include ('components.input-error', [
        'id' => $errorId,
    ])
</div>