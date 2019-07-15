<div class="app-file-input" data-id="{{ $id }}">
    <button class="btn {{ $class or 'btn-primary'}}{{ isset($model) ? ' change' : ''}}">
        <i class="{{ $iconClass or 'glyphicon glyphicon-open' }}" aria-hidden="true"></i>
        @if (isset($model))
            @if (isset($buttonChangeLabel))
            <span>{{ $buttonChangeLabel or ''}}</span>
            @else
            <span>{{ $buttonLabel or ''}}</span>
            @endif
            <div class="file-name">{{ $model->file_name or ''}}</div>
        @else
            <span>{{ $buttonLabel or ''}}</span>
            <div class="file-name"></div>
        @endif
    </button>
    <input type="file" id="{{ $id }}" name="{{ $id }}" accept="{{ $accept or 'image/*' }}"
    <?php echo (isset($controlAttributes) ? $controlAttributes :  '') ?>
    onchange="(function(fileInput, $){ var filename = fileInput.files[0].name; $(fileInput).parent().find('.btn').addClass('change').find('.file-name').text(filename);})(this, jQuery)"/>
    @if (isset($model))
        <div class="actions">
            <a href="{{ $model->download_url }}" target="_blank"><i class="glyphicon glyphicon-download"></i></a>
        </div>
    @endif
</div>
