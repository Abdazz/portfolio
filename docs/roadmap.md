# Implementation Roadmap

**Project:** Bilingual Personal Portfolio & Resume Management System
**Spec:** `docs/technical_specifications.docx` v1.1
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
| 0 | Foundations | Not started | ~1 week |
| 1 | Backoffice MVP | Not started | ~2 weeks |
| 2 | Public Site | Not started | ~2–3 weeks |
| 3 | Resume Engine | Not started | ~1.5 weeks |
| 4 | Polish & Launch | Not started | ~1.5 weeks |

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
- [x] Fortify installed (will be removed in Phase 0); `two_factor_*` columns migrated on `users`
- [ ] Filament v5 panel installed
- [ ] Locale routing (`/en`, `/fr`)
- [ ] Domain models beyond `User`
- [ ] Translatable columns + JSON storage strategy
- [ ] Docker dev stack
- [ ] CI pipeline
- [ ] Test suite (any tests at all)

---

## Phase 0 — Foundations

**Goal:** A clean, tested skeleton ready to host feature work, runnable end-to-end via Docker.

### 0.1 — Strip Fortify and the starter-kit auth/settings module

- [ ] `composer remove laravel/fortify`
- [ ] Delete `routes/settings.php` and remove its `require` from `routes/web.php`
- [ ] Delete `resources/views/pages/auth/*` (Fortify-driven Livewire pages)
- [ ] Delete `resources/views/pages/settings/*`
- [ ] Delete `resources/views/dashboard.blade.php` and the `/dashboard` route
- [ ] Delete `app/Livewire/Actions/Logout.php`
- [ ] Drop the `two_factor_*` columns added by `2025_08_14_170933_add_two_factor_columns_to_users_table.php` (Filament's MFA stores its secrets in its own table); decide whether to revert the migration or write a follow-up that drops the columns — pick one and apply
- [ ] Remove any `Laravel\Fortify\*` references that survive in `App\Providers\*` and `App\Models\User`
- [ ] `php artisan route:list` shows no Fortify routes; `/login`, `/register`, `/forgot-password`, `/dashboard`, `/settings/*` all return 404

### 0.2 — Filament panel bootstrap

- [ ] `composer require filament/filament:^5.0`
- [ ] `php artisan filament:install --panels`, panel mounted at `/admin`
- [ ] Enable in the panel: `->login()`, `->passwordReset()`, `->emailVerification()`, `->profile()`. **Do not** enable `->registration()` — single-admin app
- [ ] Implement `FilamentUser`, `HasAppAuthentication`, `MustVerifyEmail` on `App\Models\User`; add the `InteractsWithAppAuthentication` trait
- [ ] Register MFA: `->multiFactorAuthentication([AppAuthentication::make(), EmailAuthentication::make()])` and require it via panel config
- [ ] Configure brand color and panel logo placeholder
- [ ] Seed an admin user via a dedicated seeder (factory + seeder)
- [ ] Smoke test: `/admin/login` renders, admin can enroll TOTP, MFA challenge fires on next login

### 0.3 — Locale routing skeleton

- [ ] `composer require mcamara/laravel-localization`
- [ ] Publish config; register middleware groups
- [ ] Create `resources/lang/en/routes.php` and `resources/lang/fr/routes.php` with the route-segment translations (`projects` ↔ `projets`, `resume` ↔ `cv`, etc.)
- [ ] `routes/web.php` wraps public routes in the localized group
- [ ] `GET /` redirects to `/en` (or honors `Accept-Language`)

### 0.4 — Design tokens & public layout

- [ ] Declare brand palette, font stack, spacing scale, dark-mode tokens in the Tailwind 4 `@theme` block
- [ ] `resources/views/layouts/public.blade.php` consumes the tokens
- [ ] Single placeholder home view (`/{locale}`) renders within the layout
- [ ] Verify Filament's brand color reuses the same token for visual coherence

### 0.5 — Docker dev stack

- [ ] `Dockerfile` (PHP 8.4-FPM base, multi-stage with composer + node)
- [ ] `docker-compose.yml` with services: `app`, `nginx`, `postgres:16`, `redis:7`, `node`, `mailpit`
- [ ] `.env.docker` checked in; `.env.example` updated with compose hostnames
- [ ] `docker compose up` from a fresh clone runs `migrate:fresh --seed` and serves `/admin` + `/en`
- [ ] Document a one-line dev-loop bring-up in README

### 0.6 — Postgres-aware migration baseline

- [ ] Confirm `DB_CONNECTION=pgsql` in `.env` and CI
- [ ] Standardize on `jsonb` (not `json`) for all translatable columns
- [ ] Smoke migration: a `jsonb` column with a GIN index, to validate the pattern

### 0.7 — Tooling & CI

- [ ] `phpstan.neon` at level 6
- [ ] `pint.json` reviewed; `pint --test` clean on a fresh checkout
- [ ] GitHub Actions workflow: matrix-free, runs Pint, PHPStan, Pest, with a Postgres service container matching local
- [ ] Pest baseline test: panel boots, `/en` responds 200, locale resolves
- [ ] CI green on `main` and a feature branch

### 0.8 — Plugin install (no usage yet)

- [ ] `filament/spatie-laravel-translatable-plugin`
- [ ] `filament/spatie-laravel-media-library-plugin`
- [ ] `spatie/laravel-translatable`
- [ ] `spatie/laravel-medialibrary`
- [ ] `spatie/browsershot`
- [ ] `spatie/laravel-sitemap`
- [ ] `laravel/horizon` (installed, not yet wired to the dashboard)

### Phase 0 exit criteria

- [ ] `docker compose up` from a fresh clone gets `/admin` and `/en` working
- [ ] Admin can enroll TOTP via Filament; MFA challenge fires on next login
- [ ] `composer show` lists no `laravel/fortify`; `php artisan route:list` shows no Fortify routes
- [ ] `php artisan migrate:fresh` runs clean on Postgres; the smoke `jsonb` GIN index is in place
- [ ] CI green: Pint, PHPStan L6, Pest all pass

---

## Phase 1 — Backoffice MVP

**Goal:** The owner can log into `/admin` with 2FA and edit all "résumé-shaped" content bilingually.

### 1.1 — Domain schema & models

- [ ] Migration + model + factory + seeder: `Profile` (singleton row pattern)
- [ ] Migration + model + factory + seeder: `Experience` (ordered, soft deletes)
- [ ] Migration + model + factory + seeder: `Education`
- [ ] Migration + model + factory + seeder: `Skill` (category, level, ordered)
- [ ] Migration + model + factory + seeder: `Certification`
- [ ] Migration + model + factory + seeder: `LanguageSpoken`
- [ ] Migration + model + factory + seeder: `AuditLog`
- [ ] All translatable columns are `jsonb`; `HasTranslations` trait applied
- [ ] GIN indexes on translatable columns that will be searched

### 1.2 — Filament resources (with translation tabs)

- [ ] `ProfileResource` — singleton edit screen
- [ ] `ExperienceResource` — list/create/edit, reorderable, translation tabs on `title` and `description`
- [ ] `EducationResource` — list/create/edit, translation tabs
- [ ] `SkillResource` — grouped by category, reorderable, translation tab on `name`
- [ ] `CertificationResource`
- [ ] `LanguageSpokenResource`
- [ ] Resources grouped in the navigation sidebar (Profile / Career / Portfolio / Settings)

### 1.3 — Cross-cutting backoffice behaviors

- [ ] Global Eloquent observer writing diffs to `audit_logs` for every create/update/delete
- [ ] Confirmation modals on destructive actions (verify Filament default)
- [ ] Login throttling enabled
- [ ] Password reset enabled and tested end-to-end
- [ ] Session timeout configured per spec §5.3.1
- [ ] Custom panel middleware enforcing 2FA enrollment for the admin

### 1.4 — Tests

- [ ] Feature test per resource: auth gate, create, update, delete
- [ ] Translation-persistence test: save `{en, fr}` and round-trip both locales
- [ ] Architectural test: no `Laravel\Fortify\*` symbols anywhere in `app/` (the package is gone — guard against accidental reintroduction)
- [ ] Audit-log feature test: an update produces a row with the right diff
- [ ] `php artisan test --compact` covers ≥80% of `app/Models` and `app/Filament/Resources`

### Phase 1 exit criteria

- [ ] All 6 resources usable end-to-end
- [ ] Save EN+FR for an experience; both values round-trip
- [ ] Audit log shows entries for every create/update/delete
- [ ] 2FA enrollment is required for the seeded admin
- [ ] Coverage ≥80% on the touched code paths

---

## Phase 2 — Public Site

**Goal:** A bilingual public website renders Phase 1 data, plus the Projects vertical (admin + public).

### 2.1 — Projects domain

- [ ] Migration + model + factory + seeder: `Project` (translatable `title`, `slug`, `summary`, `body`; `tech_stack jsonb`, `links jsonb`, `featured bool`)
- [ ] Unique index on per-locale slug (generated column or expression index)
- [ ] Media library collection: `cover`, `gallery`
- [ ] `ProjectResource` in Filament with media uploads, gallery, tech-stack tagging

### 2.2 — Public pages (Blade + minimal Livewire)

- [ ] `Home` — hero, short bio, featured projects, CTA (pure Blade)
- [ ] `Projects index` — Livewire component for filters/search
- [ ] `Project detail` — `/{locale}/projects/{slug}` resolved per-locale
- [ ] `Resume web view` — read-only render of all profile data (PDF wiring deferred to Phase 3)
- [ ] `Contact` — Livewire form with honeypot + captcha + rate limit; persists to `contact_messages` and queues a notification mail

### 2.3 — `ContactMessage` domain

- [ ] Migration + model + factory: `ContactMessage`
- [ ] `ContactMessageResource` (read-only list, mark-as-read action, IP shown for moderation)

### 2.4 — Design system primitives

- [ ] Atoms: `button`, `badge`, `icon`, `link`, `input`
- [ ] Molecules: `card`, `navbar-item`, `language-switcher`, `breadcrumb`
- [ ] Organisms: `project-card-grid`, `experience-timeline`, `resume-section`
- [ ] All under `resources/views/components/`, consuming the `@theme` tokens

### 2.5 — i18n polish

- [ ] Language switcher preserves the current page (handles `/en/projects` ↔ `/fr/projets` slug map)
- [ ] `<link rel="alternate" hreflang="…">` + `x-default` rendered from the layout
- [ ] Translation-coverage CI job: fail when an EN key has no FR counterpart
- [ ] Filament dashboard widget surfacing empty-translation rows

### 2.6 — SEO baseline

- [ ] `SiteSettingResource` (singleton) for SEO defaults, social links, resume template selection
- [ ] Per-locale meta titles/descriptions/OG tags driven from `SiteSetting`
- [ ] JSON-LD `Person` on home
- [ ] JSON-LD `CreativeWork` on project detail

### 2.7 — Captcha decision & wiring

- [ ] Decide: hCaptcha vs. Cloudflare Turnstile
- [ ] Implement on the contact form

### 2.8 — Tests

- [ ] Feature tests for each public route in both locales
- [ ] Pest browser test: language switcher round-trip
- [ ] Pest browser test: contact form submission (golden path + honeypot blocked path)
- [ ] Project filters: feature test on the Livewire component

### Phase 2 exit criteria

- [ ] Owner publishes a project from `/admin`; it appears on `/en/projects/<slug>` and `/fr/projects/<slug>` (route-translated)
- [ ] Lighthouse Performance ≥90 on home (final 95 target deferred to Phase 4)
- [ ] Honeypot blocks bots in tests; legit submission shows up in admin

---

## Phase 3 — Resume Engine

**Goal:** Owner clicks "Download PDF" and gets a faithful, locale-aware résumé generated from the same data.

### 3.1 — Resume domain plumbing

- [ ] `App\Services\Resume\ResumeBuilder` aggregates Profile + Experiences + Education + Skills + Certifications + Languages into a typed readonly DTO
- [ ] `App\Services\Resume\TemplateRegistry` — registers Blade view ids; selectable from `SiteSettingResource`

### 3.2 — Print-friendly HTML

- [ ] `/{locale}/resume/print` renders a print-only Blade template
- [ ] `@media print` stylesheet with A4 + US Letter variants

### 3.3 — Browsershot pipeline (Docker-aware)

- [ ] Add a `chrome` sidecar to `docker-compose.yml` (or bake Chromium into the `app` image — pick one)
- [ ] Pin Chromium version explicitly; CI image matches
- [ ] `App\Jobs\GenerateResumePdf` — queued job, locale-aware, template-aware
- [ ] Caches PDF in storage keyed by `(locale, template, content_hash)`
- [ ] Streams the cached PDF to the client; cold path renders on-demand

### 3.4 — Outputs

- [ ] PDF download endpoint (A4 default, `?paper=letter` opt-in)
- [ ] JSON export at `/{locale}/resume.json` via Eloquent API Resource
- [ ] Document the JSON schema in `docs/resume-schema.md`

### 3.5 — Filament action

- [ ] "Regenerate résumé cache" action on the dashboard (clears the cache + dispatches the job)
- [ ] Horizon dashboard exposed at `/admin/horizon` behind the panel guard

### 3.6 — Resume template visual direction

- [ ] Design spike — pick the launch template's visual direction
- [ ] Implement the chosen template under `resources/views/resume/templates/`
- [ ] Implement a stub second template to validate the registry abstraction

### 3.7 — Tests

- [ ] Feature test: queued job runs and produces a non-empty PDF with correct headers
- [ ] Snapshot test on the print-HTML output
- [ ] Pest browser visual check on the resume template (regression guard)

### Phase 3 exit criteria

- [ ] PDF downloads in <3s on warm cache, <15s on cold
- [ ] French session downloads a French résumé
- [ ] `content_hash` invalidates when an experience is edited
- [ ] JSON export validates against the documented schema

---

## Phase 4 — Polish & Launch

**Goal:** Production-ready, monitored, accessible, and on the public internet — running on Docker.

### 4.1 — Performance pass (spec §9.3 targets)

- [ ] Fragment caching on home and projects index, invalidated by model events
- [ ] Vite bundle audit; CSS budget verified ≤50 KB gzipped on public routes
- [ ] Image conversions to WebP/AVIF via media library; `srcset` everywhere
- [ ] Critical CSS inlined in `layouts.public`
- [ ] Eager-loading review across all public queries (no N+1)
- [ ] Lighthouse ≥95 on home; LCP < 2.0s on 4G; CLS < 0.05; INP < 200ms

### 4.2 — Accessibility audit

- [ ] Manual pass + axe automated check on the four public templates
- [ ] Fix any WCAG 2.1 AA violations
- [ ] Alt-text required as a Filament form-level rule (not just hint) on every image upload
- [ ] Keyboard navigation verified across language switcher, contact form, admin
- [ ] Visible focus styles on all interactive elements
- [ ] Lighthouse Accessibility = 100 on every public template

### 4.3 — SEO

- [ ] Multilingual sitemap via `spatie/laravel-sitemap`
- [ ] `robots.txt`
- [ ] OG image generation per locale

### 4.4 — Hardening

- [ ] CSP headers in report-only on staging, then enforce
- [ ] Security headers middleware (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy)
- [ ] File-upload MIME whitelist verified end-to-end
- [ ] Rate limits on `/contact` and `/resume.*`
- [ ] Penetration smoke: failed-login throttling, CSRF on every POST, no debug pages

### 4.5 — Production Docker setup

- [ ] Multi-stage production `Dockerfile`: builder runs composer + Vite build; runtime is PHP-FPM with opcache
- [ ] Separate `nginx` image with the static asset config
- [ ] Pick orchestrator: `docker compose` on a single VPS vs. Kubernetes/Nomad/Swarm — _recommendation: plain compose on a VPS with Caddy or Traefik_
- [ ] Pick reverse proxy: Caddy vs. Traefik vs. Nginx
- [ ] Process model containers: `app`, `queue`, `scheduler`, `migrations` (one-shot)

### 4.6 — Deploy pipeline

- [ ] GitHub Actions: build & push image to GHCR on push to `main`
- [ ] Deploy job: SSH/webhook to host → `docker compose pull && docker compose up -d`
- [ ] One-shot `migrations` container runs `php artisan migrate --force` before `app` rolls
- [ ] Manual approval gate to production
- [ ] Documented rollback (`docker compose up -d app:<previous-tag>`)

### 4.7 — Backups & recovery

- [ ] Daily `pg_dump` from the `postgres` container shipped to off-site object storage
- [ ] 14-day retention
- [ ] Media files mirrored to versioned object storage
- [ ] Restore drill scripted and executed once on staging

### 4.8 — Observability

- [ ] Sentry/Flare DSN as env var
- [ ] Horizon dashboard exposed at `/admin/horizon` (panel-guarded)
- [ ] Container logs piped to journald or Loki
- [ ] External uptime probe per environment

### 4.9 — Image hygiene

- [ ] Renovate or Dependabot watching: PHP base image, Postgres tag, Chromium pin, npm + composer dependencies
- [ ] Monthly review cadence documented in README

### Phase 4 exit criteria

- [ ] All performance targets in spec §9.3 met on home and projects index
- [ ] Lighthouse Accessibility = 100 on every public template
- [ ] Restore-from-backup drill executed once on staging
- [ ] DNS cut over; HTTPS verified; monitoring alerting on a synthetic failure

---

## Cross-cutting tracks (continuous)

### Quality gates

- [ ] Pint runs in pre-commit and CI
- [ ] PHPStan level 6 enforced in CI
- [ ] Pest suite required green before merge
- [ ] Translation-coverage CI job
- [ ] ≥80% line coverage gate on application code

### Maintenance

- [ ] Renovate or Dependabot configured
- [ ] Monthly dependency review documented
- [ ] Annual Filament major-version upgrade window scheduled

---

## Open decisions ledger

| # | Decision | Status | Owner phase |
|---|---|---|---|
| 1 | Database engine | ✅ PostgreSQL | — |
| 2 | Hosting target | ✅ Docker | — |
| 3 | Auth | ✅ Filament owns it; Fortify removed | — |
| 4 | Starter-kit settings module | ✅ Drop entirely (option A) | — |
| 5 | Resume template visual direction | ⏳ pending | Before Phase 3 |
| 6 | Captcha vendor (hCaptcha vs. Turnstile) | ⏳ pending | Phase 2 |
| 7 | Reverse proxy (Caddy vs. Traefik vs. Nginx) | ⏳ pending | Phase 4 |
| 8 | Orchestrator (compose vs. k8s/Nomad/Swarm) | ⏳ pending | Phase 4 |
| 9 | Chromium: sidecar vs. baked into app image | ⏳ pending | Phase 3 |

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
