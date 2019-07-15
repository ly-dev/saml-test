<button type="{{ $type or 'button' }}" class="btn {{ $class or 'btn-default'}}" <?php echo (isset($attributes) ? $attributes :  '') ?>>
    @if (isset($iconClass))
    <i class="{{ $iconClass }}" aria-hidden="true"></i>
    @endif
    {{ $label or '' }}
</button>