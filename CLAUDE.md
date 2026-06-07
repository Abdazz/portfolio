<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v13
- laravel/horizon (HORIZON) - v5
- laravel/prompts (PROMPTS) - v0
- livewire/flux (FLUXUI_FREE) - v2
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>

---

# Project-Specific Guidelines

The block above is auto-generated by Laravel Boost from `composer.json` and is regenerated whenever dependencies change. **Do not edit it manually** — additions go below.

## Project Context

Bilingual (English / French) personal portfolio and resume management system. The owner edits all content through a Filament admin panel; the public site renders it in two locales; a resume engine exports PDFs and a print-friendly HTML view from the same data. Full specification at `@docs/technical_specifications.docx`. Architecture decisions at `@docs/adr/`.

## Project Dependencies — Status

Already provided by the starter kit (do not remove): `laravel/fortify`, `livewire/flux`, `livewire/livewire`, `laravel/boost`, `laravel/pail`, `laravel/sail`, `pestphp/pest`, `tailwindcss`.

To install once, with explicit user approval per the Boost rules:

```bash
composer require filament/filament
composer require filament/spatie-laravel-translatable-plugin filament/spatie-laravel-media-library-plugin
composer require spatie/laravel-translatable spatie/laravel-medialibrary
composer require spatie/browsershot spatie/laravel-sitemap
composer require mcamara/laravel-localization
composer require --dev larastan/larastan barryvdh/laravel-debugbar
```

After installing Filament: `php artisan filament:install --panels` and create the panel at `/admin`.

## Conflict Resolutions

These resolve ambiguity in the auto-generated rules above. Project-specific rules override the framework-generic guidance.

### Fortify — primary authentication

Fortify is the source of truth for authentication. Login, password reset, email verification, and two-factor authentication all go through Fortify's standard flows. The Livewire starter kit's Flux-based views handle the UI.

- The `User` model uses Fortify's `Laravel\Fortify\TwoFactorAuthenticatable` trait. **Do not** add Filament's `HasAppAuthentication` / `HasAppAuthenticationRecovery` traits — there must be exactly one 2FA path in the codebase.
- Configure Fortify features in `config/fortify.php`. Keep `Features::registration()` **disabled** — this is a single-admin site, public registration is not wanted. Keep login, password reset, email verification, and 2FA enabled.
- The Filament panel **does not** render its own login screen. Configure the panel with `->login(false)` so unauthenticated users hitting `/admin` are redirected to Fortify's `/login` instead. Filament gates access via the standard Laravel `auth` middleware.
- Password resets, profile updates, and 2FA management go through Fortify routes (`/forgot-password`, `/user/profile-information`, `/user/two-factor-authentication`) — never reimplement them inside Filament.

### Flux — public site only

`livewire/flux` is available and may be used for the public site's UI primitives (buttons, modals, form fields, navigation). **Do not use Flux components inside Filament resources** — Filament owns the admin design system. Keep the two visual languages separate.

## Project Conventions

### Internationalization

- Two locales: `en` and `fr`. Locale lives in the URL prefix (`/en/...`, `/fr/...`) via `mcamara/laravel-localization`.
- Static UI strings: `resources/lang/{locale}/*.php` and JSON files. Always use `__()` — never hardcode strings.
- Route segments are translatable via `resources/lang/{locale}/routes.php` (e.g., `projects` → `projets`).
- Translatable Eloquent fields: JSON columns + `Spatie\Translatable\HasTranslations` trait + `protected array $translatable = [...]`. **No `_translations` tables.**
- Every public page renders `hreflang` link tags for `en`, `fr`, and `x-default`.

### Filament (admin panel)

- All admin UI = Filament Resources, Pages, or Widgets under `app/Filament/`. Never hand-rolled CRUD.
- Schemas use the `Filament\Schemas\*` namespace (v4+). The legacy `Filament\Forms\Components\*` namespace is not used in new code.
- Translatable resources use `LocaleSwitcher` from the spatie-translatable plugin.
- Reordering uses `->reorderable()` on tables — no custom drag JS.
- Authentication and 2FA are handled by Fortify, not Filament — see the Fortify section above. The panel runs with `->login(false)` and relies on Laravel's `auth` middleware.
- Create resources via Artisan: `php artisan make:filament-resource <Model> --generate --no-interaction`.

### Database

- Translatable text columns: `$table->json('title')`, not `$table->string('title')`.
- Always index foreign keys, slug columns, and any column referenced in a list page's filter.
- Slugs are unique per locale; generate via a model observer or accessor.
- Use `database-schema` (Boost MCP tool) to inspect tables before writing migrations.

### Resume engine

- PDF generation runs in a queue job using Spatie Browsershot (headless Chrome). Never render PDFs synchronously inside an HTTP request.
- Resume Blade templates live in `resources/views/resume/`. Adding a template = adding a Blade view + registering it in the Filament Site Settings page.
- The same data feeds the PDF, the print-friendly HTML at `/{locale}/resume/print`, and the JSON export.

### Public site

- Server-rendered Blade. Livewire components only where genuine reactivity is needed (contact form, project filters).
- The public design system lives in `resources/views/components/`. Compose Flux primitives where useful; do not pull in Filament's admin styles.
- Mobile-first. Verify at 360px before declaring a feature done. WCAG 2.1 AA contrast in both light and dark themes.

## Project Folder Layout

```
app/Filament/Resources/    # one Resource per managed entity
app/Filament/Pages/        # custom admin pages (e.g. resume export)
app/Filament/Widgets/      # dashboard widgets
app/Livewire/              # public-site Livewire components only
app/Models/                # Eloquent models (translatable models extend HasTranslations)
app/Services/Resume/       # resume engine: renderer, PDF job, JSON exporter
app/Support/               # framework-agnostic helpers
resources/lang/{en,fr}/    # static UI translations + route translations
resources/views/components/ # Blade design system for the public site
resources/views/resume/    # resume templates
docs/                      # technical specs, ADRs, runbooks
```

## Things NOT to Do (Project Scope)
- Do not create custom admin CRUD UI — always use Filament Resources.
- Do not enable `Features::registration()` in `config/fortify.php` — single-admin site.
- Do not enable Filament's panel login. The panel must run with `->login(false)`; do not add a second auth flow alongside Fortify.
- Do not use Flux components inside Filament resources.
- Do not hardcode strings in Blade or PHP — always `__()`.
- Do not write raw SQL — Eloquent or query builder only.
- Do not generate placeholder content (lorem ipsum, fake names) in production seeders.
- Do not introduce new top-level dependencies without approval per the Boost rules — propose them first.

## Reference Documents

- `@docs/technical_specifications.docx` — full specification
- `@docs/adr/` — Architecture Decision Records

When in doubt about Laravel 13, Filament 5, Livewire 4, Pest 4, or Tailwind 4 APIs, use the Boost MCP `search-docs` tool first — it returns version-specific results for the exact installed packages. Reach for general web search only when `search-docs` returns nothing relevant.
