# AGENTS.md

Compact onboarding for AI agents. Every item answers: "would an agent miss this without help?"

## Stack

Laravel 13 · PHP 8.3+ · Filament 5 · Livewire 4 · Flux v2 · Tailwind 4 · PostgreSQL 16.
Single application, no monorepo.

---

## Key Commands

```bash
# First-time setup (install, .env, key, migrate, npm install, npm build)
composer run setup

# Start full dev stack (server + queue + Vite + pail) via concurrently
composer run dev

# Build assets (required before tests)
npm run build

# Run all tests
php artisan test --compact

# Run filtered tests
php artisan test --compact --filter=TestName

# Format PHP (auto-fix, must pass pre-commit)
vendor/bin/pint

# Static analysis (level 6, covers app/ and database/)
composer run stan

# Full local CI check (config:clear + lint:check + stan + test)
composer run ci:check
```

---

## Required Command Ordering

1. `npm run build` **must run before the test suite** — Vite manifest is required even for Feature tests; missing it throws `ViteException`.
2. `composer install` wires git hooks (`core.hooksPath .githooks`). Re-run after cloning if hooks aren't firing.
3. `php artisan filament:upgrade` runs automatically on every `composer install` via `post-autoload-dump`. Never suppress it.

---

## Pre-commit Hook

`.githooks/pre-commit` collects staged `.php` files and runs `vendor/bin/pint --test`. It aborts on format violations — it does **not** auto-fix. Run `vendor/bin/pint` manually before committing.

---

## Testing

| Concern | Detail |
|---|---|
| Local DB | SQLite `:memory:` (phpunit.xml override) |
| CI DB | PostgreSQL 16 — JSON column queries and casts may differ |
| Coverage gate | 80% minimum enforced in CI (`--coverage --min=80`) |
| Browser suite | `tests/Browser/` uses Playwright; not run in standard CI |
| Preferred runner | `php artisan test --compact` (Artisan wraps Pest) |

If a test passes locally but fails in CI, suspect Postgres-specific SQL behaviour.

---

## CI Workflow

Three parallel jobs on push/PR to `main`:

1. **lint** — `pint --test` then `phpstan analyse` (no DB, no Node)
2. **tests** — Postgres 16 + Redis 7, `npm run build`, migrate, Pest with coverage gate
3. **deploy** — Docker build → GHCR, then SSH deploy behind a **manual approval gate** (`production` environment)

---

## Filament Conventions

- Panel at `/admin` with `->login(false)` — unauthenticated users redirect to Fortify's `/login`.
- **Split-schema pattern** — each Resource has sub-directories:
  ```
  app/Filament/Resources/<Entity>/<EntityResource>.php
  app/Filament/Resources/<Entity>/Schemas/<EntityForm>.php
  app/Filament/Resources/<Entity>/Tables/<EntityTable>.php
  app/Filament/Resources/<Entity>/Pages/
  ```
- Use `Filament\Schemas\Schema` namespace (v5). The legacy `Filament\Forms\Components\*` namespace is not used in new code.
- Icons use enum-based `Heroicon::OutlinedFolderOpen` style, not string names.
- Create resources via: `php artisan make:filament-resource <Model> --generate --no-interaction`.

---

## MFA — Filament, Not Fortify

> **CLAUDE.md contains outdated guidance on this point. Follow the actual code.**

The `User` model uses **Filament-native TOTP MFA** (`InteractsWithAppAuthentication`), not Fortify's `TwoFactorAuthenticatable`. The Fortify 2FA columns were dropped in a migration. Do not add Fortify's 2FA trait or re-add those columns.

---

## Database

- Translatable columns are **JSON**: `$table->json('title')` — never `string`.
- Slugs are translatable, unique per locale, generated via observer/accessor — no DB unique constraint.
- Always index foreign keys and slug columns.
- Host-side Postgres port is **5433** (not 5432); Redis host-side port is **6380**.

---

## Internationalization

- Two locales: `en`, `fr` — URL-prefixed via `mcamara/laravel-localization`.
- Route segments are translated: `lang/en/routes.php` and `lang/fr/routes.php`.
  - English: `projects`, `resume`, `contact` → French: `projets`, `cv`, `contact`.
- Routes use `LaravelLocalization::transRoute('routes.key')`.
- Translatable Eloquent fields use `Spatie\Translatable\HasTranslations` + `protected array $translatable`. No `_translations` tables.
- Every public page must emit `hreflang` tags for `en`, `fr`, and `x-default`.
- Never hardcode UI strings — always `__()`.

---

## Environment Variables (Non-obvious)

| Variable | Notes |
|---|---|
| `ADMIN_EMAIL / PASSWORD / NAME` | Seeds the single admin user |
| `TURNSTILE_SITE_KEY / SECRET_KEY` | Cloudflare Turnstile for contact form; leave empty in dev to bypass |
| `BROWSERSHOT_CHROME_PATH` | `/usr/bin/chromium-browser` inside Docker only; set to local binary outside |
| `DB_PORT_HOST` | `5433` — host-side port for Postgres container |
| `REDIS_PORT_HOST` | `6380` — host-side port for Redis container |
| `FLARE_KEY` | Flare error tracking; only needed in production |
| `R2_*` | Cloudflare R2 for database backups |

---

## Resume Engine

PDF generation runs in a **queued job** (`app/Jobs/GenerateResumePdf.php`) via Spatie Browsershot. Never render PDFs synchronously in an HTTP request. Browsershot requires Chromium — see `BROWSERSHOT_CHROME_PATH` above. Resume Blade templates live in `resources/views/resume/`.

---

## Deployment

- Docker images tagged `sha-<sha>` + `latest`, published to GHCR.
- Production: `docker-compose.prod.yml`; dev: `docker-compose.yml`.
- Caddy handles TLS; set `DOMAIN` in `.env.prod`.
- Rollback by redeploying a prior `sha-<tag>` image.
- Required GitHub Secrets: `DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_SSH_KEY`.

---

## Additional Instruction Sources

- `CLAUDE.md` — project conventions, dependency approval rules, scope constraints (authoritative except for the MFA section above).
- `opencode.json` — loads all skill files and Boost MCP server config.
- Skills under `.agents/skills/` and `.claude/skills/` — load via the `skill` tool before working in that domain (Filament, Livewire, Pest, Tailwind, etc.).
- Use `search-docs` (Boost MCP) before making code changes — returns version-specific docs for installed packages.
