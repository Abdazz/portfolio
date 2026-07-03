# Gerold Frontend Redesign — Design (Sub-project 1: Foundation)

**Date:** 2026-07-03
**Status:** Draft for review
**Owner:** Abdoul-Aziz ZOROM

## 1. Goal

Replace the public site's current "dark editorial" design (amber / Fraunces / sharp
edges) with a full adaptation of the **Gerold – Personal Portfolio** ThemeForest
template, integrated natively into the existing stack (Laravel 13, Blade, Livewire 4,
Flux, **Tailwind v4**). The owner must be able to switch between **5 home layouts**
(Gerold demos 01, 02, 03, 08, 10) from the Filament back-office.

The template source lives at
`/media/abdazz/data3/web/perso/Gerold - Personal Portfolio Tailwind CSS Template/`
(outside the repo). It is a static HTML/Tailwind-v3 template driven by jQuery plugins;
nothing drops in — every section is re-authored as Blade/Livewire in our stack.

## 2. Scope & decomposition

The full request spans **four independent subsystems**. This spec covers **only
sub-project 1**. The others each get their own spec → plan → implementation cycle.

| # | Sub-project | Status in this spec |
|---|---|---|
| 1 | **Gerold redesign foundation + existing-data pages** | **THIS SPEC** |
| 2 | Services module (new model + Filament + section) | Later — reserved slot only |
| 3 | Testimonials module (new model + Filament + section) | Later — reserved slot only |
| 4 | Blog module + LinkedIn newsletter sync | Later — reserved slot only |

**In scope (sub-project 1):**
- Shared Gerold design system (theme tokens, Sora font, self-hosted FA Pro icons, motion).
- 5 selectable home layouts, back-office-driven.
- Redesign of all public pages backed by **existing data**: home, projects (index +
  detail), résumé web page, contact.
- **Reserved slots** for Services / Testimonials / Blog sections, conditionally rendered
  (hidden until their module ships).

**Out of scope (sub-project 1):**
- Services / Testimonials / Blog models, Filament resources, and data-backed rendering.
- LinkedIn import.
- Filament admin panel visual design (Filament owns its own design system — untouched).
- Résumé **PDF/print** templates (`resources/views/resume/templates/*`,
  `resources/views/resume/print.blade.php`) — these are print-oriented and stay as-is.
  Only the public résumé **web** page (`resources/views/resume.blade.php`) is restyled.

## 3. LinkedIn newsletter feasibility (informs sub-project 4, recorded here)

LinkedIn provides **no official public API** for newsletters/articles and **no official
RSS feed**. Realistic options for later:
- **(a) Third-party RSS bridge** (RSS.app etc.) generating a feed from the newsletter's
  public URL, consumed by a scheduled job. Semi-automatic, depends on a third party, can
  break.
- **(b) Manual entry** into the Blog model. Reliable, manual.
- **(c) Scraping** — violates LinkedIn ToS, fragile. Rejected.

Recommendation for sub-project 4: **Blog model + optional RSS sync (option a)**. Not
"fully automatic." Sub-project 4 begins with a feasibility spike.

## 4. Design system (shared)

### 4.1 Theme tokens (Tailwind v3 → v4 translation)

The Gerold `tailwind.config.js` is rewritten as CSS-first `@theme` variables in
`resources/css/app.css`. **Semantic token names are preserved** (`surface`, `text`,
`accent`, `border`) so components stay palette-agnostic; only values change to Gerold's.

Gerold palette (from source config):
- **Accent (primary):** `#8750f7`, lighter `#9b8dff`; secondary `#2a1454`.
- **Dark surfaces:** `#0f0715` (base), `#050709` (deepest), `#140c1c`, `#10171c`.
- **Body text on dark:** `#dddddd`; muted `#7A7A7A` / `#FFFFFF99`.
- **Light theme (cream):** `#f6f3fc`.
- **Green ponctuation:** `#00ff2f` (sparingly).
- Purple gradients (`gradient-primary`, etc.) ported as `@theme`/utility values.
- Heavily rounded corners (up to pill) and Gerold's box-shadows ported.

**Dark mode is the default**; light (cream) is the alternate, toggled via the existing
theme-cookie mechanism (`.dark` class). Both fully designed.

### 4.2 Typography

- **Sora** (single sans family) via the existing Google-Fonts `@import` at the top of
  `app.css`. `--font-sans` → Sora.
- Fraunces / Plus Jakarta Sans removed from the **public** site. (Admin/Filament
  untouched.)

### 4.3 Icons

- **Font Awesome Pro** + `flaticon_gerold`, **self-hosted** from the Gerold template
  assets (`assets/fonts/fa-*`, `assets/css/font-awesome-pro.min.css`,
  `flaticon_gerold.*`) copied into `resources/` / `public/`. FA Pro is not on public npm;
  the Gerold license covers use on this end product.
- Existing Flux/Lucide icons remain available where already used; new Gerold sections use
  FA Pro / flaticon to match the template pixel-for-pixel.

### 4.4 Assets policy

- **No Gerold demo images** (licensed stock/placeholders) ship to the repo. Only real
  content: profile photo, project images, certification logos
  (`public/images/certifications/*`), etc.
- Decorative Gerold **shapes/SVGs** (non-photographic, part of the design) may be copied
  as needed.

### 4.5 Motion strategy (replaces jQuery stack)

New JS dependencies (approved): **`gsap`**, **`lenis`**, **`swiper`**. jQuery and all its
plugins (Owl, Isotope, Magnific, lightcase, wow, odometer, nice-select, sticky, etc.)
are **not** used.

| Gerold effect | Replacement |
|---|---|
| Smooth scroll (Lenis) | **Lenis** |
| Scroll reveals, split-text | **GSAP + ScrollTrigger** |
| Counters (odometer) | Alpine + IntersectionObserver (no dep) |
| Portfolio filtering (Isotope) | **Livewire** (existing `ProjectFilters`) |
| Lightbox (Magnific/lightcase) | Alpine component / Flux modal |
| Carousels (Owl/Swiper) | **Swiper** (used when testimonials ship) |
| Custom cursor / tilt / wow | Alpine + CSS |

`prefers-reduced-motion` disables GSAP reveals and Lenis. Motion is initialised in
`resources/js/app.js` and is Livewire-navigate aware (re-init on `livewire:navigated`).

## 5. Component architecture

Extends the existing atomic structure under `resources/views/components/`.

- **atoms/** — restyled: `button` (pill, gradient/outline variants), `badge`, `link`.
- **molecules/** — restyled: `card`, `project-card`, `breadcrumb`, `language-switcher`,
  plus new `stat-counter`, `marquee`, `section-heading` (Gerold eyebrow + title).
- **organisms/** — new Gerold sections (one component each, self-contained, documented
  I/O):
  - `sections/hero` (variants per layout as needed)
  - `sections/about` (bio + `stat-counter` row)
  - `sections/portfolio` (project grid; links to projects index)
  - `sections/experience` (timeline; reuses `experience-timeline`)
  - `sections/skills`
  - `sections/resume-cta` (résumé preview + download buttons)
  - `sections/certifications-marquee` (repurposed Gerold "brand" marquee)
  - `sections/contact-cta` (+ contact form entry)
  - `sections/decorative-text` (animated marquee text)
  - **Reserved slots** (render nothing until their module ships, guarded by a
    `@if` on data/feature existence): `sections/services`, `sections/testimonials`,
    `sections/blog`.
- **layouts/public.blade.php** — restyled shell: header (Headroom-style sticky via
  Alpine), footer, Lenis wrapper, theme toggle, language switcher, hreflang tags
  preserved.

Each section component takes its data via explicit props/view-composers, not global
state, so it can be understood and tested in isolation.

## 6. Home layout system (Approach A: Registry + composed views)

Mirrors the existing résumé `TemplateRegistry` pattern.

- **`app/Services/Home/HomeLayoutRegistry.php`** — `slug => view` map for the 5 layouts;
  `view(slug)`, `selectOptions()`, `slugs()`, default fallback. Registered as a singleton.
- **Setting:** add `home_layout` (string, default `'gerold-01'`) to `SiteSetting`
  (`$fillable`, `instance()` defaults) via a new migration adding a `home_layout` column
  to `site_settings`.
- **Filament:** add a `Select::make('home_layout')` to
  `SiteSettings/Schemas/SiteSettingForm.php`, options from `HomeLayoutRegistry`.
- **Views:** `resources/views/home/layouts/gerold-01.blade.php` … `-10.blade.php`. Each
  composes shared section components in a Gerold-faithful order.
- **Controller/route:** the existing home route resolves the active layout via
  `HomeLayoutRegistry->view(SiteSetting::instance()->home_layout)` and renders it.

### 6.1 The 5 layouts (section composition)

Derived from Gerold demos; `*` = reserved slot (conditionally rendered).

| Layout | Section order |
|---|---|
| **gerold-01** | Hero · Services* · Projects · Experience · Skills · Testimonials* · Blog* · Contact |
| **gerold-02** | Hero · About+Stats · Services* · Projects · Résumé · Skills · Testimonials* · Certifications-marquee · Contact |
| **gerold-03** | Hero · Certifications-marquee · Services* · Projects · Résumé · Skills · Testimonials* · Decorative-text · Contact-CTA |
| **gerold-08** | Hero · About+Stats · Services* · Projects · Experience · Certifications-marquee · Testimonials* · Blog* · Contact-CTA |
| **gerold-10** | Hero · Contact-CTA (consultation) · Certifications-marquee · About+Stats · Projects · Testimonials* · Contact · Blog* |

Exact ordering/markup refined against demo HTML during implementation.

## 7. Page-by-page redesign (existing data)

- **Home** — 5 layouts as above.
- **Projects index** (`projects/index.blade.php` + `Livewire\Projects\ProjectFilters`) —
  Gerold portfolio grid + filter UI, filtering stays in Livewire.
- **Project detail** (`projects/show.blade.php`) — Gerold `portfolio-details` layout.
- **Résumé web** (`resume.blade.php`) — Gerold résumé/CV section styling; education &
  languages live here; download buttons to existing `/resume/print`, PDF, JSON routes.
  PDF/print templates untouched.
- **Contact** (`contact.blade.php` + `Livewire\ContactForm`) — Gerold contact section;
  form logic/validation unchanged.

## 8. Internationalisation (preserved)

- Two locales `en`/`fr`, URL-prefixed via `mcamara/laravel-localization`. Unchanged.
- All new strings via `__()` — no hardcoding. New keys added to
  `resources/lang/{en,fr}/*.php`.
- `hreflang` (`en`/`fr`/`x-default`) tags preserved on every page.
- Translatable route segments preserved.

## 9. Testing strategy

Per project rule: every change programmatically tested (Pest, feature tests preferred).

- **Home layout switching:** feature test — setting `home_layout` to each of the 5 slugs
  renders the corresponding view and returns 200; unknown slug falls back to default.
- **Registry unit test:** `HomeLayoutRegistry` resolves views, options, fallback.
- **Page smoke tests:** each public page (both locales) returns 200 and contains its key
  landmarks.
- **Reserved slots:** with no Services/Testimonials/Blog data, those sections are absent
  from the DOM and pages still render.
- **Pest browser smoke** (Pest 4): visit each page, assert no console JS errors (GSAP /
  Lenis / Swiper init).
- **Responsive:** manual verification at **360px** before "done"; WCAG 2.1 AA contrast in
  both themes.

## 10. Migration & build notes

- New migration: `home_layout` column on `site_settings`.
- `npm install gsap lenis swiper`; self-host FA Pro / flaticon assets.
- `npm run build` after changes; Vite manifest must resolve.
- `vendor/bin/pint --dirty` before finalising PHP changes.

## 11. Risks & open points

- **FA Pro licensing:** self-hosting from the purchased template is acceptable for this
  end product; do not commit FA Pro to a public mirror if the repo is ever open-sourced.
- **Layout fidelity vs. real data:** Gerold demos assume rich imagery; sparse real data
  (few projects/certs) may leave sections thin — layouts must degrade gracefully.
- **Reserved slots** must not render empty shells; guarded by data/feature checks.
- Exact per-layout section ordering is provisional until validated against demo HTML.
