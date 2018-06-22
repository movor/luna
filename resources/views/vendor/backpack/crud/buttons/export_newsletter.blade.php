@if ($crud->hasAccess('create'))

    <a href="{{ url($crud->route . '/export') }}" class="btn btn-primary ladda-button" data-style="zoom-in">
        <span class="ladda-label">
            <i class="fa fa-at"></i> Export emails to CSV
        </span>
    </a>

@endif