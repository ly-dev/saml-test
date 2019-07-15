<?php
// set errorId for array input, e.g. members.0.full_name
$errorId = (isset($errorId) ? $errorId : $name);
?>
<div class="form-group{{ empty($required) ? '' : ' required'}} {{ $errors->has($errorId) ? 'has-error' : '' }} {{ $class or ''}}">
    @if (isset($label))
        @include ('components.control-label', [
            'id' => '',
            'class' => (isset($labelClass) ? $labelClass : null),
            'label' => $label,
            'pageId' => (isset($pageId) ? $pageId : null),
            'tooltipId' => (isset($tooltipId) ? $tooltipId : null),
            'note' => (isset($note) ? $note : null),
        ])
    @endif
    @foreach ($options as $option)
        <?php
            $value = (isset($value) ? $value : null);
            $optionValue = (isset($option['optionValue']) ? $option['optionValue'] : null);
            $checked = ($optionValue == $value);
        ?>
        @include ('components.radio', [
            'name' => $name,
            'optionValue' => $optionValue,
            'optionLabel' => (isset($option['optionLabel']) ? $option['optionLabel'] : null),
            'checked' => $checked,
            'escaped' => (isset($option['escaped']) ? $option['escaped'] : null),
            'class' => (isset($option['class']) ? $option['class'] : null),
            'controlClass' => (isset($option['controlClass']) ? $option['controlClass'] : null),
            'controlAttributes' => (isset($option['controlAttributes']) ? $option['controlAttributes'] : null),
        ])
    @endforeach
    @include ('components.input-error', [
        'id' => $errorId,
    ])
</div>