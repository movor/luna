@php

    $model = $entry;

    $data = explode('.', $field['name']);
    $relatedModel = $data[0];
    $relatedModelField = $data[1];

    $field['value'] = $model->$relatedModel->$relatedModelField;

@endphp

<!-- Text input -->
<div @include('crud::inc.field_wrapper_attributes')>

    <label>{!! $field['label'] !!}</label>

    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix']))

        <div class="input-group"> @endif

            @if(isset($field['prefix']))

                <div class="input-group-addon">{!! $field['prefix'] !!}</div>

            @endif

            <input
                    type="text"
                    name="{{ $field['name'] }}"
                    value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                    @include('crud::inc.field_attributes')
            >

            @if(isset($field['suffix']))

                <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif

            @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- Hint --}}
    @if (isset($field['hint']))

        <p class="help-block">{!! $field['hint'] !!}</p>

    @endif

</div>