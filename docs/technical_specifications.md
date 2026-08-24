# Technical Specifications

**Project:** Bilingual Personal Portfolio & Resume Management System
**Status:** Draft — v1.3 (May 2026)

A Laravel 13 application combining a public bilingual portfolio (EN / FR), a Filament-powered backoffice, and an automated resume generation engine. The owner edits all content through `/admin`; the public site renders it in two locales; a resume engine exports PDFs and a print-friendly HTML view from the same data.

> **Companion files** — the installed stack is auto-documented in `CLAUDE.md` (Laravel Boost block). The phased build plan and risk register live in `docs/roadmap.md`. This file describes **what** the product does and **how** it must behave; it does not duplicate package versions or sequencing.

---

## 1. Scope

**In scope**

1. A public-facing portfolio website available in English and French.
2. A secure Filament backoffice for managing all dynamic content.
3. A resume engine producing downloadable PDFs and a print-friendly web version.
4. Supporting infrastructure: Docker dev stack, CI pipeline, monitoring, backups.

**Out of scope**

- Multi-user collaboration or role hierarchies beyond a single administrator.
- E-commerce features or paid content.
- Native mobile applications.
- Third-party blog import/export beyond standard Markdown.

---

## 2. Functional Requirements

### 2.1 Public Website

| Page | Description |
| --- | --- |
| **Home** | Hero, short biography, featured projects, primary CTAs (view full résumé, download PDF, contact). |
| **Projects index** | Filterable list of portfolio projects with cover images, short descriptions, technologies, and links. |
| **Project detail** | Richer description, image gallery, external links (live site, repository, case study). |
| **Resume (web)** | A web rendering of the résumé generated from the same data as the PDF export. Print-friendly via a dedicated print stylesheet. |
| **Contact** | Contact form with anti-spam protection (honeypot + captcha + rate limit). Submissions stored as `ContactMessage` entities and trigger an email notification. |

### 2.2 Internationalization

- Two locales at launch: English (`en`) and French (`fr`).
- Locale exposed in the URL via path prefix: `/en/projects`, `/fr/projets`.
- Static UI strings: PHP language files under `lang/{locale}/*.php` plus JSON files for short labels.
- Route segments are translatable via `lang/{locale}/routes.php` (e.g. `projects` → `projets`, `resume` → `cv`).
- Dynamic content uses translatable JSON columns (`spatie/laravel-translatable`) — no `_translations` tables.
- Every public page renders `<link rel="alternate" hreflang="…">` tags for `en`, `fr`, and `x-default`.
- The language switcher preserves the current page (`/en/projects` ↔ `/fr/projets`).

**Locale resolution order**

1. URL path prefix (authoritative).
2. `Accept-Language` header.
3. Default locale (English).
4. The user's last manual choice is remembered in a long-lived cookie.

### 2.3 Authentication

Filament owns the entire auth surface. Fortify is **not** used.

- `/admin` is the only authenticated area; the public site has no login.
- The Filament panel is configured with `->login()`, `->passwordReset()`, `->emailVerification()`, `->profile()`. `->registration()` is intentionally **not** enabled — single-admin site.
- Multi-factor authentication is required: `->multiFactorAuthentication([AppAuthentication::make()->recoverable()], isRequired: true)`. TOTP via authenticator apps; recovery codes generated on enrolment and re-issuable from the profile screen.
- The `User` model implements `FilamentUser`, `HasAppAuthentication`, `MustVerifyEmail` and uses the `InteractsWithAppAuthentication` trait. MFA secrets and recovery codes live in the `users` table (`app_authentication_secret`, `app_authentication_recovery_codes`).
- Login throttling and session timeout are handled by Laravel + Filament defaults.

### 2.4 Backoffice (Filament Panel)

Mounted at `/admin`. All administrative UI is implemented as Filament Resources, Pages, and Widgets — no hand-rolled CRUD.

| Resource | Purpose |
| --- | --- |
| Profile | Full name, headline, biography, contact channels, profile picture (singleton). |
| Experiences | Job title, company, location, start/end dates, description, achievements (ordered). |
| Education | Institution, degree, field, dates, description. |
| Skills | Name, category, proficiency level, optional icon (ordered, reorderable). |
| Projects | Title, slug, summary, full description, tech stack, gallery, external links, featured flag. |
| Languages spoken | Language, proficiency level (CEFR or descriptive). |
| Certifications | Title, issuer, date, credential URL. |
| Contact messages | Read-only list of submissions from the public contact form. |
| Site settings | SEO defaults, social links, résumé template selection. |

**Common behaviours**

- Translatable resources expose EN/FR locale tabs via `filament/spatie-laravel-translatable-plugin`.
- List views support search, sorting, and `->reorderable()` where order matters.
- Destructive actions require confirmation.
- Every write action is recorded in an audit log (actor, action, target, timestamp, diff).

### 2.5 Resume Engine

**Generation flow**

1. The user requests a résumé export (public site or backoffice).
2. A controller dispatches a queued job carrying the locale and template id.
3. The job renders a dedicated Blade résumé template using current backoffice data.
4. Browsershot converts the HTML into a PDF.
5. The result is cached (keyed by `locale`, `template`, `content_hash`) and streamed to the client.

**Outputs**

- Downloadable PDF (A4 default, US Letter optional) in the active locale.
- Print-friendly HTML at `/{locale}/resume/print`.
- JSON export of the structured résumé at `/{locale}/resume.json`.

**Templates** — at least one polished template at launch. Adding a template = adding a Blade view under `resources/views/resume/templates/` and registering it in the Site Settings page.

---

## 3. Database Design

### 3.1 Conventions

- Tables use snake_case plural names (`projects`, `experiences`).
- Translatable text fields are stored as `jsonb` columns containing locale keys: `{"en": "…", "fr": "…"}`.
- Every table includes `id`, `created_at`, `updated_at`, and where appropriate `deleted_at` for soft deletes.
- Slugs are unique per locale and generated on save.

### 3.2 Core tables

| Table | Notable columns | Notes |
| --- | --- | --- |
| `users` | name, email, password, app_authentication_secret, app_authentication_recovery_codes | Single administrator account (Filament-managed MFA). |
| `profile` | full_name, headline (jsonb), bio (jsonb), email, phone, location, social_links (jsonb) | Singleton row representing the site owner. |
| `experiences` | title (jsonb), company, location, start_date, end_date, description (jsonb), order | Ordered list of professional experiences. |
| `education` | institution, degree (jsonb), field (jsonb), start_date, end_date, description (jsonb) | Academic background. |
| `skills` | name (jsonb), category, level, icon, order | Skill list, grouped by category for the public display. |
| `projects` | title (jsonb), slug (jsonb), summary (jsonb), body (jsonb), tech_stack (jsonb), links (jsonb), featured | Portfolio entries with media via the media library. |
| `certifications` | title (jsonb), issuer, issued_at, credential_url | Optional list of certifications. |
| `languages_spoken` | name (jsonb), level | Spoken languages with proficiency. |
| `contact_messages` | name, email, subject, body, ip_address, read_at | Persisted submissions from the contact form. |
| `audit_logs` | user_id, action, subject_type, subject_id, payload, created_at | Append-only log of administrative actions. |

### 3.3 Indexing strategy

- B-tree indexes on every foreign key.
- Unique composite index on `(slug, locale)` for projects.
- Indexes on `featured` and `order` columns.
- GIN indexes (`jsonb_path_ops`) on translatable columns that need search.

---

## 4. Frontend & Design System

### 4.1 Two visual languages

The project intentionally maintains two separate visual layers:

- **Public site** — custom Blade design system in `resources/views/components/`, composed with Flux primitives. Owns the brand identity.
- **Filament panel** — Filament's default design system, customized only via panel colors and logo.

Components from one layer must not leak into the other.

### 4.2 Public component system

- **Atoms:** button, badge, icon, link, input (often Flux-based).
- **Molecules:** card, navbar item, language switcher, breadcrumb.
- **Organisms:** project card grid, experience timeline, résumé section.
- **Layouts:** public layout (`resources/views/components/layouts/public.blade.php`), Filament-provided admin layout, print layout.

### 4.3 Theming

- Light and dark themes via Tailwind 4's CSS-first `@theme` block in `resources/css/app.css`.
- Brand palette declared as `--color-brand-*` (mirrored by Filament's `Color::Amber` — change both together when rebranding).
- Theme preference stored in a `theme` cookie with a system-aware default.
- All components must remain legible at WCAG 2.1 AA contrast in both themes.

### 4.4 Responsiveness

Mobile-first. Breakpoints follow Tailwind defaults: `sm` 640, `md` 768, `lg` 1024, `xl` 1280, `2xl` 1536. Critical interactions (navigation, language switcher, contact form) must be fully usable on a 360 px-wide viewport.

---

## 5. Performance targets

| Metric | Target |
| --- | --- |
| Largest Contentful Paint (LCP) | < 2.0 s on 4G |
| Cumulative Layout Shift (CLS) | < 0.05 |
| Interaction to Next Paint (INP) | < 200 ms |
| Time to First Byte (TTFB) | < 400 ms (cached) / < 800 ms (cold) |
| Lighthouse Performance score | ≥ 95 on the homepage |
| Public CSS budget | ≤ 50 KB gzipped |

The `/admin` panel is exempt from these targets.

**Core techniques**

- Route, config, view, and event caches enabled in production (`php artisan optimize`).
- HTTP cache headers on static assets (immutable, long max-age) via Vite content hashing.
- Page-level fragment caching for the homepage and projects index, invalidated on relevant model events.
- Eager-loading discipline: every Eloquent query reviewed before merge to avoid N+1 issues.
- Resume PDF generation runs in a queue worker, never inside an HTTP request.
- Images served in modern formats (WebP/AVIF) with responsive `srcset`.
- Critical CSS inlined for the public layout.
- Livewire components limited to what truly needs reactivity.

---

## 6. Security

### 6.1 Authentication & authorization

- Authentication and MFA handled exclusively by Filament (see §2.3).
- Session cookies marked `HttpOnly`, `Secure`, `SameSite=Lax`.
- Password hashing uses bcrypt with a cost factor reviewed annually.
- Authorization through Laravel policies, even with a single role (administrator).

### 6.2 Application hardening

- CSRF tokens on every state-changing request (Laravel default).
- Input validation on every form via Form Request classes.
- Output escaping by default in Blade.
- File uploads limited to whitelisted MIME types and reprocessed before storage.
- Rate limiting on login, contact form, and résumé download endpoints.
- Strict Content Security Policy and security headers (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy) at the Nginx and middleware levels.

### 6.3 Secrets & configuration

- Application secrets stored in `.env` files outside version control.
- Production secrets injected via the host's secret manager.
- `APP_KEY` rotated only with a documented procedure to avoid invalidating encrypted columns.

### 6.4 Backups & recovery

- Daily database dumps shipped to off-site storage with 14-day retention.
- Media files mirrored to a versioned object storage bucket.
- Documented restore procedure, exercised at least once in staging.

---

## 7. Non-Functional Requirements

### 7.1 Accessibility

- Target compliance level: WCAG 2.1 AA.
- Semantic HTML, proper heading hierarchy, ARIA only where semantic HTML falls short.
- Full keyboard navigation, including the language switcher and Filament panel.
- Visible focus styles on every interactive element.
- Alt text required on every uploaded image (enforced as a Filament form-level rule).

### 7.2 SEO

- Per-locale meta titles, descriptions, and Open Graph tags managed from the backoffice.
- Auto-generated multilingual sitemap submitted to search engines.
- Structured data (JSON-LD) for `Person` on the homepage and `CreativeWork` on project detail pages.
- Clean, human-readable URLs in both locales.

### 7.3 Maintainability

- PSR-12 enforced by Pint (`vendor/bin/pint --dirty --format agent`).
- Static analysis via Larastan at level 6 or higher.
- Conventional commits, single-trunk Git workflow with short-lived feature branches.
- Documentation maintained alongside the code.
- Claude Code is the primary development assistant; project memory lives in `CLAUDE.md` and `docs/`.

### 7.4 Compatibility

- **Browsers:** latest two stable versions of Chrome, Firefox, Safari, and Edge.
- **Mobile:** iOS Safari 16+, current Android Chrome.
- **Server:** Linux, PHP 8.5.

---

## 8. Testing Strategy

- **Unit tests** for domain logic (résumé builder, locale resolver, value objects).
- **Feature tests** covering HTTP routes, authentication, CRUD flows, résumé generation.
- **Browser tests** (Pest 4 browser plugin) for critical end-to-end paths: login, project creation, résumé download, language switching.

**Tooling**

- Pest 4 on top of PHPUnit 12.
- Tests created with `php artisan make:test --pest <Name>` (no `Feature/` prefix in the name).
- Run with `php artisan test --compact` (filter via `--filter=<Name>`).
- Laravel's HTTP, mail, queue, and storage fakes used to keep tests fast and deterministic.
- Database refreshed between tests using SQLite in-memory or a dedicated test database.
- Filament resources tested via `Filament\Testing` helpers.

**Quality gates**

- ≥ 80% line coverage on `app/`.
- Pint, PHPStan (level 6), and the full Pest suite must pass before any merge to `main`.
- CI runs on every push and pull request.

---

## 9. Deployment & Infrastructure

### 9.1 Environments

- **Local** — Docker compose stack (`app`, `nginx`, `postgres:16-alpine`, `redis:7-alpine`, `node:22-alpine`, `mailpit`).
- **Staging** — production mirror used for QA and migration rehearsals.
- **Production** — public environment with monitoring, backups, and zero-downtime deployments.

### 9.2 CI/CD

1. Push to a feature branch → GitHub Actions runs Pint, PHPStan, Pest.
2. Merge to `main` → build job produces optimized Vite assets and a production image.
3. Deployment job ships the new release to staging automatically and to production after manual approval.
4. Migrations and cache warmups are part of the deployment script with a defined rollback path.

### 9.3 Hosting

- Docker-based deployment on a VPS (compose) — orchestrator and reverse-proxy choice deferred (see roadmap).
- HTTPS terminated at the load balancer or reverse proxy with automatic certificate renewal.
- CDN in front of static assets and media.

### 9.4 Observability

- Error tracking via Sentry or Flare.
- Queue health monitored via Laravel Horizon (production) and Pail (local).
- Structured logs shipped to a central log store (Logtail, CloudWatch, or Loki).
- External uptime probes per environment.
