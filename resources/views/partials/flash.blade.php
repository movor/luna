<div class="container mb-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert">

        {{ Session::get('message') }}

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>