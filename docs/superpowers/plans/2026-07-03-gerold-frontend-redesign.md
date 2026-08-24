# Gerold Frontend Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the public site with an adaptation of the Gerold ThemeForest template, with 5 back-office-selectable home layouts, built natively in the existing Laravel/Livewire/Tailwind-v4 stack.

**Architecture:** A shared Gerold design system (theme tokens translated from Gerold's Tailwind-v3 config into Tailwind-v4 `@theme`, Sora font, self-hosted FA Pro icons, GSAP/Lenis/Swiper motion) feeds restyled Blade section components. A `HomeLayoutRegistry` (mirroring the existing résumé `TemplateRegistry`) maps a `home_layout` setting to one of 5 composed layout views. Services/Testimonials/Blog appear only as guarded reserved slots.

**Tech Stack:** Laravel 13, Blade, Livewire 4, Flux, Tailwind v4, Pest 4, Filament 5. New JS deps: `gsap`, `lenis`, `swiper`. Self-hosted Font Awesome Pro + `flaticon_gerold` from the Gerold template.

## Global Constraints

- **Spec:** `docs/superpowers/specs/2026-07-03-gerold-frontend-redesign-design.md`.
- **Gerold source (outside repo):** `/media/abdazz/data3/web/perso/Gerold - Personal Portfolio Tailwind CSS Template/gerold/demo/` — HTML demos `index.html` (01), `index-2.html` (02), `index-3.html` (03), `index-8.html` (08), `index-10.html` (10); tokens in `../tailwind.config.js`.
- **Dark mode is the default**; light = cream (`#f6f3fc`). Toggle via existing `.dark` cookie mechanism.
- **Accent** `#8750f7` / `#9b8dff`; dark surfaces `#0f0715`/`#050709`/`#10171c`; text `#dddddd`.
- **Font:** Sora only (public site). **Icons:** self-hosted FA Pro.
- **No Gerold demo images** in the repo — real content only. Decorative shapes/SVGs allowed.
- **i18n:** every string via `__()`; add keys to `resources/lang/{en,fr}/*.php`; preserve `hreflang` and translatable routes. Two locales `en`/`fr`.
- **Out of scope:** Services/Testimonials/Blog models & data; résumé **PDF/print** templates (`resources/views/resume/print.blade.php`, `resources/views/resume/templates/*`); Filament admin visual design.
- **Every change is tested** (Pest, feature tests preferred). Run `php artisan test --compact --filter=<name>`.
- Run `vendor/bin/pint --dirty --format agent` before finalising PHP changes.
- After frontend changes run `npm run build` and confirm the Vite manifest resolves.
- `prefers-reduced-motion` must disable GSAP reveals and Lenis.

---

## Phase A — Foundation: design system, motion, layout switching

### Task A1: Install JS dependencies

**Files:**
- Modify: `package.json`

- [ ] **Step 1: Install the three motion libraries**

Run: `npm install gsap lenis swiper`
Expected: `package.json` `dependencies` gains `gsap`, `lenis`, `swiper`; `package-lock.json` updated.

- [ ] **Step 2: Verify install**

Run: `node -e "require.resolve('gsap'); require.resolve('lenis'); require.resolve('swiper'); console.log('ok')"`
Expected: prints `ok`.

- [ ] **Step 3: Commit**

```bash
git add package.json package-lock.json
git commit -m "chore: add gsap, lenis, swiper for Gerold motion"
```

---

### Task A2: Self-host Font Awesome Pro + flaticon assets

**Files:**
- Create: `public/vendor/gerold/css/font-awesome-pro.min.css`
- Create: `public/vendor/gerold/css/flaticon_gerold.css`
- Create: `public/vendor/gerold/fonts/` (FA Pro + flaticon webfonts)

- [ ] **Step 1: Copy the licensed webfont assets from the Gerold template**

```bash
GEROLD="/media/abdazz/data3/web/perso/Gerold - Personal Portfolio Tailwind CSS Template/gerold/demo/assets"
mkdir -p public/vendor/gerold/css public/vendor/gerold/fonts
cp "$GEROLD/css/font-awesome-pro.min.css" "$GEROLD/css/flaticon_gerold.css" public/vendor/gerold/css/
cp "$GEROLD/fonts/fa-"*.woff2 "$GEROLD/fonts/fa-"*.ttf public/vendor/gerold/fonts/
cp "$GEROLD/fonts/flaticon_gerold."* public/vendor/gerold/fonts/
```

- [ ] **Step 2: Fix the font URLs in the copied CSS to point at `../fonts/`**

Open both copied CSS files; ensure every `url(...)` references `../fonts/<file>` (relative to `public/vendor/gerold/css/`). Adjust any `../webfonts/` or `assets/fonts/` prefixes to `../fonts/`.

- [ ] **Step 3: Verify the fonts resolve**

Run: `ls public/vendor/gerold/fonts | head` and `grep -o "url([^)]*)" public/vendor/gerold/css/flaticon_gerold.css | head`
Expected: font files present; URLs point to `../fonts/...` files that exist.

- [ ] **Step 4: Commit**

```bash
git add public/vendor/gerold
git commit -m "chore: self-host Font Awesome Pro + flaticon from Gerold template"
```

---

### Task A3: Translate Gerold theme tokens into Tailwind v4 `@theme`

**Files:**
- Modify: `resources/css/app.css`
- Modify: `vite.config.js` (drop the `bunny('Instrument Sans')` font; Sora loads via `@import`)

**Interfaces:**
- Produces: semantic CSS variables consumed by every component — `--color-surface`, `--color-surface-muted`, `--color-text`, `--color-text-muted`, `--color-border`, `--color-accent`, `--color-accent-2` (`#9b8dff`), `--font-sans` (Sora); the `.dark` overrides; utility classes `.animate-enter`, `.gradient-primary`.

- [ ] **Step 1: Rewrite the font `@import` and `@theme` block**

In `resources/css/app.css`, replace the Google-Fonts `@import` line with Sora, and replace the `@theme` values with the Gerold palette. Keep the FA Pro / flaticon `@import`s pointing at the self-hosted CSS.

```css
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap');
@import '/vendor/gerold/css/font-awesome-pro.min.css';
@import '/vendor/gerold/css/flaticon_gerold.css';

@import 'tailwindcss';
@import '../../vendor/livewire/flux/dist/flux.css';

@source '../views';
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../vendor/livewire/flux-pro/stubs/**/*.blade.php';
@source '../../vendor/livewire/flux/stubs/**/*.blade.php';

@custom-variant dark (&:where(.dark, .dark *));

@theme {
    --font-sans: 'Sora', ui-sans-serif, system-ui, sans-serif;
    --font-mono: ui-monospace, SFMono-Regular, Menlo, monospace;

    /* Gerold purple accent */
    --color-accent: #8750f7;
    --color-accent-2: #9b8dff;
    --color-accent-deep: #2a1454;
    --color-green: #00ff2f;

    /* Light (cream) base */
    --color-surface: #f6f3fc;
    --color-surface-muted: #ece6f7;
    --color-text: #0f0715;
    --color-text-muted: #5a5170;
    --color-border: #e2dbf0;
    --color-accent-foreground: #ffffff;
}

@layer theme {
    .dark {
        --color-surface: #0f0715;
        --color-surface-muted: #140c1c;
        --color-surface-deep: #050709;
        --color-text: #dddddd;
        --color-text-muted: #7a7a7a;
        --color-border: #ffffff24;
        --color-accent-foreground: #ffffff;
    }
}
```

- [ ] **Step 2: Set the dark default and base styles**

Add, after the theme layers:

```css
@layer base {
    html { color-scheme: dark; }
    body { background: var(--color-surface); color: var(--color-text); font-family: var(--font-sans); }
    *, ::after, ::before { border-color: var(--color-border); }
}

@utility gradient-primary {
    background-image: linear-gradient(260deg, var(--color-accent-deep) 0%, var(--color-accent) 100%);
}
```

Keep the existing `@keyframes fade-in-up` / `.animate-enter*` block and the Flux field overrides from the current file.

- [ ] **Step 3: Remove the stale bunny font from vite.config.js**

In `vite.config.js`, delete the `fonts: [ bunny('Instrument Sans', ...) ]` option and the `import { bunny } ...` line.

- [ ] **Step 4: Build and verify**

Run: `npm run build`
Expected: build succeeds; `public/build/manifest.json` exists and references `app.css`.

- [ ] **Step 5: Commit**

```bash
git add resources/css/app.css vite.config.js
git commit -m "feat: translate Gerold theme tokens to Tailwind v4, Sora font, dark default"
```

---

### Task A4: Motion bootstrap (Lenis + GSAP + reduced-motion)

**Files:**
- Modify: `resources/js/app.js`

**Interfaces:**
- Produces: global scroll-reveal behaviour on elements with `[data-reveal]`; smooth scroll via Lenis; both disabled under `prefers-reduced-motion`; re-init on `livewire:navigated`.

- [ ] **Step 1: Write the motion bootstrap**

Replace the (empty) `resources/js/app.js` with:

```js
import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initMotion() {
    if (reduced) return;

    const lenis = new Lenis({ duration: 1.1, smoothWheel: true });
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);
    lenis.on('scroll', ScrollTrigger.update);

    document.querySelectorAll('[data-reveal]').forEach((el) => {
        gsap.from(el, {
            opacity: 0, y: 24, duration: 0.7, ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 85%' },
        });
    });
}

document.addEventListener('DOMContentLoaded', initMotion);
document.addEventListener('livewire:navigated', () => { ScrollTrigger.refresh(); initMotion(); });
```

- [ ] **Step 2: Build and verify no errors**

Run: `npm run build`
Expected: build succeeds, bundles gsap/lenis.

- [ ] **Step 3: Commit**

```bash
git add resources/js/app.js
git commit -m "feat: motion bootstrap with Lenis + GSAP ScrollTrigger, reduced-motion aware"
```

---

### Task A5: HomeLayoutRegistry service

**Files:**
- Create: `app/Services/Home/HomeLayoutRegistry.php`
- Test: `tests/Unit/HomeLayoutRegistryTest.php`

**Interfaces:**
- Produces:
  - `view(string $slug): string` — returns view path, falls back to default for unknown slug.
  - `selectOptions(): array<string,string>` — slug => label.
  - `slugs(): list<string>`.
  - Default slug: `gerold-01`.

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Services\Home\HomeLayoutRegistry;

it('resolves known layout slugs to view paths', function () {
    $registry = new HomeLayoutRegistry();
    expect($registry->view('gerold-01'))->toBe('home.layouts.gerold-01');
    expect($registry->view('gerold-10'))->toBe('home.layouts.gerold-10');
});

it('falls back to the default layout for unknown slugs', function () {
    $registry = new HomeLayoutRegistry();
    expect($registry->view('does-not-exist'))->toBe('home.layouts.gerold-01');
});

it('exposes the five layouts as select options', function () {
    $registry = new HomeLayoutRegistry();
    expect(array_keys($registry->selectOptions()))
        ->toBe(['gerold-01', 'gerold-02', 'gerold-03', 'gerold-08', 'gerold-10']);
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=HomeLayoutRegistry`
Expected: FAIL (class not found).

- [ ] **Step 3: Implement the registry**

```php
<?php

namespace App\Services\Home;

class HomeLayoutRegistry
{
    /** @var array<string, string> slug => view path */
    private array $layouts = [
        'gerold-01' => 'home.layouts.gerold-01',
        'gerold-02' => 'home.layouts.gerold-02',
        'gerold-03' => 'home.layouts.gerold-03',
        'gerold-08' => 'home.layouts.gerold-08',
        'gerold-10' => 'home.layouts.gerold-10',
    ];

    private string $default = 'gerold-01';

    public function view(string $slug): string
    {
        return $this->layouts[$slug] ?? $this->layouts[$this->default];
    }

    /** @return array<string, string> */
    public function selectOptions(): array
    {
        return array_combine(
            array_keys($this->layouts),
            array_map(fn (string $slug) => 'Gerold '.substr($slug, -2), array_keys($this->layouts)),
        );
    }

    /** @return list<string> */
    public function slugs(): array
    {
        return array_keys($this->layouts);
    }
}
```

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=HomeLayoutRegistry`
Expected: PASS (3 tests).

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Services/Home/HomeLayoutRegistry.php tests/Unit/HomeLayoutRegistryTest.php
git commit -m "feat: add HomeLayoutRegistry for selectable home layouts"
```

---

### Task A6: Add `home_layout` to site settings

**Files:**
- Create: `database/migrations/2026_07_03_000001_add_home_layout_to_site_settings.php`
- Modify: `app/Models/SiteSetting.php:20-28` (`$fillable`), `:55-60` (`instance()` defaults)
- Test: `tests/Feature/SiteSettingHomeLayoutTest.php`

**Interfaces:**
- Consumes: `HomeLayoutRegistry` (Task A5).
- Produces: `SiteSetting::instance()->home_layout` (string, default `gerold-01`).

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Models\SiteSetting;

it('defaults home_layout to gerold-01', function () {
    expect(SiteSetting::instance()->home_layout)->toBe('gerold-01');
});

it('persists a chosen home_layout', function () {
    $setting = SiteSetting::instance();
    $setting->update(['home_layout' => 'gerold-08']);
    expect(SiteSetting::instance()->fresh()->home_layout)->toBe('gerold-08');
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=SiteSettingHomeLayout`
Expected: FAIL (unknown column `home_layout`).

- [ ] **Step 3: Create the migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('home_layout')->default('gerold-01')->after('resume_template');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('home_layout');
        });
    }
};
```

- [ ] **Step 4: Update the model**

In `app/Models/SiteSetting.php`, add `'home_layout'` to `$fillable`, and add `'home_layout' => 'gerold-01'` to the `instance()` `firstOrCreate` defaults array.

- [ ] **Step 5: Migrate and run tests**

Run: `php artisan migrate && php artisan test --compact --filter=SiteSettingHomeLayout`
Expected: PASS (2 tests).

- [ ] **Step 6: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add database/migrations app/Models/SiteSetting.php tests/Feature/SiteSettingHomeLayoutTest.php
git commit -m "feat: add home_layout setting to SiteSetting"
```

---

### Task A7: Filament — home layout selector

**Files:**
- Modify: `app/Filament/Resources/SiteSettings/Schemas/SiteSettingForm.php`

**Interfaces:**
- Consumes: `HomeLayoutRegistry::selectOptions()`.

- [ ] **Step 1: Add the Select to the settings form**

In `SiteSettingForm.php`, add (import `App\Services\Home\HomeLayoutRegistry`) a new Section before the `Resume` section:

```php
Section::make(__('Home page'))
    ->schema([
        Select::make('home_layout')
            ->label(__('Layout'))
            ->options(fn () => app(HomeLayoutRegistry::class)->selectOptions())
            ->default('gerold-01')
            ->required(),
    ]),
```

- [ ] **Step 2: Write a feature test that the field renders**

Create `tests/Feature/Filament/SiteSettingHomeLayoutFieldTest.php`:

```php
<?php

use App\Models\User;
use function Pest\Livewire\livewire;
use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;

it('shows the home_layout select on the settings page', function () {
    $this->actingAs(User::factory()->create());
    livewire(EditSiteSetting::class)
        ->assertFormFieldExists('home_layout');
});
```

- [ ] **Step 3: Run the test**

Run: `php artisan test --compact --filter=SiteSettingHomeLayoutField`
Expected: PASS. (If `EditSiteSetting` needs a record, load `SiteSetting::instance()` first per the page's existing pattern.)

- [ ] **Step 4: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Filament/Resources/SiteSettings/Schemas/SiteSettingForm.php tests/Feature/Filament/SiteSettingHomeLayoutFieldTest.php
git commit -m "feat: add home layout selector to Filament site settings"
```

---

### Task A8: HomeController resolves the active layout + loads section data

**Files:**
- Modify: `app/Http/Controllers/HomeController.php`
- Create: `resources/views/home/layouts/gerold-01.blade.php` (temporary minimal stub — real content in Phase C)
- Test: `tests/Feature/HomeLayoutSwitchingTest.php`

**Interfaces:**
- Consumes: `HomeLayoutRegistry::view()`, `SiteSetting::instance()->home_layout`.
- Produces: the home view receives `$profile, $projects, $experiences, $skills, $certifications, $stats` where `$stats = ['projects' => int, 'years' => int, 'certifications' => int, 'languages' => int]`. Later section components (Phase B) consume these exact names.

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Models\SiteSetting;

it('renders the layout selected in settings', function () {
    SiteSetting::instance()->update(['home_layout' => 'gerold-01']);
    $this->get('/en')->assertOk()->assertViewIs('home.layouts.gerold-01');
});

it('falls back to gerold-01 for an unknown layout', function () {
    SiteSetting::instance()->update(['home_layout' => 'bogus']);
    $this->get('/en')->assertOk()->assertViewIs('home.layouts.gerold-01');
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=HomeLayoutSwitching`
Expected: FAIL (view not found / wrong view).

- [ ] **Step 3: Create a minimal layout stub**

`resources/views/home/layouts/gerold-01.blade.php`:

```blade
<x-layouts.public>
    <h1 class="sr-only">{{ $profile?->name }}</h1>
</x-layouts.public>
```

- [ ] **Step 4: Rewrite HomeController**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Services\Home\HomeLayoutRegistry;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private HomeLayoutRegistry $layouts) {}

    public function __invoke(): View
    {
        $profile = Profile::first();
        $projects = Project::orderBy('order')->with('media')->get();
        $experiences = Experience::orderByDesc('start_date')->get();
        $skills = Skill::orderBy('order')->get();
        $certifications = Certification::orderBy('order')->get();

        $stats = [
            'projects' => $projects->count(),
            'years' => max(1, now()->year - ($experiences->min('start_date')?->year ?? now()->year)),
            'certifications' => $certifications->count(),
            'languages' => \App\Models\LanguageSpoken::count(),
        ];

        $view = $this->layouts->view(SiteSetting::instance()->home_layout);

        return view($view, compact('profile', 'projects', 'experiences', 'skills', 'certifications', 'stats'));
    }
}
```

> Adjust model/column names (`start_date`, `order`, `featured`) to match the actual schema; verify with `php artisan model:show Experience` etc. before writing.

- [ ] **Step 5: Run tests, verify pass**

Run: `php artisan test --compact --filter=HomeLayoutSwitching`
Expected: PASS (2 tests).

- [ ] **Step 6: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Controllers/HomeController.php resources/views/home/layouts/gerold-01.blade.php tests/Feature/HomeLayoutSwitchingTest.php
git commit -m "feat: resolve active home layout in HomeController"
```

---

## Phase B — Shared components

> **Adaptation rule for every Phase B/C/D task:** open the named Gerold source HTML, copy the section's structure, then convert: replace hardcoded copy with `{{ __('...') }}` / model data; replace `class="..."` Gerold-v3 utilities with equivalents using our `@theme` tokens (`bg-surface`, `text-text`, `text-accent`, etc.); replace jQuery-plugin hooks with Alpine/Livewire/`data-reveal`; drop demo images. Each component is a Blade component file with an explicit `@props([...])`.

### Task B1: Restyle atoms (button, badge, link)

**Files:**
- Modify: `resources/views/components/atoms/button.blade.php`, `atoms/badge.blade.php`, `atoms/link.blade.php`
- Test: `tests/Feature/Components/AtomsRenderTest.php`

**Interfaces:**
- Produces: `<x-atoms.button variant="primary|outline" href?>`, `<x-atoms.badge>`, `<x-atoms.link>` — Gerold pill styling, gradient primary / outline variants.

- [ ] **Step 1: Write the failing test**

```php
<?php

it('renders a primary button with the gradient class', function () {
    $html = Blade::render('<x-atoms.button variant="primary">Go</x-atoms.button>');
    expect($html)->toContain('Go')->toContain('gradient-primary');
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=AtomsRender`
Expected: FAIL.

- [ ] **Step 3: Implement the button (source: any Gerold demo `.tj-primary-btn`)**

Adapt Gerold's primary button markup into `atoms/button.blade.php` using `@props(['variant' => 'primary', 'href' => null])`, a pill radius, `gradient-primary` for primary and a bordered transparent style for outline. Render `<a>` when `$href` set else `<button>`. Repeat the adaptation for `badge` and `link`.

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=AtomsRender`
Expected: PASS.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/components/atoms tests/Feature/Components/AtomsRenderTest.php
git commit -m "feat: restyle atoms to Gerold design"
```

---

### Task B2: New molecules (section-heading, stat-counter, marquee)

**Files:**
- Create: `resources/views/components/molecules/section-heading.blade.php`, `molecules/stat-counter.blade.php`, `molecules/marquee.blade.php`
- Modify: `resources/views/components/molecules/card.blade.php`, `molecules/project-card.blade.php`
- Test: `tests/Feature/Components/MoleculesRenderTest.php`

**Interfaces:**
- Produces:
  - `<x-molecules.section-heading eyebrow title />`
  - `<x-molecules.stat-counter :value="int" label />` — Alpine `x-data` counting up on `IntersectionObserver` (no odometer).
  - `<x-molecules.marquee>` — CSS-animated horizontal scroller, slot = repeated items.

- [ ] **Step 1: Write the failing test**

```php
<?php

it('renders a stat counter with its target value and label', function () {
    $html = Blade::render('<x-molecules.stat-counter :value="12" label="Projects" />');
    expect($html)->toContain('12')->toContain('Projects')->toContain('x-data');
});

it('renders a section heading with eyebrow and title', function () {
    $html = Blade::render('<x-molecules.section-heading eyebrow="Work" title="Selected Projects" />');
    expect($html)->toContain('Work')->toContain('Selected Projects');
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=MoleculesRender`
Expected: FAIL.

- [ ] **Step 3: Implement the molecules**

- `section-heading`: `@props(['eyebrow' => null, 'title'])`; eyebrow uses `text-accent uppercase tracking` styling from Gerold section titles.
- `stat-counter`: `@props(['value', 'label'])`; Alpine `x-data="{n:0}"` with an `IntersectionObserver` that tweens `n` to `{{ $value }}`; render `{{ $value }}` as accessible fallback text.
- `marquee`: `@props(['speed' => '30s'])`; a `flex` track with `animation: marquee var(--speed) linear infinite` (add the keyframe to `app.css`), duplicated slot for seamless loop; pause on `prefers-reduced-motion`.
- Restyle `card`/`project-card` to Gerold portfolio cards (rounded, hover overlay).

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=MoleculesRender`
Expected: PASS.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/components/molecules resources/css/app.css tests/Feature/Components/MoleculesRenderTest.php
git commit -m "feat: add Gerold molecules (section-heading, stat-counter, marquee) and restyle cards"
```

---

### Task B3: Public layout shell (header, footer, theme toggle, Lenis wrapper)

**Files:**
- Modify: `resources/views/components/layouts/public.blade.php`
- Test: `tests/Feature/PublicLayoutTest.php`

**Interfaces:**
- Consumes: header nav links (existing routes), language switcher, theme cookie.
- Produces: the page shell wrapping all pages; header becomes a Gerold sticky header (Alpine, hides on scroll-down / shows on scroll-up); footer restyled; `hreflang` tags preserved; `@vite` unchanged.

- [ ] **Step 1: Write the failing test**

```php
<?php

it('renders the public shell with hreflang alternates', function () {
    $this->get('/en')->assertOk()
        ->assertSee('hreflang="fr"', false)
        ->assertSee('hreflang="x-default"', false);
});
```

- [ ] **Step 2: Run it, verify current state**

Run: `php artisan test --compact --filter=PublicLayout`
Expected: PASS already if hreflang exists — this test guards against regression while restyling. If it fails, hreflang was lost; keep it.

- [ ] **Step 3: Restyle the shell (source: Gerold `index.html` header + footer)**

Adapt Gerold's header/footer markup into `public.blade.php`. Header: Alpine `x-data` with a scroll listener implementing the Headroom show/hide; nav items styled per Gerold; keep the existing `<x-molecules.language-switcher />` and theme toggle. Preserve the `hreflang` block and `@vite`/`@livewireScripts`. Do NOT introduce jQuery/headroom.js — Alpine only.

- [ ] **Step 4: Run tests + build**

Run: `php artisan test --compact --filter=PublicLayout && npm run build`
Expected: PASS + build OK.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/components/layouts/public.blade.php tests/Feature/PublicLayoutTest.php
git commit -m "feat: restyle public layout shell to Gerold (sticky header, footer)"
```

---

### Task B4: Section organisms — real-data sections

**Files:**
- Create: `resources/views/components/organisms/sections/hero.blade.php`, `about.blade.php`, `portfolio.blade.php`, `experience.blade.php`, `skills.blade.php`, `resume-cta.blade.php`, `certifications-marquee.blade.php`, `contact-cta.blade.php`, `decorative-text.blade.php`
- Test: `tests/Feature/Components/SectionsRenderTest.php`

**Interfaces (props each section consumes — names match Task A8 output):**
- `hero`: `:profile`
- `about`: `:profile :stats`
- `portfolio`: `:projects`
- `experience`: `:experiences`
- `skills`: `:skills`
- `resume-cta`: `:profile`
- `certifications-marquee`: `:certifications`
- `contact-cta`: (no props; links to contact route)
- `decorative-text`: `:text`

- [ ] **Step 1: Write the failing test (one section shown; replicate per section)**

```php
<?php

use App\Models\Certification;

it('renders the certifications marquee with each certification title', function () {
    $certs = Certification::factory()->count(3)->create();
    $html = Blade::render('<x-organisms.sections.certifications-marquee :certifications="$certs" />', ['certs' => $certs]);
    foreach ($certs as $cert) {
        expect($html)->toContain($cert->getTranslation('title', 'en'));
    }
});
```

Add one analogous assertion per section (hero shows profile name; about shows a stat value; portfolio shows a project title; experience shows a role; skills shows a skill name; resume-cta contains the résumé route; decorative-text shows the passed text).

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=SectionsRender`
Expected: FAIL.

- [ ] **Step 3: Implement each section**

For each file, adapt the matching Gerold section (per the mapping below) into a Blade component with `@props([...])` as above, tokenised classes, `data-reveal` on animated blocks, `__()` for all static copy, and real model data. Gerold source references:
- `hero` ← the hero block of `index.html`.
- `about` + stat row ← `index-2.html` about + counter area.
- `portfolio` ← portfolio/`.tj-portfolio` area of `index.html`; links to `route('projects.index')`.
- `experience` ← experience area of `index.html`; reuse existing `<x-organisms.experience-timeline>` internally if suitable.
- `skills` ← skills area of `index.html`.
- `resume-cta` ← résumé area of `index-2.html`; buttons to `route('resume')`, `route('resume.print')`, `route('resume.download')`.
- `certifications-marquee` ← brand/marquee area of `index-2.html`, using `<x-molecules.marquee>` with certification logos from `public/images/certifications/`.
- `contact-cta` ← CTA area of `index-8.html`; links to `route('contact')`.
- `decorative-text` ← the big marquee text area of `index-3.html`.

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=SectionsRender`
Expected: PASS.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/components/organisms/sections tests/Feature/Components/SectionsRenderTest.php
git commit -m "feat: add Gerold real-data section organisms"
```

---

### Task B5: Reserved-slot sections (services, testimonials, blog)

**Files:**
- Create: `resources/views/components/organisms/sections/services.blade.php`, `testimonials.blade.php`, `blog.blade.php`
- Test: `tests/Feature/Components/ReservedSlotsTest.php`

**Interfaces:**
- Produces: each renders **nothing** unless passed a non-empty `:items` collection (default `collect()`), so layouts can include them now and they light up when the future module supplies data.

- [ ] **Step 1: Write the failing test**

```php
<?php

it('reserved sections render nothing when empty', function () {
    foreach (['services', 'testimonials', 'blog'] as $s) {
        $html = Blade::render("<x-organisms.sections.$s />");
        expect(trim($html))->toBe('');
    }
});
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=ReservedSlots`
Expected: FAIL (components missing).

- [ ] **Step 3: Implement the guarded stubs**

Each file:

```blade
@props(['items' => null])
@php($items = $items ?? collect())
@if($items->isNotEmpty())
    {{-- Filled by the Services/Testimonials/Blog sub-project. --}}
@endif
```

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=ReservedSlots`
Expected: PASS.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/components/organisms/sections tests/Feature/Components/ReservedSlotsTest.php
git commit -m "feat: add guarded reserved-slot sections for future modules"
```

---

## Phase C — Home layouts

### Task C1: Compose the 5 layout views

**Files:**
- Modify: `resources/views/home/layouts/gerold-01.blade.php`
- Create: `resources/views/home/layouts/gerold-02.blade.php`, `gerold-03.blade.php`, `gerold-08.blade.php`, `gerold-10.blade.php`
- Test: `tests/Feature/HomeLayoutsContentTest.php`

**Interfaces:**
- Consumes: `$profile, $projects, $experiences, $skills, $certifications, $stats` (Task A8).

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Models\SiteSetting;
use App\Models\Project;

it('each layout renders its sections with real data', function (string $slug) {
    Project::factory()->count(2)->create();
    SiteSetting::instance()->update(['home_layout' => $slug]);
    $this->get('/en')->assertOk()->assertViewIs("home.layouts.$slug");
})->with(['gerold-01', 'gerold-02', 'gerold-03', 'gerold-08', 'gerold-10']);
```

- [ ] **Step 2: Run it, verify it fails**

Run: `php artisan test --compact --filter=HomeLayoutsContent`
Expected: FAIL (views 02/03/08/10 missing).

- [ ] **Step 3: Compose each layout**

Each layout = `<x-layouts.public>` wrapping the section components in the spec's §6.1 order. Example `gerold-02`:

```blade
<x-layouts.public>
    <x-organisms.sections.hero :profile="$profile" />
    <x-organisms.sections.about :profile="$profile" :stats="$stats" />
    <x-organisms.sections.services />
    <x-organisms.sections.portfolio :projects="$projects" />
    <x-organisms.sections.resume-cta :profile="$profile" />
    <x-organisms.sections.skills :skills="$skills" />
    <x-organisms.sections.testimonials />
    <x-organisms.sections.certifications-marquee :certifications="$certifications" />
    <x-organisms.sections.contact-cta />
</x-layouts.public>
```

Build 01, 03, 08, 10 from the §6.1 table the same way.

- [ ] **Step 4: Run tests, verify pass**

Run: `php artisan test --compact --filter=HomeLayoutsContent`
Expected: PASS (5 datasets).

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/home/layouts tests/Feature/HomeLayoutsContentTest.php
git commit -m "feat: compose the 5 Gerold home layouts"
```

---

## Phase D — Existing-data pages

### Task D1: Projects index + filters

**Files:**
- Modify: `resources/views/projects/index.blade.php`, `resources/views/livewire/projects/project-filters.blade.php`
- Test: `tests/Feature/ProjectsIndexTest.php` (extend if present)

- [ ] **Step 1: Write/extend the failing test**

```php
<?php

use App\Models\Project;

it('lists projects on the Gerold index', function () {
    $p = Project::factory()->create();
    $this->get('/en/projects')->assertOk()->assertSee($p->getTranslation('title', 'en'));
});
```

- [ ] **Step 2: Run it**

Run: `php artisan test --compact --filter=ProjectsIndex`
Expected: PASS or FAIL depending on current markup; keep it green through the restyle.

- [ ] **Step 3: Restyle (source: Gerold `index.html` portfolio grid + a filter bar)**

Adapt the Gerold portfolio grid into `projects/index.blade.php` using `<x-molecules.project-card>`; keep filtering in the existing `ProjectFilters` Livewire component (restyle its Blade only — no Isotope). Add `data-reveal` to cards.

- [ ] **Step 4: Run tests + build**

Run: `php artisan test --compact --filter=ProjectsIndex && npm run build`
Expected: PASS + build OK.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/projects/index.blade.php resources/views/livewire/projects/project-filters.blade.php tests/Feature/ProjectsIndexTest.php
git commit -m "feat: restyle projects index to Gerold"
```

---

### Task D2: Project detail

**Files:**
- Modify: `resources/views/projects/show.blade.php`
- Test: `tests/Feature/ProjectShowTest.php`

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Models\Project;

it('shows a project detail page', function () {
    $p = Project::factory()->create();
    $this->get(route('projects.show', ['project' => $p->slug, 'locale' => 'en']))
        ->assertOk()->assertSee($p->getTranslation('title', 'en'));
});
```

- [ ] **Step 2: Run it**

Run: `php artisan test --compact --filter=ProjectShow`
Expected: PASS/adjust route params to match the real slug binding.

- [ ] **Step 3: Restyle (source: Gerold `portfolio-details.html`)**

Adapt the Gerold portfolio-details layout into `show.blade.php` with real project fields (title, description, images, tech, links, breadcrumb).

- [ ] **Step 4: Run tests + build**

Run: `php artisan test --compact --filter=ProjectShow && npm run build`
Expected: PASS + build OK.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/projects/show.blade.php tests/Feature/ProjectShowTest.php
git commit -m "feat: restyle project detail to Gerold"
```

---

### Task D3: Résumé web page

**Files:**
- Modify: `resources/views/resume.blade.php`
- Test: `tests/Feature/ResumeWebTest.php`

- [ ] **Step 1: Write the failing test**

```php
<?php

it('renders the résumé web page with download links', function () {
    $this->get('/en/resume')->assertOk()
        ->assertSee(route('resume.print', ['locale' => 'en']), false);
});
```

- [ ] **Step 2: Run it**

Run: `php artisan test --compact --filter=ResumeWeb`
Expected: PASS/adjust the route to the real path.

- [ ] **Step 3: Restyle (source: Gerold résumé area of `index-2.html`)**

Adapt into `resume.blade.php`: experiences, education, skills, languages, certifications, plus download buttons to `resume.print`, `resume.download`, `resume.json`. **Do not touch** `resume/print.blade.php` or `resume/templates/*`.

- [ ] **Step 4: Run tests + build**

Run: `php artisan test --compact --filter=ResumeWeb && npm run build`
Expected: PASS + build OK.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/resume.blade.php tests/Feature/ResumeWebTest.php
git commit -m "feat: restyle résumé web page to Gerold"
```

---

### Task D4: Contact page

**Files:**
- Modify: `resources/views/contact.blade.php`, `resources/views/livewire/contact-form.blade.php`
- Test: `tests/Feature/ContactPageTest.php` (extend if present)

- [ ] **Step 1: Write the failing test**

```php
<?php

it('renders the Gerold contact page with the form', function () {
    $this->get('/en/contact')->assertOk()->assertSeeLivewire('contact-form');
});
```

- [ ] **Step 2: Run it**

Run: `php artisan test --compact --filter=ContactPage`
Expected: PASS/adjust component alias.

- [ ] **Step 3: Restyle (source: Gerold contact area of `index-2.html`)**

Adapt the contact section into `contact.blade.php`; restyle `contact-form.blade.php` fields with Gerold form styling. **Do not change** the `ContactForm` Livewire class logic/validation.

- [ ] **Step 4: Run tests + build**

Run: `php artisan test --compact --filter=ContactPage && npm run build`
Expected: PASS + build OK.

- [ ] **Step 5: Pint + commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/contact.blade.php resources/views/livewire/contact-form.blade.php tests/Feature/ContactPageTest.php
git commit -m "feat: restyle contact page to Gerold"
```

---

## Phase E — QA & polish

### Task E1: Browser smoke tests (no JS console errors)

**Files:**
- Create: `tests/Browser/PublicSmokeTest.php`

- [ ] **Step 1: Write the smoke test**

```php
<?php

it('public pages load without JS console errors', function (string $path) {
    $page = visit($path);
    $page->assertNoJavascriptErrors();
})->with(['/en', '/fr', '/en/projects', '/en/resume', '/en/contact']);
```

- [ ] **Step 2: Run it**

Run: `php artisan test --compact --filter=PublicSmoke`
Expected: PASS (GSAP/Lenis/Swiper init cleanly). Fix any console errors surfaced.

- [ ] **Step 3: Commit**

```bash
git add tests/Browser/PublicSmokeTest.php
git commit -m "test: browser smoke tests for public pages"
```

---

### Task E2: Responsive, contrast & motion audit (manual)

**Files:** none (verification task)

- [ ] **Step 1: Verify every page at 360px width** — no horizontal scroll, tap targets ≥ 44px. Fix Blade/utility issues found.
- [ ] **Step 2: Verify WCAG 2.1 AA contrast** in both dark and light themes on hero, buttons, muted text. Adjust token values in `app.css` if any pair fails.
- [ ] **Step 3: Verify `prefers-reduced-motion`** disables Lenis + GSAP reveals + marquee (emulate in devtools).
- [ ] **Step 4: Full build + full test suite**

Run: `npm run build && php artisan test --compact`
Expected: build OK; suite green.

- [ ] **Step 5: Commit any fixes**

```bash
vendor/bin/pint --dirty --format agent
git add -A
git commit -m "fix: responsive, contrast and reduced-motion audit fixes"
```

---

## Self-review notes

- **Spec coverage:** §4 tokens → A3; motion → A4/A2; §6 registry/setting/select/views → A5–A8, C1; §5 components → B1–B5; §7 pages → D1–D4; §8 i18n → global constraint + tests assert both locales; §9 testing → each task's test + E1/E2; reserved slots → B5. All mapped.
- **Type consistency:** `HomeLayoutRegistry::view/selectOptions/slugs` used identically in A5/A7/A8. HomeController output names `$profile,$projects,$experiences,$skills,$certifications,$stats` consumed verbatim in B4/C1.
- **Known adaptation points to confirm during implementation** (flagged inline): real column names on `Experience/Project/Skill/Certification`; the exact route-parameter shape for `projects.show`/`resume.print`; the Livewire component alias for `contact-form`. Verify with `php artisan model:show` / `route:list` before writing each test.
