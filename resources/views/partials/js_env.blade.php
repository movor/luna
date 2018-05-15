@php

    $jsEnv = [];
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'JS_') === 0) {
            $jsEnv[substr($key, 3)] = $value;
        }
    }

@endphp

<script>

    window.env = {!! json_encode($jsEnv, JSON_UNESCAPED_SLASHES) !!};

</script>