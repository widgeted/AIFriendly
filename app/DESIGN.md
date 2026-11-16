# AI Friendly — design system

This is the source of truth for the interface. The tokens live at the top of
`assets/css/site.css`; this file explains how and why they are used.

## Principles

1. Function first. If a rule, line, glow or card does not help reading or use,
   remove it.
2. One geometry, many palettes. The mark is never redrawn. Change fills only
   when a surface needs it, and keep the 2-unit stroke and the ember dot.
3. Content is constrained; structure can bleed. Section rules and footer bands
   may span the viewport. Content-column rules do not cut through sidebars.
4. Same component, same shell. Buttons share radius, border and shadow. Cards
   share radius, border and resting shadow. Colour may change; geometry may not.
5. English and Chinese are designed separately. Translation is content, not
   typography.

## Tokens

```
colour       paper #f8f3ea · paper-2 #fffaf1 · ink #171008
             body #4b4034 · muted #6f6355 · ember #b23a00
lines        --line #d8cdbd · --line-strong #978672 · always 2px
radius       card 22px · button/code 12px · nested 10px
shadow       resting: none · interactive hover: --shadow-sm
motion       0.24s cubic-bezier(.22,1,.36,1) for controls
             0.55s reveal; respect prefers-reduced-motion
spacing      section clamp(84px, 9.5vw, 152px) · card 24px
             grid gap 20px · compact control gap 8px
```

## Type scale

English and Chinese use the same roles with different values.

| role | English | Chinese |
|---|---|---|
| display | clamp(54px, 17vw, 178px), 0.83 | clamp(44px, 13.2vw, 124px), 1.12 |
| page title | clamp(38px, 10.4vw, 138px), 0.90 | clamp(34px, 7.2vw, 96px), 1.18 |
| h2 | clamp(30px, 4.5vw, 66px), 1.04 | clamp(26px, 3.4vw, 50px), 1.28 |
| lead | clamp(16px, 1.4vw, 20px), 1.62 | clamp(15.5px, 1.25vw, 19px), 1.90 |
| body | 16px, 1.65 | 16px, 1.90 |
| prose | 15px, 1.80 | 15px, 1.95 |

Additional Chinese rules:

- no negative tracking on headings;
- measure: lead 40ch, prose 42em, check body 38em;
- buttons 13px / small 11px; nav 12px;
- CJK font stack is `--font-zh`; Latin inside Chinese copy still uses Inter.

Chinese type is not a translated copy of the English scale. Dense glyphs need
more line height, less tracking and a smaller display size.

## Buttons

One shell: 2px border, 12px radius, no shadow, centred label, 0.24s hover.
Variants only change colour:

- primary: ink fill, paper text
- secondary: transparent, line-strong border
- ghost: transparent, no visible border

Heights: 48px default · 38px small · 32–40px compact utility. A button inside a
list, nav or TOC must not inherit link typography. Use `:not(.btn)` on generic
link selectors.

## Cards

One shell: 2px border, 22px radius, paper-2 background, no resting shadow.
Only interactive cards lift 3px and use `--shadow-sm` on hover. Static cards do
not pretend to be clickable. Feature panels (scan, score, audit) use the same
shell, not a one-off shadow.

## Inputs

All text inputs, textareas and selects use a 2px underline, `--line-strong` at
rest and ember on focus. The scan field uses the same rule, only larger.

## Rules and dividers

- Full-width rules: site header, footer bands, section boundaries, hero strip.
- No full-width rule inside a content column, especially the standard page
  alongside the Contents sidebar.
- Lists use spacing, numbering and type hierarchy. Do not put a rule under every
  item.
- The standard page has no full-width rules in its body. Level headings, checks,
  audit rows and sources are separated by whitespace and grouping.
- The compact level summary on the home page may use row rules because it is a
  three-row comparison, not a 24-row list.

## Palettes

- Spectrum ribbon: header/footer, hero, mark section, badge lockups, seals,
  statements, verified, OG, poster.
- Mono: favicon and 64px icons. The geometry is the same; the mono palette
  exists so the ribbon does not turn into mud at the smallest sizes.
- Dark surfaces: cream spark, ink stroke, light ribbon, ember dot.
- Light surfaces: ink spark, paper/white stroke, spectrum or drift ribbon.

## Copy voice

Plain, specific, human. Say what a thing does; avoid hype and decorative
metaphor. Keep technical terms exact. Chinese copy is written for Chinese
readers, not translated word-for-word: shorter clauses, natural punctuation,
and no English sentence rhythm forced into Chinese.

## Files

- `assets/css/site.css` — tokens, type systems, components, rules
- `app/lang/en.php`, `app/lang/zh.php` — UI copy
- `app/lang/*-legal.php` — legal copy
- `app/data/checks.php` — bilingual checks
- `assets/badges/`, `assets/brand/` — generated mark assets
