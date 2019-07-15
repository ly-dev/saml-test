<div class="checkbox {{ $class or 'app-checkbox'}}"  data-id="{{ $name }}">
    <label for="{{ $name . '_' . $optionValue }}" class="{{ $controlClass or ''}}">
        <input type="checkbox" id="{{ $name . '_' . $optionValue }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        value="{{ $optionValue }}" {{ $checked ? 'checked="checked"' : '' }} <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>/>
        <div class="app-checkbox-mark"></div>
        <div class="app-checkbox-text">
        @if (!empty($escaped) && isset($optionLabel))
        <?php echo $optionLabel ?>
        @else
        {{ $optionLabel or '' }}
        @endif
        </div>
    </label>
</div>
