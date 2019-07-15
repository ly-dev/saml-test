<div class="page-header">
    <ul class="nav nav-tabs">
    @foreach ([
        'variable' => [
            'label' => 'Variables',
            'uri' => 'taxonomy/variable',
        ],
    ] as $key=>$value)
        <li role="presentation" class="{{ ($term == $key ? 'active' : '') }}">
            <a href="{{url($value['uri'])}}">{{ $value['label'] }}</a>
        </li>
    @endforeach
    </ul>
</div>