@if (session('status'))
    <div class="container global-feedback">
        <div class="alert alert-success mb-0" role="status">{{ session('status') }}</div>
    </div>
@endif
@if (session('error'))
    <div class="container global-feedback"><div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div></div>
@endif
