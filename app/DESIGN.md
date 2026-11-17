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
             outlines never round an element that must stay square (inputs)
shadow       resting: none · interactive hover: --shadow-sm
motion       0.24s cubic-bezier(.22,1,.36,1) for controls
             0.55s reveal; mark orbit 14s linear (dot + gradient swirl)
             respect prefers-reduced-motion for every CSS animation
type         body 16 / sm 14 / xs 13 / label 12 / micro 11 / tag 10 (px minima)
             caps tracking 0.12em; eyebrow 0.14em; no other tracking values
spacing      section clamp(84px, 9.5vw, 152px) · card 24px
             grid gap 20px · compact control gap 8px
```

## Type scale

Latin: Ubuntu (400 preload, 500/700 on demand). Code: Ubuntu Mono. Chinese:
system CJK stack (`--font-zh`); Latin inside Chinese copy uses Ubuntu.
Font files are latin-subset woff2, `font-display: swap`; the CSS `@font-face`
block and the preload in `app/header.php` are the only places fonts are wired.

Roles, one per size class — nothing smaller than 10px anywhere, and 10px is
reserved for uppercase tags only:

| role | English | Chinese |
|---|---|---|
| display | clamp(48px, 10.5vw, 136px), 0.83 | clamp(40px, 8.6vw, 104px), 1.12 |
| page title | clamp(34px, 7.5vw, 104px), 0.90 | clamp(30px, 6.4vw, 84px), 1.18 |
| h2 | clamp(26px, 3.6vw, 56px), 1.04 | clamp(24px, 3.2vw, 46px), 1.28 |
| lead | clamp(16px, 1.3vw, 19px), 1.62 | clamp(15.5px, 1.2vw, 18px), 1.90 |
| body | 16px, 1.65 | 16px, 1.90 |
| prose | 15px, 1.80 | 15px, 1.95 |
| label | 12px uppercase, 0.1em | 12px, 0.02em |
| micro | 11px uppercase, 0.1em | 11px, 0.06em |
| tag | 10px uppercase | 10px, 0.06em |

Display/page-title/h2 keep the 1:0.77:0.41 ratio to body in both languages so
contrast between levels is real but never theatrical (no 178px display against
6px mock labels).

Additional Chinese rules:

- no negative tracking on headings;
- measure: lead 40ch, prose 42em, check body 38em;
- buttons 13px / small 11px; nav 12px.

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

## Links

- Never underlined. Not in prose, not in footers, not as hover, not as active
  state. An underline is decoration; clickability is communicated by the
  element's role and affordance.
- In-text links: ember #B23A00 at weight 500 (body text is ink at 400, so the
  link is distinguishable by colour AND weight — axe's link-in-text-block
  check passes). Hover inverts to ink.
- Structural links (buttons, cards, TOC, footer columns, file links) carry an
  arrow icon, weight or placement instead of any underline.
- The header brand does not recolour on hover; its ribbon spectrum flows
  instead (see Mark motion).

## Reading progress

A 2px spectrum rule fixed to the header's bottom edge fills left-to-right
with scroll (rAF-throttled transform, no animation loop). Scroll-linked, not
decorative: it answers "how much is left" on long pages.

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
  statements, verified, OG, poster. Spectrum accent gradients (verified frame,
  underline rules) run on the diagonal — 135°, top-left amber to bottom-right
  indigo — never a flat left-to-right ramp.
- Flat two-tone (`mark-flat-cream` / `lockup-flat-cream`): cream fills
  (#fff6e7 spark + ribbon, #fffaf1 inner ribbon) with an ink #171008 outline,
  no dot. For gradient tiles and other colourful surfaces: one silhouette
  would melt into the background, so boundaries are drawn.
- Poster two-colour (`mark-poster` / `lockup-poster`): cream #fff6e7 spark,
  ink #171008 ring, ink outline, no dot. For printed posters and any busy
  photographic or gradient surface where the spark and the ring must read as
  two shapes. Use this palette consistently on every colourful-surface
  placement (home placements and the badge preview tile).
- Mono: favicon and 64px icons, no ember dot. The geometry is the same; the
  mono palette exists so the ribbon does not turn into mud at the smallest
  sizes.
- Dark surfaces: cream spark, ink stroke, light ribbon, ember dot.
- Light surfaces: ink spark, paper/white stroke, spectrum or drift ribbon.

## Mark structure and motion

- Layer order is the whole trick and must not change: **spark back piece →
  ribbon ring → dot → spark front piece**. The spark is drawn twice: the back
  copy carries fill and outline under the ring, the front copy (fill AND its
  own outline copy) is clipped (by a polygon whose edge runs through the ring's
  hole) to the upper-left half. The outline copy is essential: without it the
  front fill eats the inner half of the 2-unit silhouette stroke. The two halves
  have the same fill, so their junction is invisible and never crosses the
  drawn ribbon. Nothing fades and no mask is used: whatever passes behind the
  spark is covered by the front piece.
- The ember dot is omitted below 96px: header/footer 30–36px, favicon, icons,
  lockups (40), statement badges (34) and seals (72) all ship without it. It
  is always present and always ember #DC5000 at 96px and above.
- At display sizes (≥ 200px, currently hero 460px and the mark section 420px)
  the mark is alive. The dot runs on a true ellipse fitted to the artwork's own
  centreline (centre 178.3,178.9; axes 150×61; rotation −27.5°; mean residual
  1.4 units), emitted as four cubic arcs. It follows that ellipse strictly all
  the way around: the lower-right arcs cross in front of the spark and stay
  visible; the upper-left and left-spike arcs pass behind it and are covered by
  the front piece (the clip edge is 45–80 units clear of those arcs, so the dot
  cannot poke out inside the spark). SMIL `animateMotion` + `mpath`, 14s, the
  same clock as the gradient swirl. Motion uses `keyPoints`/`keyTimes` instead
  of `paced`: the dot eases through the two tight ends of the ellipse (radius
  ~25 units) and runs faster along the flanks (~370 units). Measured per-frame
  turn stays near 1° instead of spiking to 6°; a faster loop alone would make
  that spike worse, so keep the 14s clock. The two ribbon gradients rotate 14s
  via SMIL `animateTransform` on `gradientTransform`.
- Motion is one system: dot and colour travel the same direction at the same
  period, so it reads as orbit, not decoration.
- Hovering the header brand makes the ribbon's spectrum flow (CSS
  `hue-rotate`, 6s loop, on the gradient-filled paths only). The spark keeps
  its colour — links never recolour the mark's core.
- `prefers-reduced-motion: reduce` swaps the animated dot for a static twin
  parked on the ribbon (`.mark__dot-static`) and stops the hue flow; the
  gradient swirl continues but is a slow colour shift, not movement across
  the page.

## Hero layout

- The hero visual spans both the headline row and the copy row in the grid, so
  its centre lines up with the centre of the whole hero content (the mark used
  to sit ~150px low when it only spanned the copy row). At ≤980px the grid
  collapses and the mark stacks below the copy, centred on its own row.

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
