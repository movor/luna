@php

    $model = $entry;
    $method = $column['method_name'];

    $url = $model->$method();

@endphp

<td>
    <a class="fa fa-link" href="{{ $url }}" target="_blank"></a>
</td>