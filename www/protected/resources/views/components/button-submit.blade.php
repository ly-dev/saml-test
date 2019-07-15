@include('components.button', [
    'type' => 'submit',
    'label' => (isset($label) ? $label : 'Submit'),
    'class' => (isset($class) ? $class : 'btn-primary'),
    'attributes' => (isset($attributes) ? $attributes : null),
    'iconClass' => (isset($iconClass) ? $iconClass : null),
])
