<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRM Pro')</title>
    <style>
        :root {
            --bg: #060708;
            --surface: #131313;
            --surface-soft: #191919;
            --ink: #f5f7fb;
            --muted: #9699a6;
            --border: rgba(255, 255, 255, 0.11);
            --brand: #2798ff;
            --brand-dark: #08d0cf;
            --teal: #08d0cf;
            --blue: #2798ff;
            --green: #19c37d;
            --amber: #f4b400;
            --danger: #ff5d5d;
            --shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
            --sidebar: #0c1126;
            --sidebar-border: rgba(255, 255, 255, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--ink);
            font-family: "Segoe UI", sans-serif;
            background:
                linear-gradient(180deg, rgba(39, 152, 255, 0.18), transparent 90px),
                var(--bg);
        }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        .shell {
            display: grid;
            grid-template-columns: 270px minmax(0, 1fr);
            min-height: 100vh;
        }
        .sidebar {
            padding: 26px 18px;
            background: linear-gradient(180deg, #0d1230 0%, #0a0e20 100%);
            color: var(--ink);
            border-right: 1px solid var(--sidebar-border);
        }
        .brand {
            padding: 8px 10px 26px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .brand-mark {
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--blue), #13d4be);
            color: #031117;
            font-weight: 800;
            letter-spacing: 0.04em;
        }
        .brand h1 {
            margin: 14px 0 6px;
            font-size: 1.8rem;
            color: var(--teal);
        }
        .brand p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }
        .nav-group {
            margin-top: 22px;
            display: grid;
            gap: 8px;
        }
        .nav-label {
            margin: 10px 10px 4px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.72rem;
        }
        .nav-link {
            padding: 12px 14px;
            border-radius: 14px;
            color: #c9d0dc;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }
        .nav-link.active {
            background: linear-gradient(90deg, var(--blue), #13d4be);
            color: #031117;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        }
        .sidebar-footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--sidebar-border);
        }
        .main {
            min-width: 0;
        }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            padding: 18px 26px;
            background: rgba(19, 19, 19, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .headline h2 {
            margin: 0;
            font-size: 1.8rem;
        }
        .headline p {
            margin: 6px 0 0;
            color: var(--muted);
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .avatar-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #10171d;
            border: 1px solid var(--border);
        }
        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            color: #031117;
            font-weight: 800;
            background: linear-gradient(135deg, var(--blue), #13d4be);
        }
        .content {
            padding: 26px;
            display: grid;
            gap: 18px;
        }
        .card, .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
        }
        .card {
            padding: 22px;
        }
        .panel {
            overflow: hidden;
        }
        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: rgba(25, 25, 25, 0.8);
        }
        .panel-body {
            padding: 22px;
        }
        .grid {
            display: grid;
            gap: 18px;
        }
        .grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .metric-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .metric {
            padding: 18px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, #161616 0%, #121212 100%);
        }
        .metric .label {
            color: var(--muted);
            font-size: 0.9rem;
        }
        .metric .value {
            margin-top: 10px;
            font-size: 1.9rem;
            font-weight: 800;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn, .btn-secondary, .btn-link, .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            cursor: pointer;
        }
        .btn {
            background: linear-gradient(90deg, var(--blue), #13d4be);
            color: #031117;
            border: 0;
            font-weight: 700;
        }
        .btn-secondary {
            background: #202020;
            color: var(--ink);
        }
        .btn-link {
            background: transparent;
            color: var(--teal);
        }
        form.inline { display: inline; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }
        th {
            color: var(--muted);
            font-size: 0.84rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .table-wrap {
            overflow-x: auto;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            background: rgba(15, 122, 84, 0.35);
            color: #7ff7d8;
        }
        .badge.warn { color: #ffd36a; background: rgba(127, 94, 29, 0.25); }
        .badge.danger { color: #ff8e8e; background: rgba(127, 29, 29, 0.35); }
        .badge.blue { color: #90bfff; background: rgba(30, 64, 175, 0.28); }
        .badge.green { color: #a4f3d0; background: rgba(6, 95, 70, 0.3); }
        .muted { color: var(--muted); }
        .stack { display: grid; gap: 14px; }
        .field-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .field {
            display: grid;
            gap: 8px;
        }
        .field.full { grid-column: 1 / -1; }
        label {
            font-weight: 700;
            font-size: 0.92rem;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: #1a1a1a;
            color: var(--ink);
        }
        textarea { min-height: 120px; resize: vertical; }
        .flash, .error-box {
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
        }
        .flash {
            background: rgba(6, 95, 70, 0.28);
            color: #b7ffdc;
        }
        .error-box {
            background: rgba(127, 29, 29, 0.35);
            color: #ffc2c2;
        }
        .timeline {
            display: grid;
            gap: 12px;
        }
        .timeline-item {
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #202020;
        }
        .split {
            display: grid;
            gap: 18px;
            grid-template-columns: 1.2fr 0.8fr;
        }
        .kanban {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        .kanban-col {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 14px;
            min-height: 280px;
        }
        .kanban-card {
            padding: 14px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #202020;
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.2);
            margin-bottom: 12px;
        }
        .kanban-card[draggable="true"] { cursor: grab; }
        .kpi-list {
            display: grid;
            gap: 12px;
        }
        .kpi-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
        }
        .footer-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 8px;
        }
        .note {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.6;
        }
        @media (max-width: 1080px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar {
                display: block;
                padding: 18px;
                border-right: 0;
                border-bottom: 1px solid var(--sidebar-border);
            }
            .brand {
                padding: 0 0 16px;
                margin-bottom: 16px;
            }
            .brand p {
                max-width: 680px;
            }
            .nav-group {
                margin-top: 0;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .nav-label {
                width: 100%;
                margin: 0 0 4px;
            }
            .nav-link {
                flex: 1 1 150px;
                text-align: center;
                border: 1px solid rgba(255, 255, 255, 0.08);
                background: rgba(255, 255, 255, 0.04);
            }
            .nav-link:hover {
                transform: none;
                background: rgba(255, 255, 255, 0.08);
            }
            .sidebar-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
            }
            .sidebar-footer form {
                margin-top: 0 !important;
                width: 220px;
                flex-shrink: 0;
            }
            .split, .metric-grid, .grid.cols-2, .grid.cols-3, .field-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 720px) {
            .sidebar {
                padding: 16px;
            }
            .brand p {
                display: none;
            }
            .nav-link {
                flex-basis: calc(50% - 5px);
            }
            .sidebar-footer {
                display: grid;
            }
            .sidebar-footer form {
                width: 100%;
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .content {
                padding: 18px;
            }
        }
        @media (max-width: 520px) {
            .nav-link {
                flex-basis: 100%;
            }
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $allNavigation = [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard'],
        'accounts' => ['label' => 'Accounts', 'route' => 'accounts.index', 'active' => 'accounts.*'],
        'contacts' => ['label' => 'Contacts', 'route' => 'contacts.index', 'active' => 'contacts.*'],
        'leads' => ['label' => 'Leads', 'route' => 'leads.index', 'active' => 'leads.*'],
        'pipeline' => ['label' => 'Pipeline', 'route' => 'pipeline.index', 'active' => 'pipeline.index|deals.*'],
        'tasks' => ['label' => 'Tasks', 'route' => 'tasks.index', 'active' => 'tasks.*'],
        'tickets' => ['label' => 'Support', 'route' => 'tickets.index', 'active' => 'tickets.*'],
        'reports' => ['label' => 'Reports', 'route' => 'reports.index', 'active' => 'reports.*'],
        'team' => ['label' => 'Team', 'route' => 'team.index', 'active' => 'team.*'],
        'settings' => ['label' => 'Settings', 'route' => 'settings.edit', 'active' => 'settings.*'],
    ];
    $navItems = collect($user?->accessibleModules() ?? [])
        ->map(fn ($key) => $allNavigation[$key] ?? null)
        ->filter()
        ->values();
@endphp
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">cp</div>
            <h1>CRM Pro</h1>
            <p>Core CRM workspace for accounts, contacts, leads, pipeline, tasks, support, and reporting.</p>
        </div>

        <div class="nav-group">
            <div class="nav-label">Workspace</div>
            @foreach ($navItems as $item)
                @php($activePatterns = explode('|', $item['active']))
                <a class="nav-link {{ request()->routeIs(...$activePatterns) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="sidebar-footer">
            <div class="note">
                Signed in as <strong>{{ $user?->roleLabel() }}</strong><br>
                Core CRM workspace with real backend workflows.
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:16px;">
                @csrf
                <button class="btn-secondary" type="submit" style="width:100%;">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="headline">
                <h2>@yield('page_title', 'CRM Pro')</h2>
                <p>@yield('page_subtitle', 'Operational workspace')</p>
            </div>
            <div class="topbar-actions">
                @yield('actions')
                <div class="avatar-chip">
                    <div>
                        <div style="font-weight:700;">{{ $user?->name }}</div>
                        <div class="muted">{{ $user?->roleLabel() }}</div>
                    </div>
                    <div class="avatar">{{ $user?->initials() }}</div>
                </div>
            </div>
        </header>

        <section class="content">
            @include('partials.flash')
            @yield('content')
        </section>
    </main>
</div>
@stack('scripts')
</body>
</html>
