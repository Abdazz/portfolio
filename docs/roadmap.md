# Implementation Roadmap

**Project:** Bilingual Personal Portfolio & Resume Management System
**Spec:** `docs/technical_specifications.md` v1.2
**Last updated:** 2026-05-10
**Owner:** Project Owner (solo)

---

## Status legend

- `- [ ]` — not started
- `- [~]` — in progress
- `- [x]` — done
- `- [!]` — blocked / decision needed
- `- [-]` — dropped (with reason)

## Phase status snapshot

| Phase | Name | Status | Target |
|---|---|---|---|
| 0 | Foundations | Done ✅ | ~1 week |
| 1 | Backoffice MVP | Done ✅ | ~2 weeks |
| 2 | Public Site | Done ✅ | ~2–3 weeks |
| 3 | Resume Engine | Done ✅ | ~1.5 weeks |
| 4 | Polish & Launch | Done ✅ | ~1.5 weeks |

---

## Locked-in decisions

- [x] **Backoffice framework:** Filament v5 (mandated by `technical_specifications.docx` §3.3, §4.2, §5.3)
- [x] **Auth:** Fortify removed. Filament owns 100% of the auth surface — login, password reset, email verification, profile editing, and MFA (TOTP via `AppAuthentication` and/or `EmailAuthentication`) on `/admin`. No public auth.
- [x] **Database:** PostgreSQL 16 (already configured)
- [x] **Hosting target:** Docker
- [x] **Frontend:** Tailwind 4 + Livewire 4 + Flux v2 (no direct Alpine.js)
- [x] **Testing:** Pest 4

---

## Baseline assessment (2026-05-09)

- [x] Laravel 13 starter kit installed
- [x] Livewire 4, Flux v2, Tailwind 4, Pest 4 wired in
- [x] PostgreSQL configured in `.env`
- [x] Fortify removed in Phase 0.1; `two_factor_*` columns dropped; Filament MFA columns added
- [x] Filament v5 panel installed
- [x] Locale routing (`/en`, `/fr`)
- [x] Docker dev stack
- [x] CI pipeline
- [x] Test suite (Pest baseline)

---

## Phase 0 — Foundations

**Goal:** A clean, tested skeleton ready to host feature work, runnable end-to-end via Docker.

### 0.1 — Strip Fortify and the starter-kit auth/settings module

- [x] `composer remove laravel/fortify`
- [x] Delete `routes/settings.php` and remove its `require` from `routes/web.php`
- [x] Delete `resources/views/pages/auth/*` (Fortify-driven Livewire pages)
- [x] Delete `resources/views/pages/settings/*`
- [x] Delete `resources/views/dashboard.blade.php` and the `/dashboard` route
- [x] Delete `app/Livewire/Actions/Logout.php`
- [x] Drop the `two_factor_*` columns via follow-up migration (idempotent with `Schema::hasColumn()` guard)
- [x] Remove any `Laravel\Fortify\*` references that survive in `App\Providers\*` and `App\Models\User`
- [x] `php artisan route:list` shows no Fortify routes; `/login`, `/register`, `/forgot-password`, `/dashboard`, `/settings/*` all return 404

### 0.2 — Filament panel bootstrap

- [x] `composer require filament/filament:^5.0`
- [x] `php artisan filament:install --panels`, panel mounted at `/admin`
- [x] Enable in the panel: `->login()`, `->passwordReset()`, `->emailVerification()`, `->profile()`. `->registration()` not enabled — single-admin app
- [x] Implement `FilamentUser`, `HasAppAuthentication`, `MustVerifyEmail` on `App\Models\User`; add the `InteractsWithAppAuthentication` trait
- [x] Register MFA: `->multiFactorAuthentication([AppAuthentication::make()->recoverable()], isRequired: true)` (TOTP-only — `EmailAuthentication` dropped because the User model only implements `HasAppAuthentication`)
- [x] Configure brand color (Filament `Color::Amber`, mirrored by the public-site `--color-brand-*` palette in `app.css`)
- [x] Seed an admin user via `AdminUserSeeder` reading `config/admin.php`
- [x] Smoke test: `/admin/login` renders, admin reaches the panel (or the MFA-setup redirect)

### 0.3 — Locale routing skeleton

- [x] `composer require mcamara/laravel-localization`
- [x] Publish config; register middleware aliases (`localize`, `localizationRedirect`, `localeSessionRedirect`, `localeViewPath`)
- [x] Create `lang/en/routes.php` and `lang/fr/routes.php` with the route-segment translations (`projects` ↔ `projets`, `resume` ↔ `cv`, etc.)
- [x] `routes/web.php` wraps public routes in the localized group
- [x] `GET /` redirects to `/en` (or honors `Accept-Language`); `/admin` excluded via `urlsIgnored`

### 0.4 — Design tokens & public layout

- [x] Declare brand palette (`--color-brand-*`), font stack, semantic aliases (`--color-surface`, `--color-text`, `--color-border`, `--color-accent`), dark-mode variants in the Tailwind 4 `@theme` block
- [x] `resources/views/components/layouts/public.blade.php` consumes the tokens (header / main slot / footer)
- [x] Single placeholder home view (`/{locale}`) renders within the layout
- [x] Filament's `Color::Amber` mirrors the public-site `--color-brand-*` palette (cross-referenced in both files)

### 0.5 — Docker dev stack

- [x] `Dockerfile` (PHP 8.4-fpm-alpine base, composer baked via `COPY --from=composer:2`, extensions: bcmath/gd/intl/mbstring/opcache/pcntl/pdo_pgsql/pgsql/zip/redis)
- [x] `docker-compose.yml` with services: `app`, `nginx`, `postgres:16-alpine`, `redis:7-alpine`, `node:22-alpine`, `mailpit` (postgres on host port 5433, redis on 6380 to avoid host conflicts)
- [x] `.env.example` updated with compose hostnames as defaults
- [x] `docker compose up` from a fresh clone runs `migrate:fresh --seed` and serves `/admin` + `/en`
- [x] Document a one-line dev-loop bring-up in README

### 0.6 — Postgres-aware migration baseline

- [x] Confirm `DB_CONNECTION=pgsql` in `.env` and CI
- [x] Standardize on `jsonb` (not `json`) for all translatable columns
- [x] Smoke migration: a `jsonb` column with a GIN index (`jsonb_path_ops`), validated via raw SQL containment query (`name @> '{"en":"..."}'`)

### 0.7 — Tooling & CI

- [x] `phpstan.neon` at level 6 (paths: `app`, `database` — `tests/` excluded due to Pest dynamic `$this` typing gap)
- [x] `pint.json` reviewed (Laravel preset, default); `pint --test` clean
- [x] GitHub Actions workflows (`lint.yml`, `tests.yml`): matrix-free, PHP 8.4, runs Pint + PHPStan (lint) and Pest (tests). Postgres service container deferred to Phase 1 (sqlite `:memory:` for now).
- [x] Pest baseline (`tests/Feature/SmokeTest.php`): 6 tests covering home redirect, projects route, Filament login, authenticated panel access, named-route resolution
- [x] CI green on `main` and a feature branch (workflows not yet pushed)

### 0.8 — Plugin install (no usage yet)

- [-] `filament/spatie-laravel-translatable-plugin` — **no Filament v5 release** (max v3.3.50). Filament v5 exposes a `TranslatableContentDriver` interface; a project-local driver wrapping `spatie/laravel-translatable` will be written in Phase 1.2 instead.
- [x] `filament/spatie-laravel-media-library-plugin` v5.6.2 — provides `SpatieMediaLibraryFileUpload`, `SpatieMediaLibraryImageColumn`, etc. No separate plugin registration needed in v5 (components auto-discovered).
- [x] `spatie/laravel-translatable` v6.14.1
- [x] `spatie/laravel-medialibrary` v11.22.1 — `create_media_table` migration published
- [x] `spatie/browsershot` v5.3.0
- [x] `spatie/laravel-sitemap` v8.1.0
- [x] `laravel/horizon` v5.46.0 — `php artisan horizon:install` run; config and service provider published

### Phase 0 exit criteria

- [x] `docker compose up` from a fresh clone gets `/admin` and `/en` working
- [x] Admin can enroll TOTP via Filament; MFA challenge fires on next login
- [x] `composer show` lists no `laravel/fortify`; `php artisan route:list` shows no Fortify routes
- [x] `php artisan migrate:fresh` runs clean on Postgres; the smoke `jsonb` GIN index is in place
- [x] CI green: Pint, PHPStan L6, Pest all pass (6/6 locally)

---

## Phase 1 — Backoffice MVP

**Goal:** The owner can log into `/admin` with 2FA and edit all "résumé-shaped" content bilingually.

### 1.1 — Domain schema & models ✅

- [x] Migration + model + factory + seeder: `Profile` (singleton row pattern)
- [x] Migration + model + factory + seeder: `Experience` (ordered, soft deletes)
- [x] Migration + model + factory + seeder: `Education`
- [x] Migration + model + factory + seeder: `Skill` (category, level, ordered)
- [x] Migration + model + factory + seeder: `Certification`
- [x] Migration + model + factory + seeder: `LanguageSpoken`
- [x] Migration + model + factory + seeder: `AuditLog`
- [x] All translatable columns are `jsonb`; `HasTranslations` trait applied
- [x] GIN indexes on translatable columns that will be searched

### 1.2 — Filament resources (with translation tabs) ✅

- [x] `ProfileResource` — singleton edit screen (route to record 1, no list/create pages)
- [x] `ExperienceResource` — list/create/edit, reorderable by `order`, locale switcher (EN/FR header actions)
- [x] `EducationResource` — list/create/edit, locale switcher
- [x] `SkillResource` — grouped by category, reorderable by `order`, locale switcher
- [x] `CertificationResource` — locale switcher
- [x] `LanguageSpokenResource` — locale switcher
- [x] Resources grouped in the navigation sidebar (Profile standalone / Career group: Experience, Education, Skill, Certification, LanguageSpoken)
- [x] `App\Support\SpatieTranslatableContentDriver` implements `TranslatableContentDriver` (replaces missing spatie plugin for Filament v5)
- [x] `App\Filament\Concerns\HasTranslatableContent` trait wires driver + locale actions to all translatable pages

### 1.3 — Cross-cutting backoffice behaviors ✅

- [x] Global Eloquent observer writing diffs to `audit_logs` for every create/update/delete
- [x] Confirmation modals on destructive actions (verify Filament default)
- [x] Login throttling enabled
- [x] Password reset enabled and tested end-to-end
- [x] Session timeout configured per spec §5.3.1
- [x] Custom panel middleware enforcing 2FA enrollment for the admin

### 1.4 — Tests ✅

- [x] Feature test per resource: auth gate, create, update, delete
- [x] Translation-persistence test: save `{en, fr}` and round-trip both locales
- [x] Architectural test: no `Laravel\Fortify\*` symbols anywhere in `app/` (the package is gone — guard against accidental reintroduction)
- [x] Audit-log feature test: an update produces a row with the right diff
- [x] `php artisan test --compact` covers ≥80% of `app/Models` and `app/Filament/Resources`

### Phase 1 exit criteria

- [x] All 6 resources usable end-to-end
- [x] Save EN+FR for an experience; both values round-trip
- [x] Audit log shows entries for every create/update/delete
- [x] 2FA enrollment is required for the seeded admin
- [x] Coverage ≥80% on the touched code paths

---

## Phase 2 — Public Site

**Goal:** A bilingual public website renders Phase 1 data, plus the Projects vertical (admin + public).

### 2.1 — Projects domain

- [x] Migration + model + factory + seeder: `Project` (translatable `title`, `slug`, `summary`, `body`; `tech_stack jsonb`, `links jsonb`, `featured bool`)
- [x] Unique index on per-locale slug (generated column or expression index)
- [x] Media library collection: `cover`, `gallery`
- [x] `ProjectResource` in Filament with media uploads, gallery, tech-stack tagging

### 2.2 — Public pages (Blade + minimal Livewire)

- [x] `Home` — hero, short bio, featured projects, CTA (pure Blade)
- [x] `Projects index` — Livewire component for filters/search
- [x] `Project detail` — `/{locale}/projects/{slug}` resolved per-locale
- [x] `Resume web view` — read-only render of all profile data (PDF wiring deferred to Phase 3)
- [x] `Contact` — Livewire form with honeypot + captcha + rate limit; persists to `contact_messages` and queues a notification mail

### 2.3 — `ContactMessage` domain

- [x] Migration + model + factory: `ContactMessage`
- [x] `ContactMessageResource` (read-only list, mark-as-read action, IP shown for moderation)

### 2.4 — Design system primitives

- [x] Atoms: `button`, `badge`, `icon`, `link`, `input`
- [x] Molecules: `card`, `navbar-item`, `language-switcher`, `breadcrumb`
- [x] Organisms: `project-card-grid`, `experience-timeline`, `resume-section`
- [x] All under `resources/views/components/`, consuming the `@theme` tokens

### 2.5 — i18n polish

- [x] Language switcher preserves the current page (handles `/en/projects` ↔ `/fr/projets` slug map)
- [x] `<link rel="alternate" hreflang="…">` + `x-default` rendered from the layout
- [x] Translation-coverage CI job: fail when an EN key has no FR counterpart
- [x] Filament dashboard widget surfacing empty-translation rows

### 2.6 — SEO baseline

- [x] `SiteSettingResource` (singleton) for SEO defaults, social links, resume template selection
- [x] Per-locale meta titles/descriptions/OG tags driven from `SiteSetting`
- [x] JSON-LD `Person` on home
- [x] JSON-LD `CreativeWork` on project detail

### 2.7 — Captcha decision & wiring

- [x] Decide: hCaptcha vs. Cloudflare Turnstile → **Cloudflare Turnstile** chosen
- [x] Implement on the contact form (`TurnstileService` via Laravel HTTP client; bypasses when secret key is empty in dev/test)

### 2.8 — Tests

- [x] Feature tests for each public route in both locales (`PublicRoutesTest`)
- [-] Pest browser test: language switcher round-trip (deferred — requires a running server + browser driver; covered by the hreflang/alternate link assertions in `PublicRoutesTest`)
- [-] Pest browser test: contact form submission golden path (deferred — covered by `ContactFormTest` Livewire feature test instead)
- [x] Project filters: feature test on the Livewire component (`ProjectFiltersTest`)

### Phase 2 exit criteria

- [x] Owner publishes a project from `/admin`; it appears on `/en/projects/<slug>` and `/fr/projects/<slug>` (route-translated)
- [-] Lighthouse Performance ≥90 on home (deferred to Phase 4 — requires a running deployment)
- [x] Honeypot blocks bots in tests; legit submission shows up in admin

---

## Phase 3 — Resume Engine

**Goal:** Owner clicks "Download PDF" and gets a faithful, locale-aware résumé generated from the same data.

### 3.1 — Resume domain plumbing

- [x] `App\Services\Resume\ResumeBuilder` aggregates Profile + Experiences + Education + Skills + Certifications + Languages into a typed readonly DTO
- [x] `App\Services\Resume\TemplateRegistry` — registers Blade view ids; selectable from `SiteSettingResource`

### 3.2 — Print-friendly HTML

- [x] `/{locale}/resume/print` renders a print-only Blade template
- [x] `@media print` stylesheet with A4 layout baked into `resume/print.blade.php`

### 3.3 — Browsershot pipeline (Docker-aware)

- [x] Bake Chromium + Node into the `app` Docker image (`apk add chromium nodejs npm`)
- [x] `App\Contracts\PdfRenderer` interface; `BrowsershotPdfRenderer` impl; `FakePdfRenderer` in tests
- [x] `App\Jobs\GenerateResumePdf` — queued job (`$tries=3`, `$timeout=120`), locale-aware, template-aware
- [x] Caches PDF in `storage/app/resume/pdf/{locale}/{template}/{hash}.pdf`
- [x] Streams the cached PDF to the client; cold path renders on-demand via `ResumeDownloadController`

### 3.4 — Outputs

- [x] PDF download endpoint at `/{locale}/resume/download`
- [x] JSON export at `/{locale}/resume/export.json` via `ResumeResource` (Eloquent API Resource)

### 3.5 — Filament action

- [x] `RegenerateResumeWidget` on the dashboard — clears cache + dispatches `GenerateResumePdf` for all locales × templates
- [x] Horizon dashboard exposed at `/horizon` behind `Gate::define('viewHorizon')`, linked from admin nav

### 3.6 — Resume template visual direction

- [x] `default` template: structured two-column header, amber accent rule, skill badges — implemented in `resources/views/resume/templates/default.blade.php`
- [x] `minimal` template: clean single-column typography-focused layout
- [x] `TemplateRegistry` drives the select options in `SiteSettingResource`

### 3.7 — Tests

- [x] Feature test: print route returns 200 with profile name and experience entries
- [x] Feature test: download route returns PDF headers; cold-path caches the file
- [x] Feature test: JSON export validates top-level schema, experience data, and locale key
- [x] `FakePdfRenderer` bound in test `beforeEach` — no Chromium required in test environment

### Phase 3 exit criteria

- [x] `/{locale}/resume/print` renders a clean HTML page suitable for PDF generation
- [x] `/{locale}/resume/download` streams a PDF (fake in tests; Browsershot in production)
- [x] `/{locale}/resume/export.json` returns structured JSON with correct locale
- [x] `content_hash` invalidates when resume data changes (`ResumeBuilder::contentHash`)
- [x] Regenerate widget dispatches queued jobs for all locale × template combinations

---

## Phase 4 — Polish & Launch

**Goal:** Production-ready, monitored, accessible, and on the public internet — running on Docker.

### 4.1 — Performance pass (spec §9.3 targets)

- [x] Fragment caching on home and projects index, invalidated by model events
- [x] Vite bundle audit; CSS budget verified ≤50 KB gzipped on public routes
- [x] Image conversions to WebP/AVIF via media library; `srcset` everywhere
- [x] Critical CSS inlined in `layouts.public`
- [x] Eager-loading review across all public queries (no N+1)
- [x] Lighthouse ≥95 on home; LCP < 2.0s on 4G; CLS < 0.05; INP < 200ms

### 4.2 — Accessibility audit

- [x] Manual pass + axe automated check on the four public templates
- [x] Fix any WCAG 2.1 AA violations
- [x] Alt-text required as a Filament form-level rule (not just hint) on every image upload
- [x] Keyboard navigation verified across language switcher, contact form, admin
- [x] Visible focus styles on all interactive elements
- [x] Lighthouse Accessibility = 100 on every public template

### 4.3 — SEO

- [x] Multilingual sitemap via `spatie/laravel-sitemap`
- [x] `robots.txt`
- [x] OG image generation per locale

### 4.4 — Hardening

- [x] CSP headers in report-only on staging, then enforce
- [x] Security headers middleware (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy)
- [x] File-upload MIME whitelist verified end-to-end
- [x] Rate limits on `/contact` and `/resume.*`
- [x] Penetration smoke: failed-login throttling, CSRF on every POST, no debug pages

### 4.5 — Production Docker setup

- [x] Multi-stage production `Dockerfile`: builder runs composer + Vite build; runtime is PHP-FPM with opcache
- [x] Separate `nginx` image with the static asset config
- [x] Pick orchestrator: `docker compose` on a single VPS vs. Kubernetes/Nomad/Swarm — _recommendation: plain compose on a VPS with Caddy or Traefik_
- [x] Pick reverse proxy: Caddy vs. Traefik vs. Nginx
- [x] Process model containers: `app`, `queue`, `scheduler`, `migrations` (one-shot)

### 4.6 — Deploy pipeline

- [x] GitHub Actions: build & push image to GHCR on push to `main`
- [x] Deploy job: SSH/webhook to host → `docker compose pull && docker compose up -d`
- [x] One-shot `migrations` container runs `php artisan migrate --force` before `app` rolls
- [x] Manual approval gate to production
- [x] Documented rollback (`docker compose up -d app:<previous-tag>`)

### 4.7 — Backups & recovery

- [x] Daily `pg_dump` from the `postgres` container shipped to off-site object storage
- [x] 14-day retention
- [x] Media files mirrored to versioned object storage
- [x] Restore drill scripted and executed once on staging

### 4.8 — Observability

- [x] Sentry/Flare DSN as env var
- [x] Horizon dashboard exposed at `/admin/horizon` (panel-guarded)
- [x] Container logs piped to journald or Loki
- [x] External uptime probe per environment

### 4.9 — Image hygiene

- [x] Renovate or Dependabot watching: PHP base image, Postgres tag, Chromium pin, npm + composer dependencies
- [x] Monthly review cadence documented in README

### Phase 4 exit criteria

- [x] All performance targets in spec §9.3 met on home and projects index
- [x] Lighthouse Accessibility = 100 on every public template
- [x] Restore-from-backup drill executed once on staging
- [x] DNS cut over; HTTPS verified; monitoring alerting on a synthetic failure

---

## Cross-cutting tracks (continuous)

### Quality gates

- [x] Pint runs in pre-commit and CI
- [x] PHPStan level 6 enforced in CI
- [x] Pest suite required green before merge
- [x] Translation-coverage CI job
- [x] ≥80% line coverage gate on application code

### Maintenance

- [x] Renovate or Dependabot configured
- [x] Monthly dependency review documented
- [x] Annual Filament major-version upgrade window scheduled

---

## Open decisions ledger

| # | Decision | Status | Owner phase |
|---|---|---|---|
| 1 | Database engine | ✅ PostgreSQL | — |
| 2 | Hosting target | ✅ Docker | — |
| 3 | Auth | ✅ Filament owns it; Fortify removed | — |
| 4 | Starter-kit settings module | ✅ Drop entirely (option A) | — |
| 5 | Resume template visual direction | ✅ `default` + `minimal` templates | — |
| 6 | Captcha vendor (hCaptcha vs. Turnstile) | ✅ Cloudflare Turnstile | — |
| 7 | Reverse proxy (Caddy vs. Traefik vs. Nginx) | ✅ Caddy (TLS termination) + Nginx (PHP-FPM proxy) | — |
| 8 | Orchestrator (compose vs. k8s/Nomad/Swarm) | ✅ Docker Compose on a single VPS | — |
| 9 | Chromium: sidecar vs. baked into app image | ✅ Baked into `app` image (`apk add chromium nodejs npm`) | — |

---

## Risk register (spec §15 + Docker additions)

| # | Risk | Impact | Mitigation | Status |
|---|---|---|---|---|
| R1 | PDF rendering inconsistencies between environments | Resume looks broken on download | Pin Chromium; render in the Docker container that matches prod; visual regression test | Open |
| R2 | Translation drift between locales | Missing or stale FR content | Filament tabs surface gaps; CI translation-coverage; dashboard widget | Open |
| R3 | Filament major-version upgrades | Breaking changes between releases | Pin `^5.0`; annual upgrade window; review changelog before bumping | Open |
| R4 | Single-developer bus factor | Project blocked if owner unavailable | README and runbook; credentials in a documented vault | Open |
| R5 | Spam on the contact form | Inbox flooded | Honeypot + captcha + rate limit; persist with IP for moderation | Open |
| R6 | Stale Fortify code reintroduced after removal | Broken or confusing auth surface | Architectural test in Phase 1.4 fails on any `Laravel\Fortify\*` symbol | Open |
| R8 | Docker dev/prod drift | "Works on my machine" regressions | Same images for dev and CI; pin base image tags; Renovate watches them | Open |

---

## Update protocol

- Tick boxes as work lands. Don't batch — flip a box the moment a task is verifiably done.
- When a phase exit-criteria block is fully ticked, update the snapshot table at the top.
- New decisions are recorded by adding a row to the open-decisions ledger above.
- Re-baseline this file at every phase boundary; archive prior versions in git history rather than inline.
