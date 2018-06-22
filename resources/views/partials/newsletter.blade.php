<section>

    {{ Form::open(['url' => 'newsletter', 'method' => 'post']) }}
    {{ Form::text('email', null, ['placeholder' => 'enter your email', 'class' => 'text_field']) }}
    <button type="submit">Subscribe</button>
    {{ Form::close() }}

</section>