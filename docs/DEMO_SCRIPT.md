# Demo Video Script (~90 seconds)

Use this script to record a portfolio demo with Postman, the landing page, or API docs.

---

## Scene 1 — Landing Page (10s)

> "This is my Multi-Tenant Inventory Management API — a Laravel 12 SaaS backend built for portfolio and production use."

Show: `https://YOUR-APP.onrender.com/`

Highlight: 21 tests, 3 RBAC roles, multi-tenant isolation.

---

## Scene 2 — API Documentation (15s)

> "The API is fully documented with OpenAPI via Scramble."

Show: `/docs/api`

Scroll through Auth, Products, and Inventory endpoints.

---

## Scene 3 — Login (15s)

> "I'll log in as the Acme warehouse manager using Sanctum token authentication."

```
POST /api/login
{
  "email": "manager@acme.test",
  "password": "password"
}
```

Copy the token → Authorize in API docs or Postman.

---

## Scene 4 — List Products (10s)

> "Each tenant only sees their own products — data is fully isolated."

```
GET /api/products
Authorization: Bearer {token}
```

Show paginated product list for Acme Electronics.

---

## Scene 5 — Inventory Movement (20s)

> "Operators can record stock movements. The system uses database locking to prevent race conditions."

```
POST /api/products/3/movements
{
  "type": "out",
  "quantity": 2,
  "note": "Customer order"
}
```

Show updated quantity. Mention low-stock job triggers when threshold is reached.

---

## Scene 6 — RBAC (10s)

> "Role-based access is enforced via Policies — viewers cannot create products or manage inventory."

Quickly show a 403 response with viewer account (optional).

---

## Scene 7 — Closing (10s)

> "The project includes 21 automated tests, GitHub Actions CI, Postman collection, and one-click Render deployment. Link in description."

Show: GitHub repo + `/up` health check passing.

---

## Recording Tips

- Use **OBS Studio** or **Loom** (free)
- Resolution: 1920×1080
- Show Postman or Scramble UI — both work
- Keep it under 2 minutes for LinkedIn/portfolio
