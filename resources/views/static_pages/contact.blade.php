@extends('layouts.default')

@include('static_pages.partials.seo')

@section('content')

    @php
        // Validation
        $emailInputClass = $messageInputClass = '';

        if ($errors->isNotEmpty()) {
            $emailInputClass = $errors->has('email') ? 'is-invalid' : 'is-valid';
            $messageInputClass = $errors->has('message') ? 'is-invalid' : 'is-valid';
        }

    @endphp

    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="">Contact Us</h1>
                <p class="mb-4 pb-4">
                    Feel free to contact us any time using web form or email! Contact form is below, so don't hesitate,
                    contact us about everything you want to know. If form some reason you find easier to send us a mail
                    directly you can click <a href="mailto:{{ env('APP_CONTACT_EMAIL') }}">here</a> or use this email
                    address: <span class="text-primary">{{ env('APP_CONTACT_EMAIL') }}</span>
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
                <div class="form-group row mb-md-5">
                    <label for="message" class="col-sm-3 col-form-label form-control-lg">Message</label>
                    <div class="col-sm-9">

                        {{ Form::textarea('message', null, ['class' => "form-control form-control-lg $messageInputClass", 'rows' => 5]) }}

                        @if ($messageInputClass)

                            <div class="invalid-feedback">{{ $errors->first('message') }}</div>

                        @else

                            <div class="valid-feedback">Looks good</div>

                        @endif

                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-primary float-sm-right mb-5">Submit</button>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection