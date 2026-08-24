# Portfolio

Bilingual (English / French) personal portfolio and resume management system.

- **Admin panel:** Filament v5 at `/admin` (TOTP-protected)
- **Public site:** Laravel 13 + Livewire 4 + Flux v2 + Tailwind 4
- **Locales:** `/en/…` and `/fr/…` via mcamara/laravel-localization
- **Resume engine:** Browsershot PDF generation via a queued job

---

## Quick start (Docker)

```bash
cp .env.example .env
docker compose up -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

Visit <http://localhost:8080> — the public site redirects to `/en`.
Visit <http://localhost:8080/admin> — log in with the credentials from `.env` (`ADMIN_EMAIL` / `ADMIN_PASSWORD`).
Visit <http://localhost:8025> — Mailpit catches all outgoing mail.

### First-time TOTP enrolment

On first login the admin panel forces TOTP setup. Use any authenticator app (Google Authenticator, Bitwarden, 1Password) to scan the QR code.

### Vite dev server (hot reload)

The `node` service in `docker-compose.yml` runs `npm run dev` automatically. Hot reload is available at port `5173`. If you prefer to run it on the host:

```bash
npm install && npm run dev
```

---

## Development workflow

```bash
# Run the full test suite
php artisan test --compact

# Format code (run before committing)
vendor/bin/pint

# Static analysis
vendor/bin/phpstan analyse --memory-limit=512M

# Tail logs (Pail)
php artisan pail

# Monitor queues (Horizon)
php artisan horizon
# Dashboard: http://localhost:8080/horizon
```

Git hooks auto-run Pint on staged PHP files. Install them once after cloning:

```bash
composer install   # post-install-cmd wires .githooks/ automatically
```

---

## Environment variables

Key variables — see `.env.example` for the full list.

| Variable | Description |
|---|---|
| `ADMIN_EMAIL` | Seeded admin account e-mail |
| `ADMIN_PASSWORD` | Seeded admin account password |
| `TURNSTILE_SITE_KEY` | Cloudflare Turnstile — leave empty to bypass in dev/test |
| `TURNSTILE_SECRET_KEY` | Cloudflare Turnstile secret |
| `FLARE_KEY` | Flare error tracking DSN (production) |
| `R2_ENDPOINT` | Cloudflare R2 endpoint for database backups |
| `R2_BUCKET` | R2 bucket name (default: `portfolio-backups`) |
| `R2_ACCESS_KEY_ID` | R2 credentials |
| `R2_SECRET_ACCESS_KEY` | R2 credentials |
| `BACKUP_RETENTION_DAYS` | Days to keep backups (default: `14`) |
| `LOG_CHANNEL` | Set to `stderr` in production for Docker log driver |

---

## Production deployment

### Prerequisites

- A VPS with Docker and Docker Compose v2 installed
- A domain pointed at the VPS
- The following GitHub repository secrets set:
  - `DEPLOY_HOST` — VPS IP or hostname
  - `DEPLOY_USER` — SSH user
  - `DEPLOY_SSH_KEY` — private SSH key with access to the VPS
- A GitHub Environment named **`production`** with a required reviewer for the approval gate

### First deploy

```bash
# On the VPS
mkdir -p /opt/portfolio
cp .env.prod.example /opt/portfolio/.env.prod   # fill in production values
cd /opt/portfolio
docker compose -f docker-compose.prod.yml up -d
```

Subsequent deploys happen automatically on push to `main` (after manual approval).

### Rollback

```bash
# On the VPS — substitute the previous SHA tag
cd /opt/portfolio
IMAGE_TAG=sha-<previous> docker compose -f docker-compose.prod.yml up -d app horizon scheduler
```

The SHA tags are printed in the GitHub Actions deploy run and stored as image tags in GHCR.

### TLS

Caddy handles TLS automatically via Let's Encrypt. Set `DOMAIN` in `.env.prod` (or the Caddyfile) to your domain. Port 80 and 443 must be open on the VPS firewall.

---

## Backups

Daily `pg_dump` runs inside the `backup` container and uploads to Cloudflare R2:

- Retention: 14 days (configurable via `BACKUP_RETENTION_DAYS`)
- Script: `docker/scripts/backup.sh`

**Restore procedure:**

```bash
# Download a backup from R2
aws s3 cp s3://<bucket>/postgres/<timestamp>.sql.gz /tmp/restore.sql.gz \
    --endpoint-url <R2_ENDPOINT>

# Restore into the running Postgres container
gunzip -c /tmp/restore.sql.gz | \
    docker compose -f docker-compose.prod.yml exec -T postgres \
    psql -U "$DB_USERNAME" -d "$DB_DATABASE"
```

Run a restore drill on staging before going to production and after every major infrastructure change.

---

## Observability

| Tool | URL | Notes |
|---|---|---|
| Horizon | `/horizon` | Queue monitoring; guarded by `Gate::define('viewHorizon')` |
| Flare | flare.laravel.com | Error tracking; set `FLARE_KEY` in production |
| Mailpit | `http://localhost:8025` | Dev mail catcher |
| Caddy access log | `/data/access.log` inside the `caddy` container | Rolls at 100 MB, keeps 5 files |

Container stdout/stderr flows to Docker's log driver (journald in production). Aggregate with Loki or your preferred log shipper.

---

## Maintenance

### Monthly dependency review

Run on the first Monday of every month:

```bash
composer outdated --direct
npm outdated
```

Renovate opens PRs automatically (grouped by ecosystem) every Monday before 06:00 UTC. Review and merge after reading the changelog for each bump. Never auto-merge PHP or Docker updates without testing.

**Chromium note:** The Chromium version is pinned via `ARG CHROMIUM_VERSION` in the `Dockerfile` and tracked by Renovate's `regexManagers` against the Alpine package index (`alpine_3_21/chromium`). When a Renovate PR bumps the PHP base image to a new Alpine minor (e.g., `alpine3.22`), update the `depName` in the Renovate comment directive and the `ARG` default value in the same PR — they must stay in sync.

### Annual Filament upgrade

Filament follows its own major-version cadence. Schedule one working day per year to:

1. Read the [Filament upgrade guide](https://filamentphp.com/docs/upgrade-guide) for the new major.
2. Bump `filament/filament` in `composer.json` and run `composer update filament/filament --with-dependencies`.
3. Run `php artisan filament:upgrade` if the upgrade guide specifies it.
4. Run `php artisan test --compact` — fix any failures before merging.

### Security patches

Apply security-tagged Renovate PRs immediately, without waiting for the Monday window.

---

## Project structure

```
app/Filament/          # Admin resources, pages, widgets
app/Http/Controllers/  # Public-site controllers
app/Http/Middleware/   # SecurityHeaders, EnsureMfaIsEnrolled
app/Jobs/              # GenerateResumePdf (queued)
app/Livewire/          # Public-site Livewire components
app/Models/            # Eloquent models (all translatable models use HasTranslations)
app/Observers/         # Cache-invalidation + audit-log observers
app/Services/Resume/   # ResumeBuilder DTO, TemplateRegistry, BrowsershotPdfRenderer
app/Support/           # SpatieTranslatableContentDriver, helpers
docker/                # Nginx config, Caddy config, PHP opcache.ini, backup script
docs/                  # Technical spec, ADRs, roadmap
resources/lang/        # Static UI strings (en/fr) + route translations
resources/views/       # Blade templates — public site + resume templates
tests/                 # Pest feature + unit tests
```

---

## Licence

Private — all rights reserved.
