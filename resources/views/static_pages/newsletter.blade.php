@extends('layouts.default')

@section('content')

    @php

        // Validation
        $emailInputClass = '';

        if ($errors->isNotEmpty()) {
            $emailInputClass = $errors->has('email') ? 'is-invalid' : 'is-valid';
        }

    @endphp

    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>Newsletter</h1>
                <p class="mb-4 pb-4">
                    Sign up for newsletter
                </p>

                {{ Form::open() }}

                <div class="form-group row mb-md-5">
                    <label for="email" class="col-sm-3 col-form-label form-control-lg">Email</label>
                    <div class="col-sm-9">

                        {{ Form::text('email', null, ['class' => "form-control form-control-lg $emailInputClass"]) }}

                        @if ($emailInputClass)

                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>

                        @else

                            <div class="valid-feedback">Looks good</div>

                        @endif

                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-outline-primary float-sm-right mb-5">
                    Submit
                </button>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection