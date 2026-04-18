<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeVocado | Dashboard</title>
    <style>
        :root {
            --bg: #071019;
            --panel: rgba(17, 22, 30, 0.95);
            --border: rgba(124, 145, 178, 0.18);
            --text: #f3f7fb;
            --muted: #9eadc5;
            --primary: #2494ff;
            --accent: #16d2bf;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(36, 148, 255, 0.18), transparent 30%),
                radial-gradient(circle at right, rgba(22, 210, 191, 0.16), transparent 28%),
                linear-gradient(180deg, #08111d 0%, var(--bg) 24%);
        }

        .shell {
            width: min(1140px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 40px;
        }

        .topbar,
        .hero,
        .card {
            border: 1px solid var(--border);
            background: var(--panel);
            box-shadow: 0 22px 70px rgba(0, 0, 0, 0.24);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border-radius: 22px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            color: #06131b;
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }

        .logout {
            padding: 10px 16px;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            color: var(--text);
            background: rgba(255,255,255,0.03);
            cursor: pointer;
        }

        .hero {
            margin-top: 24px;
            padding: 28px;
            border-radius: 28px;
        }

        .eyebrow {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            color: #d0fbf1;
            background: rgba(22, 210, 191, 0.12);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        h1 {
            margin: 16px 0 10px;
            font-size: clamp(2rem, 4vw, 3.5rem);
        }

        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 24px;
        }

        .card {
            padding: 22px;
            border-radius: 22px;
        }

        .label {
            color: var(--muted);
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .value {
            margin-top: 10px;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .list {
            display: grid;
            gap: 12px;
            margin-top: 14px;
        }

        .list-item {
            padding: 14px 16px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            background: rgba(255,255,255,0.02);
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    @php
        $features = match (auth()->user()->role) {
            'super_admin' => ['Platform analytics', 'Organization control', 'Security policies'],
            'admin' => ['Company management', 'Reporting overview', 'Team permissions'],
            'sales_manager' => ['Pipeline tracking', 'Team targets', 'Deal forecasting'],
            'sales_exec' => ['Lead follow-up', 'Opportunity board', 'Meeting notes'],
            'support_agent' => ['Ticket queue', 'Response SLAs', 'Customer updates'],
            default => ['Workspace access', 'User tools', 'Shared reports'],
        };
    @endphp

    <div class="shell">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark">cv</div>
                <div>
                    <div>CodeVocado</div>
                    <div style="color: var(--muted); font-size: 0.92rem;">Signed in as {{ auth()->user()->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Logout</button>
            </form>
        </header>

        <section class="hero">
            <div class="eyebrow">{{ auth()->user()->roleLabel() }}</div>
            <h1>Welcome back, {{ auth()->user()->name }}.</h1>
            <p>
                This protected page confirms the login flow is working for different seeded user roles.
                You can now expand this into a full SaaS dashboard with real modules and permissions.
            </p>
        </section>

        <section class="grid">
            <article class="card">
                <div class="label">User Role</div>
                <div class="value">{{ auth()->user()->roleLabel() }}</div>
            </article>
            <article class="card">
                <div class="label">Access Model</div>
                <div class="value">Session Auth</div>
            </article>
            <article class="card">
                <div class="label">Backend Stack</div>
                <div class="value">PHP + Laravel</div>
            </article>
        </section>

        <section class="card" style="margin-top: 24px;">
            <div class="label">Available Modules</div>
            <div class="list">
                @foreach ($features as $feature)
                    <div class="list-item">{{ $feature }}</div>
                @endforeach
            </div>
        </section>
    </div>
</body>
</html>
