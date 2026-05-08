<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose a New Password</title>
    <style>
        body { margin:0; font-family:"Segoe UI",sans-serif; background:linear-gradient(180deg,#08111d 0%,#05070b 24%); color:#f4f7fb; }
        .wrap { min-height:100vh; display:grid; place-items:center; padding:20px; }
        .card { width:min(480px,100%); background:linear-gradient(180deg, rgba(19, 23, 31, 0.96), rgba(12, 15, 22, 0.96)); border:1px solid rgba(115,132,165,.2); border-radius:24px; padding:28px; box-shadow:0 18px 40px rgba(0,0,0,.28); }
        .field { display:grid; gap:8px; margin-bottom:14px; }
        input { width:100%; padding:12px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.12); background:#1a1a1a; color:#f4f7fb; }
        button { margin-top:8px; width:100%; padding:12px 14px; border:0; border-radius:14px; background:linear-gradient(90deg,#2494ff,#16d2bf); color:#03131a; font-weight:700; }
        .muted { color:#9ba9c0; }
        .error { margin-bottom:14px; padding:12px 14px; border-radius:14px; background:rgba(127,29,29,.35); color:#ffd2d6; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1 style="margin-top:0;">Choose a new password</h1>
        <p class="muted">Reset links expire after 60 minutes. Use a strong password with mixed case letters and numbers.</p>
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required>
            </div>
            <div class="field">
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
