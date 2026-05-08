@if (session('status'))
    <div class="flash">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="error-box">
        <strong>Please review the form:</strong>
        <div style="margin-top:8px;">{{ $errors->first() }}</div>
    </div>
@endif
