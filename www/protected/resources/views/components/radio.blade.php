<div class="radio {{ $class or 'app-radio'}}"  data-id="{{ $name }}">
    <label for="{{ $name . '_' . $optionValue }}" class="{{ $controlClass or ''}}">
        <input type="radio" id="{{ $name . '_' . $optionValue }}" name="{{ $name }}"
        value="{{ $optionValue }}" {{ $checked ? 'checked="checked"' : '' }} <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>/>
        <span class="app-radio-mark"></span>
        <div class="app-radio-text">
        @if (!empty($escaped) && isset($optionLabel))
        <?php echo $optionLabel ?>
        @else
        {{ $optionLabel or '' }}
        @endif
        </div>
    </label>
</div>
