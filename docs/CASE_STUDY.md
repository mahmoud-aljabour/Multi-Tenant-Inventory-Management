# Case Study — Multi-Tenant Inventory Management API

> Use this content on your portfolio website, LinkedIn project section, or CV.

---

## Project Overview

**Multi-Tenant Inventory Management API** is a SaaS-style backend that allows multiple independent companies to manage products and inventory through a single application — with complete data isolation between tenants.

**Role:** Backend Developer (Solo Project)  
**Duration:** 2026  
**Live Demo:** `https://YOUR-APP.onrender.com` *(update after deploy)*  
**Repository:** [github.com/mahmoud-aljabour/Multi-Tenant-Inventory-Management](https://github.com/mahmoud-aljabour/Multi-Tenant-Inventory-Management)

---

## The Problem

Small and mid-size businesses need inventory tracking software, but building separate systems per company is expensive and hard to maintain. A SaaS approach requires:

- Strict data isolation between organizations
- Role-based access within each organization
- Reliable stock tracking without race conditions
- Scalable authentication for API consumers

---

## The Solution

A RESTful Laravel 12 API with:

- **Multi-tenancy** via Eloquent Global Scopes + Spatie Permission Teams
- **Sanctum token auth** for stateless API access
- **Three-tier RBAC:** Viewer, Operator, Warehouse Manager
- **Inventory movements** with database row locking and cached quantity column
- **Async low-stock alerts** via Laravel Queue Jobs
- **21 automated tests** covering auth, RBAC, tenant isolation, and inventory logic

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Auth | Laravel Sanctum |
| Authorization | Spatie Permission + Laravel Policies |
| Database | PostgreSQL / MySQL |
| Queue | Database driver |
| API Docs | Scramble (OpenAPI) |
| CI | GitHub Actions |
| Deployment | Render |

---

## Key Technical Decisions

### 1. Tenant Isolation with Global Scopes
Every query on tenant-owned models automatically filters by `tenant_id`, preventing cross-tenant data leaks at the ORM level.

### 2. Service Layer Architecture
Business logic lives in dedicated services (`AuthService`, `ProductService`, `InventoryService`), keeping controllers thin and testable.

### 3. Race Condition Prevention
Stock movements use `DB::transaction()` + `lockForUpdate()` to prevent overselling when concurrent requests occur.

### 4. Policy-Based Authorization
Laravel Policies replace inline permission checks, making authorization rules explicit and reusable.

---

## Results

- **21 passing tests** with CI on every push
- **Full API documentation** auto-generated at `/docs/api`
- **Postman collection** for easy testing
- **Demo data** with 2 tenants, 5 users, and sample products
- **Production-ready deploy** via Render Blueprint

---

## What I Learned

- Designing multi-tenant data isolation patterns in Laravel
- Implementing team-scoped RBAC with Spatie Permission
- Handling concurrency in inventory systems
- Building portfolio-ready projects with docs, tests, and live demos

---

## Links

- [GitHub Repository](https://github.com/mahmoud-aljabour/Multi-Tenant-Inventory-Management)
- [Live API](https://YOUR-APP.onrender.com)
- [API Documentation](https://YOUR-APP.onrender.com/docs/api)
- [LinkedIn](https://linkedin.com/in/mahmoud-aljabour)
