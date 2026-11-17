# aifriendly.fyi — application notes

Bilingual, server-rendered site (EN + 简体中文) with two small JSON endpoints.
No framework, no build step, no external runtime dependencies.

## Layout

```
index.php              front controller / router
app/bootstrap.php      request, language, meta and route setup
app/i18n.php           t(), tr(), url(), language switch helpers
app/ui.php             shared components and icons
app/header.php         document head, navigation, language switch
app/footer.php         footer + JS i18n payload
app/pages/*.php        page templates
app/lang/en.php        English UI and page copy
app/lang/zh.php        Chinese UI and page copy
app/lang/*-legal.php   legal copy (merged into the language)
app/data/checks.php    24 checks, bilingual, shared by standard + audit
app/data/faqs.php      FAQ data
app/data/badges.php    badge files, dimensions and snippets
app/data/resources.php download cards
api/scan.php           heuristic scanner (localized labels, 30/hour)
api/contact.php        contact / verify / gallery forms (localized, 6/hour)
assets/                CSS, JS, fonts, badges, brand assets
.data/                 runtime data (blocked by nginx, 750 www:www)
```

## Design system

See `app/DESIGN-SYSTEM.md` for the layout, spacing, type, colour, radius and
component rules; it is the single source of truth. `app/DESIGN.md` covers the
brand, mark construction and palettes. `assets/css/site.css` is the only
implementation, and its `:root` block is the only place tokens are declared.

## Routes

English is the canonical unprefixed version. Chinese lives under `/zh/`.
`/en/...` and missing trailing slashes redirect with 301.

- `/` and `/zh/`
- `/standard/`, `/badges/`, `/scan/`, `/resources/`, `/about/`, `/verify/`, `/gallery/`
- `/license/`, `/privacy/`, `/terms/`
- Unknown paths render the 404 template with a 404 status.

## Language changes

1. Add or edit keys in `app/lang/en.php` and `app/lang/zh.php` (same structure).
2. Legal sections live in `app/lang/en-legal.php` and `zh-legal.php`.
3. Data-driven content (checks, FAQs, badge descriptions) uses `['en' => ..., 'zh' => ...]` pairs in `app/data/`.
4. Never print translated HTML through `e()` unless it is plain text; use `links()` for prose with `{badges}`, `{standard}`, `{contact}` etc.

## Nginx

The vhost must:

- serve `/assets/` directly;
- route everything else through `index.php`;
- block `/app/` (source) and `/.data/` (runtime);
- allow only `/index.php` and the two API endpoints to execute PHP;
- keep `/.well-known/acme-challenge/` reachable on port 80 for certbot.

See `/www/server/panel/vhost/nginx/aifriendly.fyi.conf`.

## Data

- Scan cache: `.data/cache/<sha256>.json`, six hours, no plain-text URLs.
- Rate limits: `.data/rate/*.json`, hashed IP, short-lived.
- Forms: `.data/inbox/YYYY-MM.jsonl`.

The site login user cannot list `.data`; use `sudo`, and use `find -delete`
rather than a shell glob:

```bash
sudo find /www/aifriendly/.data/cache -type f -delete
sudo tail -n 20 /www/aifriendly/.data/inbox/2026-11.jsonl
```

## Checks and tests

```bash
find app api -name '*.php' -print0 | xargs -0 -n1 php -l
curl -sI https://aifriendly.fyi/
curl -s "https://aifriendly.fyi/api/scan.php?url=aifriendly.fyi&lang=zh"
```

Run axe-core and html-validate against the rendered pages before changing layout.
Test at 320, 390, 768, 1024 and 1440 px; watch for auto-zoom caused by a child
with a large min-width (tables, pre blocks, grids).

## Mark geometry

The mark is one SVG geometry: a concave four-point spark, one ribbon orbit
and one ember dot on a 360-unit artboard. Do not redraw it per placement.
Badge assets are generated with outlined text and fixed geometry; edit the SVG source only with the same proportions.

## Cached media

Badge, brand, kit and social URLs are versioned at render time with
`?v=<MEDIA_VERSION>` (derived from the artwork and kit mtimes). This bypasses
long-lived CDN caches without renaming files. CSS and JS use `ASSET_VERSION`.

## Cloudflare

Cloudflare may inject its optional Web Analytics beacon into HTML. The origin CSP
blocks it (`script-src 'self'`), so it does not run. To remove the resulting
console violation, turn off Web Analytics for the zone in Cloudflare.

## TLS

`certbot.timer` renews the Let's Encrypt certificate. The HTTP server block keeps
the ACME path open and redirects everything else. Test with:

```bash
sudo certbot renew --cert-name aifriendly.fyi --dry-run --no-random-sleep-on-renew
```
