<a class="btn {{ $class or 'btn-primary'}}" href="{{ $url or '#' }}" <?php echo (isset($attributes) ? $attributes :  '') ?> role="button">
    @if (isset($iconClass))
    <i class="{{ $iconClass }}" aria-hidden="true"></i>
    @endif
    {{ $label or '' }}
</a>