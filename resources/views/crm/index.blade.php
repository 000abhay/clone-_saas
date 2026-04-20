<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeVocado CRM</title>
    <style>
        :root {
            --bg: #060708;
            --sidebar: #0c1126;
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --panel: #131313;
            --panel-2: #191919;
            --border: rgba(255, 255, 255, 0.11);
            --text: #f5f7fb;
            --muted: #9699a6;
            --teal: #08d0cf;
            --blue: #2798ff;
            --green: #19c37d;
            --purple: #7c3aed;
            --danger: #ff5d5d;
            --amber: #f4b400;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background:
                linear-gradient(180deg, rgba(39, 152, 255, 0.18), transparent 90px),
                var(--bg);
            color: var(--text);
            font-family: "Segoe UI", sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }

        .app {
            display: grid;
            grid-template-columns: 212px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, #0d1230 0%, #0a0e20 100%);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
        }

        .brand {
            padding: 28px 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand h1 {
            margin: 0;
            font-size: 2rem;
            color: var(--teal);
        }

        .brand p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .nav-group {
            padding: 18px 12px;
        }

        .nav-label {
            margin: 14px 16px 10px;
            color: var(--muted);
            font-size: 0.76rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #c9d0dc;
            margin-bottom: 6px;
        }

        .nav-link.active {
            color: #031117;
            background: linear-gradient(90deg, var(--blue), #13d4be);
            font-weight: 700;
        }

        .main {
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: rgba(19, 19, 19, 0.95);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .ghost-btn,
        .primary-btn {
            border-radius: 12px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            color: var(--text);
            background: #202020;
        }

        .primary-btn {
            border: 0;
            color: #031117;
            font-weight: 700;
            background: linear-gradient(90deg, var(--blue), #15d4be);
        }

        .content {
            padding: 20px;
        }

        .user-card {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
            padding: 16px;
            border: 1px solid rgba(99, 179, 237, 0.45);
            border-radius: 12px;
            background: #10171d;
            max-width: 440px;
            margin-bottom: 24px;
        }

        .user-card h2 {
            margin: 0 0 8px;
            font-size: 1.3rem;
        }

        .muted {
            color: var(--muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 9px;
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-role-super_admin,
        .badge-status-active,
        .badge-stage-closed_won {
            color: #7ff7d8;
            background: rgba(15, 122, 84, 0.35);
            border: 1px solid rgba(25, 195, 125, 0.3);
        }

        .badge-role-admin,
        .badge-priority-high,
        .badge-status-open {
            color: #ff8e8e;
            background: rgba(127, 29, 29, 0.35);
            border: 1px solid rgba(255, 93, 93, 0.25);
        }

        .badge-role-sales_manager,
        .badge-stage-qualified,
        .badge-stage-negotiation {
            color: #90bfff;
            background: rgba(30, 64, 175, 0.28);
            border: 1px solid rgba(59, 130, 246, 0.24);
        }

        .badge-role-sales_exec,
        .badge-stage-proposal,
        .badge-status-in-progress,
        .badge-priority-medium {
            color: #d6b1ff;
            background: rgba(88, 28, 135, 0.32);
            border: 1px solid rgba(124, 58, 237, 0.24);
        }

        .badge-role-support_agent,
        .badge-priority-low,
        .badge-stage-prospect,
        .badge-status-resolved {
            color: #a4f3d0;
            background: rgba(6, 95, 70, 0.3);
            border: 1px solid rgba(16, 185, 129, 0.24);
        }

        .badge-status-inactive {
            color: #d0d5dd;
            background: rgba(55, 65, 81, 0.4);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .section-grid {
            display: grid;
            gap: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-top: 10px;
        }

        .stat-change {
            float: right;
            font-size: 0.78rem;
            color: #72e9b1;
            background: rgba(25, 195, 125, 0.16);
            border-radius: 4px;
            padding: 4px 8px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 18px;
        }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .panel-title {
            margin: 0 0 16px;
            font-size: 1rem;
            font-weight: 700;
        }

        .chart-svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .legend,
        .simple-list,
        .kanban,
        .customer-grid,
        .form-grid {
            display: grid;
            gap: 12px;
        }

        .legend-item,
        .company-item,
        .alert-item,
        .task-item,
        .deal-card,
        .card-mini {
            background: #202020;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
        }

        .legend-item,
        .alert-item,
        .task-item,
        .company-item,
        .card-mini {
            padding: 14px;
        }

        .region-visual {
            width: 180px;
            aspect-ratio: 1;
            border-radius: 50%;
            margin: 10px auto 16px;
            background: conic-gradient(
                #7bb8e6 0 45%,
                #7a2de2 45% 73%,
                #3b82f6 73% 91%,
                #06b6d4 91% 100%
            );
            border: 2px solid rgba(255, 255, 255, 0.12);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            vertical-align: top;
        }

        th {
            color: #d6d6d6;
            font-size: 0.88rem;
        }

        td {
            color: #f1f1f1;
        }

        .customer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .customer-card {
            padding: 20px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .customer-card .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .kanban {
            grid-template-columns: repeat(5, minmax(0, 1fr));
            align-items: start;
        }

        .stage-column {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            min-height: 320px;
        }

        .stage-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .deal-card {
            padding: 14px;
            margin-bottom: 12px;
        }

        .deal-value,
        .money {
            color: var(--teal);
            font-weight: 800;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
        }

        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 12px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .pricing-grid,
        .integration-grid,
        .settings-stack {
            display: grid;
            gap: 18px;
        }

        .pricing-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .plan-card,
        .integration-card,
        .setting-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .plan-card.featured {
            border-color: rgba(39, 152, 255, 0.75);
            box-shadow: inset 0 0 0 1px rgba(39, 152, 255, 0.18);
        }

        .plan-price {
            margin: 16px 0;
            font-size: 2rem;
            font-weight: 800;
        }

        .plan-price span {
            font-size: 1rem;
            font-weight: 400;
            color: var(--muted);
        }

        .feature-list {
            display: grid;
            gap: 10px;
            margin-top: 18px;
            color: #dbe4ec;
        }

        .integration-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .integration-card {
            min-height: 170px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .settings-stack {
            max-width: 780px;
        }

        .performance-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .search-box {
            width: min(100%, 380px);
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: #1a1a1a;
            color: var(--text);
        }

        .account-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--blue), #13d4be);
        }

        .funnel {
            display: grid;
            gap: 0;
            margin-top: 12px;
        }

        .funnel-step {
            margin: 0 auto;
            height: 46px;
            clip-path: polygon(10% 0, 90% 0, 80% 100%, 20% 100%);
        }

        .progress {
            margin-top: 10px;
            height: 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #14d4be, #2f8fff);
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: #1a1a1a;
            color: var(--text);
        }

        .flash,
        .error-box {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .flash {
            color: #b7ffdc;
            background: rgba(6, 95, 70, 0.28);
            border: 1px solid rgba(16, 185, 129, 0.24);
        }

        .error-box {
            color: #ffc2c2;
            background: rgba(127, 29, 29, 0.35);
            border: 1px solid rgba(255, 93, 93, 0.25);
        }

        .spacer {
            flex: 1;
        }

        @media (max-width: 1200px) {
            .stats-grid,
            .dashboard-grid,
            .bottom-grid,
            .report-grid,
            .customer-grid,
            .kanban,
            .pricing-grid,
            .integration-grid,
            .performance-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .app {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @php
        $sectionTitles = [
            'dashboard' => 'Dashboard',
            'leads' => 'Leads',
            'customers' => 'Customers',
            'pipeline' => 'Pipeline',
            'tasks' => 'Tasks',
            'support' => 'Support Tickets',
            'reports' => 'Analytics',
            'team' => 'Team Management',
            'billing' => 'Subscriptions',
            'integrations' => 'Integrations',
            'settings' => 'Settings',
        ];

        $headerActions = [
            'dashboard' => $user->role === 'sales_manager'
                ? [['label' => 'Team Performance Overview', 'type' => 'ghost']]
                : ($user->role === 'sales_exec' ? [] : [['label' => 'Export Report', 'type' => 'ghost']]),
            'leads' => [['label' => 'Filter', 'type' => 'ghost'], ['label' => 'Export', 'type' => 'ghost'], ['label' => 'Add Lead', 'type' => 'primary']],
            'customers' => [['label' => 'Add Customer', 'type' => 'primary']],
            'pipeline' => [['label' => 'New Deal', 'type' => 'primary']],
            'tasks' => [['label' => 'Add Task', 'type' => 'primary']],
            'support' => [['label' => 'New Ticket', 'type' => 'primary']],
            'reports' => [['label' => 'Date Range', 'type' => 'ghost'], ['label' => 'Export', 'type' => 'ghost']],
            'team' => [['label' => 'Add Member', 'type' => 'primary']],
            'billing' => [],
            'integrations' => [],
            'settings' => [],
        ][$section] ?? [];

        $roleClass = fn ($role) => 'badge badge-role-' . $role;
        $statusClass = fn ($status) => 'badge badge-status-' . \Illuminate\Support\Str::slug(strtolower($status), '-');
        $priorityClass = fn ($priority) => 'badge badge-priority-' . strtolower($priority);
        $stageClass = fn ($stage) => 'badge badge-stage-' . $stage;
        $pipelineGroups = $deals->groupBy('stage');
        $maxRevenue = max($monthlyRevenue);
    @endphp

    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <h1>CRM Pro</h1>
                <p>Advanced SaaS Solution</p>
            </div>

            <div class="nav-group">
                @foreach ($navItems as $item)
                    <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="/{{ $item['route'] === 'dashboard' ? 'dashboard' : $item['route'] }}">
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="spacer"></div>

            <div class="nav-group">
                @if (count($saasNavItems))
                    <div class="nav-label">SaaS</div>
                    @foreach ($saasNavItems as $item)
                        <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="/{{ $item['route'] }}">{{ $item['label'] }}</a>
                    @endforeach
                @endif
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="nav-link" style="width:100%; border:0; background:none; text-align:left; cursor:pointer;">Logout</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                @if ($user->role === 'sales_exec')
                    <input class="search-box" type="text" value="" placeholder="Search leads, deals, contacts...">
                @else
                    <div class="page-title">{{ $sectionTitles[$section] ?? 'CRM' }}</div>
                @endif
                <div class="toolbar">
                    @if ($user->role === 'sales_exec')
                        <div class="account-chip">
                            <div style="text-align:right;">
                                <div style="font-weight:700;">Sales Executive</div>
                                <div class="muted">Your Account</div>
                            </div>
                            <div class="avatar"></div>
                        </div>
                    @endif
                    @foreach ($headerActions as $action)
                        <button class="{{ $action['type'] === 'primary' ? 'primary-btn' : 'ghost-btn' }}" type="button">{{ $action['label'] }}</button>
                    @endforeach
                </div>
            </header>

            <div class="content">
                <section class="user-card">
                    <div>
                        <h2>{{ $user->name }}</h2>
                        <div class="muted" style="margin-bottom: 8px;">{{ $user->profile_summary }}</div>
                        <div class="muted">{{ $user->email }}</div>
                    </div>
                    <div class="{{ $roleClass($user->role) }}">{{ strtoupper($user->roleLabel()) }}</div>
                </section>

                @if ($section === 'dashboard')
                    <div class="section-grid">
                        <section class="stats-grid">
                            @foreach ($stats as $stat)
                                <article class="panel">
                                    @if ($stat['change'] !== '')
                                        <span class="stat-change">{{ $stat['change'] }}</span>
                                    @endif
                                    <div class="muted">{{ $stat['label'] }}</div>
                                    <div class="stat-value">{{ $stat['value'] }}</div>
                                </article>
                            @endforeach
                        </section>

                        @if ($user->role === 'sales_manager')
                            <section class="dashboard-grid">
                                <article class="panel">
                                    <h3 class="panel-title">Team Revenue Performance</h3>
                                    <svg class="chart-svg" viewBox="0 0 640 290" xmlns="http://www.w3.org/2000/svg">
                                        <line x1="60" y1="20" x2="60" y2="240" stroke="#666" />
                                        <line x1="60" y1="240" x2="610" y2="240" stroke="#666" />
                                        @foreach ([0, 1, 2, 3] as $index)
                                            @php $x = 110 + ($index * 120); @endphp
                                            <rect x="{{ $x }}" y="{{ [40, 95, 60, 60][$index] }}" width="48" height="{{ [130, 75, 110, 110][$index] }}" fill="#7bb8e6" />
                                            <rect x="{{ $x + 52 }}" y="{{ [95, 95, 95, 95][$index] }}" width="48" height="{{ [75, 75, 75, 75][$index] }}" fill="#7a2de2" />
                                        @endforeach
                                    </svg>
                                </article>

                                <article class="panel">
                                    <h3 class="panel-title">Pipeline Status</h3>
                                    <div class="region-visual" style="background: conic-gradient(#7bb8e6 0 35%, #7a2de2 35% 63%, #3b82f6 63% 85%, #06b6d4 85% 100%);"></div>
                                    <div class="legend">
                                        <div class="legend-item"><strong>Prospecting 35%</strong></div>
                                        <div class="legend-item"><strong>Qualified 28%</strong></div>
                                        <div class="legend-item"><strong>Proposal 22%</strong></div>
                                        <div class="legend-item"><strong>Negotiation 15%</strong></div>
                                    </div>
                                </article>
                            </section>

                            <section class="panel">
                                <h3 class="panel-title">Team Member Performance</h3>
                                <div class="performance-grid">
                                    @foreach ($salesManagerPerformance as $member)
                                        <article class="card-mini">
                                            <div style="display:flex; justify-content:space-between; gap:12px;">
                                                <strong>{{ $member['name'] }}</strong>
                                                <span class="badge badge-status-active">{{ $member['deals'] }} deals</span>
                                            </div>
                                            <div class="muted" style="margin-top:12px;">Revenue: <strong style="color:var(--text);">{{ $member['revenue'] }}</strong></div>
                                            <div class="muted">Target: <strong style="color:var(--text);">{{ $member['target'] }}</strong></div>
                                            <div class="progress"><span style="width: {{ $member['progress'] }}%"></span></div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @elseif ($user->role === 'sales_exec')
                            <section>
                                <h1 style="margin: 0 0 6px;">Dashboard</h1>
                                <p>Welcome back! Here's your business overview.</p>
                            </section>

                            <section class="dashboard-grid">
                                <article class="panel">
                                    <h3 class="panel-title">Revenue Trend</h3>
                                    <svg class="chart-svg" viewBox="0 0 640 290" xmlns="http://www.w3.org/2000/svg">
                                        <line x1="60" y1="20" x2="60" y2="240" stroke="#666" />
                                        <line x1="60" y1="240" x2="610" y2="240" stroke="#666" />
                                        <polyline fill="none" stroke="#7bb8e6" stroke-width="3" points="60,190 170,150 280,170 390,110 500,120 610,90" />
                                        <polyline fill="none" stroke="#9b4dff" stroke-width="2" stroke-dasharray="6 6" points="60,150 170,150 280,150 390,150 500,150 610,150" />
                                    </svg>
                                </article>
                                <article class="panel">
                                    <h3 class="panel-title">Lead Sources</h3>
                                    <div class="region-visual" style="background: conic-gradient(#7bb8e6 0 35%, #7a2de2 35% 60%, #3b82f6 60% 80%, #06b6d4 80% 95%, #8b5cf6 95% 100%);"></div>
                                    <div class="legend">
                                        <div class="legend-item"><strong>Direct 35%</strong></div>
                                        <div class="legend-item"><strong>Referral 25%</strong></div>
                                        <div class="legend-item"><strong>Social 20%</strong></div>
                                        <div class="legend-item"><strong>Email 15%</strong></div>
                                        <div class="legend-item"><strong>Other 5%</strong></div>
                                    </div>
                                </article>
                            </section>

                            <section class="dashboard-grid">
                                <article class="panel">
                                    <h3 class="panel-title">Sales Funnel</h3>
                                    <div class="funnel">
                                        <div class="funnel-step" style="width: 92%; background:#7bb8e6;"></div>
                                        <div class="funnel-step" style="width: 68%; background:#7a2de2;"></div>
                                        <div class="funnel-step" style="width: 46%; background:#3b82f6;"></div>
                                        <div class="funnel-step" style="width: 28%; background:#06b6d4;"></div>
                                    </div>
                                </article>
                                <article class="panel">
                                    <h3 class="panel-title">Recent Activity</h3>
                                    <div class="simple-list">
                                        @foreach ($salesExecActivity as $item)
                                            <div class="company-item">
                                                <strong>{{ $item['name'] }}</strong>
                                                <div class="muted">{{ $item['detail'] }}</div>
                                                <div class="muted">{{ $item['time'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>
                            </section>
                        @else
                        <section class="dashboard-grid">
                            <article class="panel">
                                <h3 class="panel-title">User Growth &amp; Adoption</h3>
                                <svg class="chart-svg" viewBox="0 0 640 290" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="60" y1="20" x2="60" y2="240" stroke="#666" />
                                    <line x1="60" y1="240" x2="610" y2="240" stroke="#666" />
                                    @foreach ([60, 150, 240, 330, 420, 510, 600] as $x)
                                        <line x1="{{ $x }}" y1="20" x2="{{ $x }}" y2="240" stroke="rgba(255,255,255,0.08)" stroke-dasharray="4 4" />
                                    @endforeach
                                    @php
                                        $buildPoints = function ($values, $max, $height = 220, $left = 60, $width = 550) {
                                            $count = count($values) - 1;
                                            return collect($values)->map(function ($value, $index) use ($max, $height, $left, $width, $count) {
                                                $x = $left + ($width / max($count, 1)) * $index;
                                                $y = 240 - (($value / $max) * $height);
                                                return round($x, 1) . ',' . round($y, 1);
                                            })->implode(' ');
                                        };
                                        $userPoints = $buildPoints($userGrowth, max($userGrowth));
                                        $companyPoints = $buildPoints($companyGrowth, max($userGrowth));
                                    @endphp
                                    <polyline fill="none" stroke="#7bb8e6" stroke-width="3" points="{{ $userPoints }}" />
                                    <polyline fill="none" stroke="#7a2de2" stroke-width="3" points="{{ $companyPoints }}" />
                                </svg>
                                <div class="muted">users vs companies</div>
                            </article>

                            <article class="panel">
                                <h3 class="panel-title">Geographic Distribution</h3>
                                <div class="region-visual"></div>
                                <div class="legend">
                                    @foreach ($regions as $region)
                                        <div class="legend-item">
                                            <strong>{{ $region['label'] }} {{ $region['value'] }}%</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        </section>

                        <section class="bottom-grid">
                            <article class="panel">
                                <h3 class="panel-title">System Alerts</h3>
                                <div class="simple-list">
                                    @foreach ($alerts as $alert)
                                        <div class="alert-item">
                                            <strong>{{ $alert['title'] }}</strong>
                                            <div class="muted">{{ $alert['detail'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </article>

                            <article class="panel">
                                <h3 class="panel-title">Top Companies</h3>
                                <div class="simple-list">
                                    @foreach ($customers->sortByDesc('total_revenue')->take(4) as $customer)
                                        <div class="company-item">
                                            <strong>{{ $customer->name }}</strong>
                                            <div class="muted">{{ number_format($customer->active_deals) }} active deals</div>
                                            <div class="money">${{ number_format($customer->total_revenue) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        </section>
                        @endif
                    </div>
                @endif

                @if ($section === 'leads')
                    <section class="panel">
                        <h2 style="margin-top:0;">Leads</h2>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Value</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr>
                                            <td><strong>{{ $lead->name }}</strong></td>
                                            <td>{{ $lead->company }}</td>
                                            <td>
                                                <div class="muted">{{ $lead->email }}</div>
                                                <div class="muted">{{ $lead->phone }}</div>
                                            </td>
                                            <td><span class="{{ $stageClass(\Illuminate\Support\Str::slug(strtolower($lead->status), '_')) }}">{{ $lead->status }}</span></td>
                                            <td><strong>${{ number_format($lead->value) }}</strong></td>
                                            <td>{{ $lead->contacted_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                @if ($section === 'customers')
                    <section class="customer-grid">
                        @foreach ($customers as $customer)
                            <article class="customer-card">
                                <div style="display:flex; justify-content:space-between; gap:12px;">
                                    <div>
                                        <h3 style="margin:0 0 8px;">{{ $customer->name }}</h3>
                                        <div class="muted">{{ $customer->industry }}</div>
                                    </div>
                                    <span class="{{ $statusClass($customer->status) }}">{{ ucfirst($customer->status) }}</span>
                                </div>
                                <div class="simple-list" style="margin-top:16px;">
                                    <div class="muted">{{ $customer->location }}</div>
                                    <div class="muted">{{ $customer->email }}</div>
                                    <div class="muted">{{ $customer->phone }}</div>
                                </div>
                                <div class="footer">
                                    <div>
                                        <div class="muted">Total Revenue</div>
                                        <div class="money">${{ number_format($customer->total_revenue) }}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div class="muted">Active Deals</div>
                                        <div style="font-weight:800; color:#4ea5ff;">{{ $customer->active_deals }}</div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </section>
                @endif

                @if ($section === 'pipeline')
                    <section class="section-grid">
                        <div class="report-grid">
                            <article class="panel">
                                <h3 class="panel-title">Deals by Stage</h3>
                                <svg class="chart-svg" viewBox="0 0 500 260" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="50" y1="20" x2="50" y2="220" stroke="#666" />
                                    <line x1="50" y1="220" x2="470" y2="220" stroke="#666" />
                                    @foreach ($stages as $key => $label)
                                        @php
                                            $index = array_search($key, array_keys($stages), true);
                                            $count = $pipelineGroups->get($key, collect())->count();
                                            $height = $count * 14 + 20;
                                            $x = 65 + ($index * 78);
                                            $y = 220 - $height;
                                        @endphp
                                        <rect x="{{ $x }}" y="{{ $y }}" width="55" height="{{ $height }}" fill="#7bb8e6" />
                                    @endforeach
                                </svg>
                            </article>

                            <article class="panel">
                                <h3 class="panel-title">Pipeline Value</h3>
                                <svg class="chart-svg" viewBox="0 0 500 260" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="50" y1="20" x2="50" y2="220" stroke="#666" />
                                    <line x1="50" y1="220" x2="470" y2="220" stroke="#666" />
                                    @php
                                        $stageValues = collect(array_keys($stages))->map(fn ($key) => $pipelineGroups->get($key, collect())->sum('value'))->values()->all();
                                        $maxStageValue = max($stageValues ?: [1]);
                                        $linePoints = collect($stageValues)->map(function ($value, $index) use ($maxStageValue) {
                                            $x = 50 + ($index * 100);
                                            $y = 220 - (($value / max($maxStageValue, 1)) * 160);
                                            return round($x, 1) . ',' . round($y, 1);
                                        })->implode(' ');
                                    @endphp
                                    <polyline fill="none" stroke="#7bb8e6" stroke-width="3" points="{{ $linePoints }}" />
                                </svg>
                            </article>
                        </div>

                        <section>
                            <h2>Kanban View</h2>
                            <div class="kanban">
                                @foreach ($stages as $stageKey => $stageLabel)
                                    <article class="stage-column">
                                        <div class="stage-head">
                                            <span>{{ $stageLabel }}</span>
                                            <span class="badge">{{ $pipelineGroups->get($stageKey, collect())->count() }}</span>
                                        </div>
                                        @foreach ($pipelineGroups->get($stageKey, collect()) as $deal)
                                            <div class="deal-card">
                                                <strong>{{ $deal->title }}</strong>
                                                <div class="muted">{{ $deal->company }}</div>
                                                <div class="deal-value">${{ number_format($deal->value) }}</div>
                                            </div>
                                        @endforeach
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    </section>
                @endif

                @if ($section === 'tasks')
                    <section class="section-grid">
                        @foreach ($tasks as $task)
                            <article class="task-item">
                                <div>
                                    <h3 style="margin:0 0 8px; {{ $task->completed ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</h3>
                                    <div class="muted">{{ $task->description }}</div>
                                    <div class="task-meta">
                                        <span>{{ $task->due_date->format('n/j/Y') }}</span>
                                        <span>{{ $task->assignedUser?->name }}</span>
                                        <span>{{ $task->task_status }}</span>
                                    </div>
                                </div>
                                <div class="{{ $priorityClass($task->priority) }}">{{ ucfirst($task->priority) }}</div>
                            </article>
                        @endforeach
                    </section>
                @endif

                @if ($section === 'support')
                    <section class="panel">
                        <h2 style="margin-top:0;">Support Tickets</h2>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assignee</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td style="color:var(--teal);">{{ $ticket->ticket_number }}</td>
                                            <td><strong>{{ $ticket->title }}</strong></td>
                                            <td>{{ $ticket->customer?->name }}</td>
                                            <td><span class="{{ $statusClass($ticket->status) }}">{{ $ticket->status }}</span></td>
                                            <td style="color:{{ strtolower($ticket->priority) === 'high' ? '#ff7d7d' : '#f4d400' }}">{{ $ticket->priority }}</td>
                                            <td>{{ $ticket->assignee?->name }}</td>
                                            <td>{{ $ticket->updated_label }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                @if ($section === 'reports')
                    <section class="section-grid">
                        <div class="report-grid">
                            <article class="panel">
                                <h3 class="panel-title">Monthly Sales Revenue</h3>
                                <svg class="chart-svg" viewBox="0 0 500 260" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="50" y1="20" x2="50" y2="220" stroke="#666" />
                                    <line x1="50" y1="220" x2="470" y2="220" stroke="#666" />
                                    @php
                                        $revenuePoints = collect($monthlyRevenue)->map(function ($value, $index) use ($maxRevenue) {
                                            $x = 50 + ($index * 80);
                                            $y = 220 - (($value / $maxRevenue) * 160);
                                            return round($x, 1) . ',' . round($y, 1);
                                        })->implode(' ');
                                    @endphp
                                    <polyline fill="none" stroke="#7bb8e6" stroke-width="3" points="{{ $revenuePoints }}" />
                                </svg>
                            </article>
                            <article class="panel">
                                <h3 class="panel-title">Lead Conversion Funnel</h3>
                                <div class="region-visual" style="background: conic-gradient(#7bb8e6 0 40%, #7a2de2 40% 70%, #3b82f6 70% 90%, #06b6d4 90% 100%);"></div>
                            </article>
                        </div>
                        <article class="panel">
                            <h3 class="panel-title">Sales Agent Performance</h3>
                            <svg class="chart-svg" viewBox="0 0 900 280" xmlns="http://www.w3.org/2000/svg">
                                <line x1="50" y1="20" x2="50" y2="230" stroke="#666" />
                                <line x1="50" y1="230" x2="860" y2="230" stroke="#666" />
                                @php
                                    $performance = [
                                        ['name' => 'Sarah', 'value' => 125000],
                                        ['name' => 'James', 'value' => 98000],
                                        ['name' => 'Emma', 'value' => 86000],
                                        ['name' => 'Mike', 'value' => 110000],
                                    ];
                                    $maxPerf = collect($performance)->max('value');
                                @endphp
                                @foreach ($performance as $index => $entry)
                                    @php
                                        $x = 70 + ($index * 180);
                                        $height = ($entry['value'] / $maxPerf) * 150;
                                        $y = 230 - $height;
                                    @endphp
                                    <rect x="{{ $x }}" y="{{ $y }}" width="80" height="{{ $height }}" fill="#7bb8e6" />
                                    <text x="{{ $x }}" y="255" fill="#b8c1ce">{{ $entry['name'] }}</text>
                                @endforeach
                            </svg>
                        </article>
                    </section>
                @endif

                @if ($section === 'team')
                    <section class="section-grid">
                        <article class="panel">
                            <h2 style="margin-top:0;">Team Members</h2>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Last Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teamMembers as $member)
                                            <tr>
                                                <td><strong>{{ $member->name }}</strong></td>
                                                <td class="muted">{{ $member->email }}</td>
                                                <td><span class="{{ $roleClass($member->role) }}">{{ $member->roleLabel() }}</span></td>
                                                <td><span class="{{ $statusClass($member->statusLabel()) }}">{{ $member->statusLabel() }}</span></td>
                                                <td class="muted">{{ $member->last_active_at?->diffForHumans() ?? 'Never' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </article>

                        @if ($user->canManageTeam())
                            <article class="panel">
                                <h2 style="margin-top:0;">Add Team Member</h2>

                                @if (session('status'))
                                    <div class="flash">{{ session('status') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-box">{{ $errors->first() }}</div>
                                @endif

                                <form method="POST" action="/team/members">
                                    @csrf
                                    <div class="form-grid">
                                        <label class="field">
                                            <span>Name</span>
                                            <input type="text" name="name" value="{{ old('name') }}" required>
                                        </label>
                                        <label class="field">
                                            <span>Email</span>
                                            <input type="email" name="email" value="{{ old('email') }}" required>
                                        </label>
                                        <label class="field">
                                            <span>Role</span>
                                            <select name="role" required>
                                                <option value="super_admin">Super Admin</option>
                                                <option value="admin">Admin</option>
                                                <option value="sales_manager">Sales Manager</option>
                                                <option value="sales_exec">Sales Executive</option>
                                                <option value="support_agent">Support Agent</option>
                                            </select>
                                        </label>
                                        <label class="field">
                                            <span>Password</span>
                                            <input type="password" name="password" required>
                                        </label>
                                        <label class="field full">
                                            <span>Profile Summary</span>
                                            <input type="text" name="profile_summary" value="{{ old('profile_summary') }}" placeholder="Example: Full platform access & analytics" required>
                                        </label>
                                    </div>
                                    <div style="margin-top:16px;">
                                        <button class="primary-btn" type="submit">Create User</button>
                                    </div>
                                </form>
                            </article>
                        @endif
                    </section>
                @endif

                @if ($section === 'billing')
                    <section class="section-grid">
                        <section>
                            <h2>Choose Your Plan</h2>
                            <div class="pricing-grid">
                                @foreach ($billingPlans as $plan)
                                    <article class="plan-card {{ $plan['featured'] ? 'featured' : '' }}">
                                        @if ($plan['featured'])
                                            <span class="badge" style="background: linear-gradient(90deg, var(--blue), #13d4be); color:#031117;">POPULAR</span>
                                        @endif
                                        <h3 style="margin:12px 0 6px;">{{ $plan['name'] }}</h3>
                                        <div class="muted">{{ $plan['subtitle'] }}</div>
                                        <div class="plan-price">{{ $plan['price'] }}<span>{{ $plan['period'] }}</span></div>
                                        <button class="{{ $plan['current'] ? 'primary-btn' : 'ghost-btn' }}" type="button" style="width:100%;">{{ $plan['button'] }}</button>
                                        <div class="feature-list">
                                            @foreach ($plan['features'] as $feature)
                                                <div>{{ $feature }}</div>
                                            @endforeach
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <section class="panel">
                            <h2 style="margin-top:0;">Billing History</h2>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($billingHistory as $invoice)
                                            <tr>
                                                <td><strong>{{ $invoice['invoice'] }}</strong></td>
                                                <td class="muted">{{ $invoice['date'] }}</td>
                                                <td><strong>{{ $invoice['amount'] }}</strong></td>
                                                <td><span class="badge badge-status-active">{{ $invoice['status'] }}</span></td>
                                                <td style="color: var(--teal);">Download</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </section>
                @endif

                @if ($section === 'integrations')
                    <section class="section-grid">
                        <h2>Connect Your Tools</h2>
                        <div class="integration-grid">
                            @foreach ($integrations as $integration)
                                <article class="integration-card">
                                    <div>
                                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                                            <h3 style="margin:0;">{{ $integration['name'] }}</h3>
                                            @if ($integration['status'])
                                                <span class="badge badge-status-active">{{ $integration['status'] }}</span>
                                            @endif
                                        </div>
                                        <div class="muted" style="margin-top:8px;">{{ $integration['desc'] }}</div>
                                    </div>
                                    <button class="{{ $integration['action'] === 'Connect' ? 'primary-btn' : 'ghost-btn' }}" type="button" style="width:100%;">{{ $integration['action'] }}</button>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($section === 'settings')
                    <section class="settings-stack">
                        <article class="setting-card">
                            <h2 style="margin-top:0;">Account</h2>
                            <div class="form-grid">
                                <label class="field">
                                    <span>Full Name</span>
                                    <input type="text" value="{{ $settingsData['account']['name'] }}">
                                </label>
                                <label class="field">
                                    <span>Email</span>
                                    <input type="email" value="{{ $settingsData['account']['email'] }}">
                                </label>
                                <label class="field full">
                                    <span>Role</span>
                                    <input type="text" value="{{ $settingsData['account']['role'] }}">
                                </label>
                            </div>
                            <div style="margin-top:16px;">
                                <button class="primary-btn" type="button">Save Changes</button>
                            </div>
                        </article>

                        <article class="setting-card">
                            <h2 style="margin-top:0;">Security</h2>
                            <div class="section-grid">
                                <label class="field">
                                    <span>Current Password</span>
                                    <input type="password" placeholder="Enter current password">
                                </label>
                                <label class="field">
                                    <span>New Password</span>
                                    <input type="password" placeholder="Enter new password">
                                </label>
                                <label class="field">
                                    <span>Confirm Password</span>
                                    <input type="password" placeholder="Confirm new password">
                                </label>
                            </div>
                            <div style="margin-top:16px;">
                                <button class="primary-btn" type="button">Update Password</button>
                            </div>
                        </article>

                        <article class="setting-card">
                            <h2 style="margin-top:0;">API Keys</h2>
                            <div class="muted" style="margin-bottom:16px;">Manage API keys for integrations</div>
                            <div class="card-mini">
                                <div style="display:flex; justify-content:space-between; gap:12px;">
                                    <strong>Production API Key</strong>
                                    <span style="color: var(--teal);">Copy</span>
                                </div>
                                <div class="muted" style="margin-top:12px;">{{ $settingsData['api_key'] }}</div>
                            </div>
                            <div style="margin-top:16px;">
                                <button class="ghost-btn" type="button">Generate New Key</button>
                            </div>
                        </article>

                        <article class="setting-card">
                            <h2 style="margin-top:0;">Notifications</h2>
                            <div class="section-grid">
                                @foreach ($settingsData['notifications'] as $notification)
                                    <label class="card-mini" style="display:flex; align-items:center; gap:12px;">
                                        <input type="checkbox" {{ $notification['enabled'] ? 'checked' : '' }}>
                                        <span>{{ $notification['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </article>
                    </section>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
