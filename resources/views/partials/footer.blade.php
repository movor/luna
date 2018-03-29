<footer class="py-4">
    <div class="container">
        <div class="text-center">
            <img class="movor-icon" src="{{ asset('img/movor_icon.svg') }}">
        </div>
        <div class="text-center">
            <small class="text-muted">
                &copy; {{ env('APP_NAME') . ' ' . date('Y') }}
            </small>
        </div>
    </div>
    <a href="https://movor.io" target="_blank" class="movor-with-love">
        <small class="text-muted">
            <em>Made with <i class="fa fa-heart"></i> by</em>
        </small>
        <img src="{{ url('img/movor_logo.svg') }}">
    </a>
</footer>