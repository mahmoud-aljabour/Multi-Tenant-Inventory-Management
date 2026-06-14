# Deployment Guide — Render

Deploy the API to [Render](https://render.com) using the included Blueprint.

## One-Click Deploy

1. Push this repository to GitHub.
2. Go to [Render Dashboard](https://dashboard.render.com) → **New** → **Blueprint**.
3. Connect your GitHub repo — Render detects `render.yaml` automatically.
4. Set **APP_URL** to your Render web service URL (e.g. `https://multi-tenant-inventory-api.onrender.com`).
5. Click **Apply** and wait for the build (~3–5 minutes).

## What Gets Created

| Resource | Purpose |
|---|---|
| Web Service | PHP 8.2 Laravel API |
| PostgreSQL DB | Production database (free tier) |

## Environment Variables

| Variable | Default | Description |
|---|---|---|
| `APP_URL` | *(set manually)* | Your public Render URL |
| `SEED_DEMO_DATA` | `true` | Seeds demo tenants/users on deploy |
| `DB_URL` | auto | Injected from Render PostgreSQL |

To disable demo seeding in production, set `SEED_DEMO_DATA=false` in Render dashboard.

## After Deploy

Verify these URLs:

```
https://YOUR-APP.onrender.com/          → Portfolio landing page
https://YOUR-APP.onrender.com/up        → Health check
https://YOUR-APP.onrender.com/docs/api  → OpenAPI documentation
https://YOUR-APP.onrender.com/api/login → API endpoint
```

## Demo Login

```
POST https://YOUR-APP.onrender.com/api/login
{
  "email": "manager@acme.test",
  "password": "password"
}
```

## Local Production Simulation

```bash
composer install --no-dev
cp .env.example .env
php artisan key:generate
# Configure DB_CONNECTION=pgsql and DB_URL for your database
php artisan migrate --seed
php artisan serve
```

## Troubleshooting

| Issue | Fix |
|---|---|
| 500 on first request | Check `APP_KEY` is set and migrations ran |
| DB connection error | Verify `DB_URL` is linked to PostgreSQL service |
| Cold start slow | Render free tier spins down after inactivity (~30s wake) |
| Seed duplicates | Seeders are idempotent — safe to re-run |

## Alternative Platforms

The app also works on **Railway**, **Fly.io**, and **DigitalOcean App Platform**. Use the same build/start commands from `render.yaml`.
