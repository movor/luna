{{-- Entry will not be set if we're creating model, only on edit --}}
@if (isset($entry))

    @php

        $model = $entry;
        $method = $field['method_name'];

        $url = url($model->$method());

    @endphp

    <div @include('crud::inc.field_wrapper_attributes')>
        <p>
            View On Site: &nbsp<a href="{{ $url }}" target="_blank">{{ $url }}</a>
        </p>
    </div>

@endif