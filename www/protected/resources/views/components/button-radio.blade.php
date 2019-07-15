<div class="radio app-button-radio {{ $class or ''}}">
    <input type="radio" id="{{ $name . '_' . $optionValue }}" name="{{ $name }}"
    value="{{ $optionValue }}" {{ $checked ? 'checked="checked"' : '' }} <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>/>
    <label for="{{ $name . '_' . $optionValue }}" class="btn {{ $controlClass or 'btn-default btn-block'}}">
        {{ $optionLabel or ''}}
    </label>
</div>
