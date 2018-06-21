<div class="container">

    @php

        if ($errors->any()) {
            $data = $errors;
            $class = 'alert-danger';
        } else {
            $data = $messages;
            $class = 'alert-success';
        }

    @endphp

    @foreach($data->all() as $message)

        <div class="alert alert-dismissible fade show mt-4 mb-4 {{ $class }}" role="alert">
            {{ $message }}
            <button type="button" class="close mt-2" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    @endforeach

</div>