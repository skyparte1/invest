@if (session('status'))
    <div class="container global-feedback">
        <div class="alert alert-success mb-0" role="status">{{ session('status') }}</div>
    </div>
@endif
