<label for="{{ $id }}" class="control-label {{ $class or 'app-control-label'}}">
    @if (isset($iconPath))
        @include('components.svg', [
            'path' => $iconPath,
            'class' => 'label-icon',
            'width' => '20',
            'height' => '20'
        ])
    @endif
    <span class="label-text">
        @if (empty($labelEscape))
            {{ $label }}
        @else
            <?php echo $label; ?>
        @endif
    </span>
    @include ('components.tooltip', [
        'pageId' => (isset($pageId) ? $pageId : null),
        'tooltipId' => (isset($tooltipId) ? $tooltipId : null)
    ])
    @if (isset($note))
    <span class="note">{{ $note }}</span>
    @endif
</label>
