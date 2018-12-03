@php

    $html = '';
    $model = $entry;

    /** @var \Illuminate\Database\Eloquent\Model $relation */
    $relation = $model->{$column['relation']};

    if ($relation) {
        $value = is_callable($column['attribute'])
        ? $column['attribute']($relation)
        : $relation->{$column['attribute']};

        $html = '<a href="%s/admin/%s/%s/edit" target="_blank">%s</a>';
        $html = sprintf($html, env('APP_URL'), $column['relationRoute'], $relation->getKey(), $value);
    }

@endphp

<td>{!! $html !!}</td>