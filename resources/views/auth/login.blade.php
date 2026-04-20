<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeVocado | Sign In</title>
    <style>
        :root {
            --bg: #05070b;
            --panel: rgba(17, 21, 29, 0.94);
            --panel-soft: rgba(15, 18, 25, 0.82);
            --border: rgba(115, 132, 165, 0.2);
            --text: #f4f7fb;
            --muted: #9ba9c0;
            --primary: #2494ff;
            --accent: #16d2bf;
            --danger: #ff7b86;
            --input: rgba(255, 255, 255, 0.04);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top, rgba(36, 148, 255, 0.24), transparent 32%),
                radial-gradient(circle at bottom right, rgba(22, 210, 191, 0.18), transparent 28%),
                linear-gradient(180deg, #08111d 0%, var(--bg) 24%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 18px 0 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.6rem;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            color: #051018;
            font-size: 1.1rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }

        .topbar-note {
            color: var(--muted);
            font-size: 0.96rem;
        }

        .content {
            display: flex;
            justify-content: center;
            padding-top: 52px;
        }

        .credential-note,
        .forgot-link {
            color: var(--muted);
        }

        .card {
            position: relative;
            width: min(100%, 430px);
            padding: 34px;
            border: 1px solid var(--border);
            border-radius: 26px;
            background: linear-gradient(180deg, rgba(19, 23, 31, 0.96), rgba(12, 15, 22, 0.96));
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.38);
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(180deg, rgba(36, 148, 255, 0.36), rgba(22, 210, 191, 0.16), rgba(255, 255, 255, 0.04));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .card h2 {
            margin: 0 0 8px;
            font-size: 2.1rem;
        }

        .card p {
            margin: 0 0 24px;
            color: var(--muted);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .field {
            margin-bottom: 18px;
        }

        .input {
            width: 100%;
            padding: 15px 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            outline: none;
            color: var(--text);
            background: var(--input);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input:focus {
            border-color: rgba(36, 148, 255, 0.82);
            box-shadow: 0 0 0 4px rgba(36, 148, 255, 0.12);
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin: 14px 0 24px;
            font-size: 0.92rem;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
        }

        .btn {
            width: 100%;
            padding: 15px 18px;
            border: 0;
            border-radius: 14px;
            color: #03131a;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .error {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid rgba(255, 123, 134, 0.22);
            border-radius: 14px;
            color: #ffd2d6;
            background: rgba(255, 123, 134, 0.1);
            font-size: 0.92rem;
        }

        .credential-note {
            margin-top: 20px;
            font-size: 0.9rem;
            line-height: 1.6;
            text-align: center;
        }

        @media (max-width: 920px) {
            .content {
                padding-top: 30px;
            }
        }

        @media (max-width: 640px) {
            .shell {
                width: min(100% - 20px, 1120px);
            }

            .topbar {
                align-items: flex-start;
                gap: 10px;
                flex-direction: column;
            }

            .card {
                padding: 26px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark">cv</div>
                <span>CodeVocado</span>
            </div>
            <div class="topbar-note">Sign in to your account</div>
        </header>

        <main class="content">
            <section class="card">
                <h2>Sign In</h2>
                <p>Use one of the seeded users from the backend to access the dashboard.</p>

                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="field">
                        <label for="email">Email Address</label>
                        <input
                            class="input"
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter email..."
                            required
                            autofocus
                        >
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input
                            class="input"
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter password..."
                            required
                        >
                    </div>

                    <div class="row">
                        <label class="remember" for="remember">
                            <input id="remember" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        <span class="forgot-link">Role-based access enabled</span>
                    </div>

                    <button class="btn" type="submit">Sign In</button>
                </form>

                <div class="credential-note">
                    Default password for all seeded users: <strong>Password123!</strong>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
