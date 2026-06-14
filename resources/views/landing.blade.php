<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — SaaS Inventory API</title>
    <meta name="description" content="Multi-tenant inventory management REST API built with Laravel 12, Sanctum, and Spatie RBAC.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0f19;
            --surface: #111827;
            --border: #1f2937;
            --text: #f9fafb;
            --muted: #9ca3af;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --success: #10b981;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }
        .container { max-width: 960px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
        .badge {
            display: inline-block;
            background: rgba(99, 102, 241, 0.15);
            color: var(--accent-hover);
            border: 1px solid rgba(99, 102, 241, 0.3);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }
        h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 1rem;
        }
        .subtitle {
            color: var(--muted);
            font-size: 1.125rem;
            max-width: 640px;
            margin-bottom: 2rem;
        }
        .actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 3rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-secondary {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { border-color: var(--muted); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2.5rem; }
        .stat {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.25rem;
        }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--accent-hover); }
        .stat-label { color: var(--muted); font-size: 0.875rem; margin-top: 0.25rem; }
        .section { margin-bottom: 2.5rem; }
        .section h2 { font-size: 1.25rem; margin-bottom: 1rem; }
        .stack { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .pill {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 0.35rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { text-align: left; padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); }
        th { color: var(--muted); font-weight: 500; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        code {
            background: rgba(99, 102, 241, 0.1);
            color: var(--accent-hover);
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.85em;
        }
        footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 0.875rem;
        }
        footer a { color: var(--accent-hover); text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <span class="badge">Portfolio Project · Laravel 12 SaaS Backend</span>
        <h1>Multi-Tenant Inventory Management API</h1>
        <p class="subtitle">
            Production-ready REST API serving multiple organizations with complete data isolation,
            role-based access control, and real-time stock monitoring.
        </p>

        <div class="actions">
            <a href="/docs/api" class="btn btn-primary">Open API Docs</a>
            <a href="https://github.com/mahmoud-aljabour/Multi-Tenant-Inventory-Management" class="btn btn-secondary" target="_blank" rel="noopener">GitHub Repository</a>
            <a href="/up" class="btn btn-secondary">Health Check</a>
        </div>

        <div class="grid">
            <div class="stat">
                <div class="stat-value">21</div>
                <div class="stat-label">Automated Tests</div>
            </div>
            <div class="stat">
                <div class="stat-value">3</div>
                <div class="stat-label">RBAC Roles</div>
            </div>
            <div class="stat">
                <div class="stat-value">Multi</div>
                <div class="stat-label">Tenant Isolation</div>
            </div>
            <div class="stat">
                <div class="stat-value">REST</div>
                <div class="stat-label">Sanctum Auth</div>
            </div>
        </div>

        <div class="section">
            <h2>Tech Stack</h2>
            <div class="stack">
                <span class="pill">Laravel 12</span>
                <span class="pill">PHP 8.2+</span>
                <span class="pill">Sanctum</span>
                <span class="pill">Spatie Permission</span>
                <span class="pill">PostgreSQL / MySQL</span>
                <span class="pill">Queue Jobs</span>
                <span class="pill">OpenAPI (Scramble)</span>
            </div>
        </div>

        <div class="section">
            <h2>Demo Credentials</h2>
            <p style="color: var(--muted); margin-bottom: 1rem; font-size: 0.9rem;">
                Password for all accounts: <code>password</code> — Use <code>POST /api/login</code> then authorize in API docs.
            </p>
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Acme Electronics</td>
                            <td>manager@acme.test</td>
                            <td>warehouse_manager</td>
                        </tr>
                        <tr>
                            <td>Acme Electronics</td>
                            <td>operator@acme.test</td>
                            <td>operator</td>
                        </tr>
                        <tr>
                            <td>Acme Electronics</td>
                            <td>viewer@acme.test</td>
                            <td>viewer</td>
                        </tr>
                        <tr>
                            <td>Beta Supplies Co.</td>
                            <td>manager@beta.test</td>
                            <td>warehouse_manager</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <h2>Quick Start</h2>
            <div class="card" style="padding: 1.25rem;">
                <pre style="overflow-x: auto; color: var(--muted); font-size: 0.85rem; line-height: 1.7;"><code>POST /api/login
{
  "email": "manager@acme.test",
  "password": "password"
}

# Use the returned token as Bearer auth for all protected endpoints.</code></pre>
            </div>
        </div>

        <footer>
            Built by <a href="https://github.com/mahmoud-aljabour" target="_blank" rel="noopener">Mahmoud Maher Al Jbour</a>
            · <a href="https://linkedin.com/in/mahmoud-aljabour" target="_blank" rel="noopener">LinkedIn</a>
        </footer>
    </div>
</body>
</html>
