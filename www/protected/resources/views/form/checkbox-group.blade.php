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
    <?php
        $multiple = (isset($multiple) ? $multiple : ((count($options) > 1) && !isset($options[0]['name'])));
    ?>
    @foreach ($options as $option)
        <?php
            $value = (isset($option['value']) ? $option['value'] : (isset($values) ? $values : []));
            $optionValue = (isset($option['optionValue']) ? $option['optionValue'] : null);
            $checked = (is_array($value) ? (in_array($optionValue, $value)) : ($optionValue == $value));
        ?>
        @include ('components.checkbox', [
            'multiple' => $multiple,
            'name' => (isset($option['name']) ? $option['name'] : $name),
            'optionValue' => $optionValue,
            'optionLabel' => (isset($option['optionLabel']) ? $option['optionLabel'] : null),
            'checked' => (isset($option['checked']) ? $option['checked'] : $checked),
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