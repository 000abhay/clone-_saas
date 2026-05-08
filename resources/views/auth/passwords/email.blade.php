<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <style>
        body { margin:0; font-family:"Segoe UI",sans-serif; background:linear-gradient(180deg,#08111d 0%,#05070b 24%); color:#f4f7fb; }
        .wrap { min-height:100vh; display:grid; place-items:center; padding:20px; }
        .card { width:min(420px,100%); background:linear-gradient(180deg, rgba(19, 23, 31, 0.96), rgba(12, 15, 22, 0.96)); border:1px solid rgba(115,132,165,.2); border-radius:24px; padding:28px; box-shadow:0 18px 40px rgba(0,0,0,.28); }
        input { width:100%; padding:12px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.12); margin-top:8px; background:#1a1a1a; color:#f4f7fb; }
        button { margin-top:18px; width:100%; padding:12px 14px; border:0; border-radius:14px; background:linear-gradient(90deg,#2494ff,#16d2bf); color:#03131a; font-weight:700; }
        .muted { color:#9ba9c0; }
        .flash { margin-bottom:14px; padding:12px 14px; border-radius:14px; background:rgba(6,95,70,.28); color:#b7ffdc; }
        .error { margin-bottom:14px; padding:12px 14px; border-radius:14px; background:rgba(127,29,29,.35); color:#ffd2d6; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1 style="margin-top:0;">Forgot your password?</h1>
        <p class="muted">Enter your email address and we will send you a time-limited reset link.</p>
        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email" style="font-weight:700;">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            <button type="submit">Send Reset Link</button>
        </form>
        <div style="margin-top:14px;"><a class="muted" href="{{ route('login') }}">Back to sign in</a></div>
    </div>
</div>
</body>
</html>
