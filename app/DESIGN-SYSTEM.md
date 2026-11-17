# AI Friendly — design system

Version 4.0 · single source of truth for layout, spacing, type, colour,
radii, borders, gradients and motion. The brand/mark rules live in
[`DESIGN.md`](DESIGN.md); this file governs every page shell and component.

**Rule zero: if a value is not in this document and not in the token block at
the top of `assets/css/site.css`, it does not belong in the product.** Spacing,
sizes, radii and colours are chosen from the scales below — never typed as a
one-off pixel value.

---

## 1. Principles

1. **One rhythm.** Vertical space comes from a 4px scale. Siblings share the
   same stack step; sections share one section step.
2. **Dense but calm.** Whitespace and local 2px rules carry the structure;
   full-width rules mark page boundaries only. Components stay compact.
3. **Two materials.** Paper (`--paper*`) and ink (`--ink*`), with a single
   accent (`--ember`) and one spectrum gradient. No third surface colour.
4. **Type does the hierarchy.** Size, weight and casing — not colour — separate
   levels. Body copy is always `--body`, never pure black.
5. **Bilingual by design.** Latin and Chinese share roles, not metrics. Chinese
   gets its own line-height and tracking tokens.
6. **Accessible by default.** AA contrast, visible focus, no colour-only
   meaning, no motion required to understand the page.

---

## 2. Tokens

All tokens are declared once in `assets/css/site.css` under `:root` (and the
`html[lang="zh-Hans"]` override block). Components consume tokens; they never
redeclare primitives.

### 2.1 Colour

| Token | Value | Use |
|---|---|---|
| `--paper` | `#f8f3ea` | page canvas |
| `--paper-2` | `#fffaf1` | raised surfaces: cards, panels, footer |
| `--paper-3` | `#f1e8d9` | inset fills: bars, code chips |
| `--paper-warm` | `#fff5dc` | callout / note background |
| `--paper-compare` | `#fff7e6` | highlighted table column |
| `--ink` | `#171008` | headings, primary text, dark surfaces |
| `--ink-2` | `#2a2118` | code text |
| `--body` | `#4b4034` | body copy |
| `--muted` | `#6f6355` | labels, meta, secondary copy |
| `--muted-2` | `#77695a` | placeholders |
| `--line` | `#d8cdbd` | 2px rules, card borders |
| `--line-strong` | `#978672` | interactive borders, focus-adjacent rules |
| `--ember` | `#b23a00` | accent text, active states, focus |
| `--ember-bright` | `#e85a00` | illustration only (mark bead) |
| `--header-bg` | `rgb(248 243 234 / .86)` | translucent header |
| `--header-bg-solid` | `rgb(248 243 234 / .93)` | scrolled header |

Rules:

- Links inherit their surrounding colour and recolour to `--ember` on hover.
  Never underline a link.
- `--ember` is for small text and marks. Never set body copy in ember.
- Dark surfaces (`--ink`) use `--paper-2` for text.
- No raw hex outside `:root` and the printed-poster exception.

### 2.2 Type

**Families**

- Latin: `--font` — Ubuntu 400/500/700, system fallback.
- Chinese: `--font-zh` — Ubuntu for Latin, then PingFang / YaHei / Noto CJK.
- Code: `--mono` — Ubuntu Mono.

**Scale**

| Token | Size | Role |
|---|---|---|
| `--fs-display` | `clamp(52px, 11vw, 136px)` | home hero, uppercase |
| `--fs-title` | `clamp(34px, 7.5vw, 104px)` | page hero `h1` |
| `--fs-h2` | `clamp(26px, 4.6vw, 56px)` | section headings |
| `--fs-h2-s` | `clamp(28px, 3vw, 42px)` | in-panel headings, legal prose `h2` |
| `--fs-h3` | `clamp(22px, 2vw, 30px)` | card / row headings |
| `--fs-h3-lg` | `clamp(24px, 2.4vw, 34px)` | level rows, feature rows |
| `--fs-h4` | `clamp(18px, 1.6vw, 22px)` | item headings, FAQ questions |
| `--fs-lead` | `clamp(16px, 1.3vw, 19px)` | section leads |
| `--fs-body` | `16px` | paragraphs, prose, inputs |
| `--fs-body-s` | `14px` | card copy, secondary paragraphs |
| `--fs-small` | `13px` | dense copy, table cells, details |
| `--fs-label` | `12px` | nav, buttons, captions, dense rows |
| `--fs-micro` | `11px` | eyebrows, meta, utility labels |
| `--fs-tag` | `10px` | tags, chips, mock labels |
| `--fs-stat` | `56px` | audit score numeral |
| `--fs-stat-s` | `46px` | scan score-ring numeral |
| `--fs-num` | `24px` | level-row counts, inline numerals |
| `--fs-nav-mobile` | `clamp(30px, 7vw, 44px)` | open mobile nav items |

**Line height / tracking**

| Token | Value | Use |
|---|---|---|
| `--lh-display` | `0.83` | display |
| `--lh-title` | `0.9` | page title |
| `--lh-none` | `1` | numerals, single-line badges |
| `--lh-h2` | `1.04` | section headings |
| `--lh-tight` | `1.1` | large display headings, score headlines |
| `--lh-heading` | `1.2` | h3/h4 |
| `--lh-sub` | `1.25` | card titles, secondary headings |
| `--lh-snug` | `1.4` | labels, small headings |
| `--lh-label` | `1.5` | dense UI copy |
| `--lh-micro` | `1.55` | micro answers, CJK hero lead |
| `--lh-body` | `1.65` | body |
| `--lh-prose` | `1.8` | long-form prose |
| `--lh-lead` | `1.62` | section leads |
| `--track-display` | `-0.04em` | display, page title |
| `--track-title` | `-0.04em` | page title alias |
| `--track-h2` | `-0.02em` | h2 |
| `--track-h3` | `-0.02em` | h3 |
| `--track-h4` | `-0.01em` | h4 |
| `--track-caps` | `0.12em` | uppercase micro labels |
| `--track-label` | `0.08em` | form labels, table heads |
| `--track-meta` | `0.06em` | meta rows, chips |
| `--track-ui` | `0.03em` | buttons, compact UI text |
| `--track-stat` | `-0.05em` | score numerals |

**Roles**

- Display: 600, uppercase, `--lh-display`, `--track-display`.
- Page title: 600, sentence case (uppercase only for the home display),
  `--lh-title`.
- H2/H3/H4: 600; colour `--ink`; max 18–24ch for display headings.
- Lead: 400, `--body`, `--lh-lead` (`1.62`).
- Body: 400, `--body`, `--lh-body`.
- Eyebrow/micro: 600, uppercase, `--track-caps`, `--muted`.
- Buttons/nav: 600, uppercase for nav and micro buttons, 12px.
- Weights: 400 body, 500 lead/emphasis, 600 headings and UI, 700 only for the
  active language.

**Chinese overrides** (`html[lang="zh-Hans"]`)

- Token overrides: `--fs-display` `clamp(40px, 8.6vw, 104px)`; `--fs-title`
  `clamp(30px, 6.4vw, 84px)`; `--fs-h2` `clamp(24px, 4vw, 46px)`;
  `--fs-h2-s` `clamp(24px, 3vw, 38px)`; `--fs-h3` `clamp(20px, 2vw, 28px)`;
  `--fs-h3-lg` `clamp(22px, 2.4vw, 32px)`; `--fs-h4` `clamp(18px, 1.6vw, 21px)`;
  `--fs-lead` `clamp(15.5px, 1.2vw, 18px)`.
- Line height: `--lh-display` 1.12, `--lh-title` 1.18, `--lh-h2` 1.28,
  `--lh-heading` 1.45, `--lh-body` 1.9, `--lh-prose` 1.95, `--lh-lead` 1.9.
- Tracking: display/title/h2/h3/h4 `0`; `--track-caps` `0.08em`.
- Max measure 40em for paragraphs, 42em for prose; hero lead keeps its own
  `clamp(19px, 1.6vw, 25px)` size at `--lh-micro`.

### 2.3 Space

4px base. Use the smallest step that preserves hierarchy.

| Token | px | Typical use |
|---|---|---|
| `--space-1` | 4 | icon/text gap, hairline offsets |
| `--space-2` | 8 | inline gaps, label→value |
| `--space-3` | 12 | button rows, list item padding |
| `--space-4` | 16 | icon blocks, paragraph stacks, compact padding |
| `--space-5` | 24 | card padding, grid gap, heading→copy |
| `--space-6` | 32 | card groups, panel padding (small) |
| `--space-7` | 40 | section head→content (small) |
| `--space-8` | 48 | blocks inside a section |
| `--space-9` | 64 | section padding (small), large gaps |
| `--space-10` | 80 | section padding, footer |
| `--space-11` | 96 | large panels |
| `--space-12` | 120 | hero / display spacing |
| `--space-13` | 152 | maximum section padding |

**Vertical rhythm recipes** (the only approved stacks):

| Relationship | Space |
|---|---|
| eyebrow → heading | `--space-4` (16) |
| heading → lead/body | `--space-5` (24) |
| lead → body | `--space-4` (16) |
| body → actions | `--space-5` (24) |
| actions → micro | `--space-4` (16) |
| card label → title | `--space-2` (8) |
| card title → copy | `--space-2` (8) |
| card copy → actions | auto / `--space-5` (24) |
| paragraph → paragraph | `--space-3` (12) |
| list row padding | `--space-3` (12) |
| link row padding | `--space-2` (8) |
| card padding | `--space-5` (24) |
| grid gap (cards) | `--space-5` (24) |
| section head → grid | `--stack` (32–48) |
| block → block inside a section | `--stack-lg` (48–80) |
| section padding | `--section-y` (48–96) |
| first section after a hero | `--section-y-first` (40–64) top only |

**Never**: 5/6/7/9/10/11/13/14/15/17/18/19/20/21/22/26/28/30/34/36px as a
margin, padding or gap. Snap to the table. The only raw pixel offsets allowed
are optical: `-1px` (`.sr-only` clip), `-3px` (arrow heads), and `2px` (optical
label nudges); they are not layout space.

**Miniature exemption**: placement mocks and badge previews are scaled
illustrations, not interface. They may use 2/4/6/8/10/12/16 and 8–10px type,
but must keep the same radii, 2px rules and spectrum ramp as the full-size
surfaces.

### 2.4 Layout

| Token | Value | Use |
|---|---|---|
| `--gutter` | `clamp(24px, 4vw, 64px)` | page side padding |
| `--section-y` | `clamp(48px, 6vw, 96px)` | every section's block padding |
| `--section-y-first` | `clamp(40px, 5vw, 64px)` | first section after a hero (top only) |
| `--stack` | `clamp(32px, 4vw, 48px)` | section head → content |
| `--stack-lg` | `clamp(48px, 6vw, 80px)` | block → block inside a section |
| `--header-h` | `76px` | fixed header |
| `--scroll-pad` | `104px` | anchor / sticky offset |
| `--hero-top` | `clamp(104px, 12vh, 140px)` | home hero top |
| `--page-hero-top` | `clamp(120px, 15vh, 152px)` | interior page hero top |

- Container: max `1440px`, gutter both sides.
- **One section rhythm.** Every `.section` uses `padding-block: --section-y`
  at every breakpoint. No `--tight`, `--scan`, per-page or media-query section
  padding. The only exception is the first section after a hero, which uses
  `--section-y-first` on top.
- Section heads use one gap: `margin-bottom: --stack`. Never a per-section
  clamp.
- Two-column: `1fr 1fr`, gap `clamp(32px, 5vw, 80px)`.
- Section head: `1.05fr .95fr`, gap `clamp(32px, 4vw, 64px)`, aligned to the
  baseline of the last line.
- Card grids: 2 or 3 equal columns, gap `--space-5` (24).
- Interior page hero: two columns `1.08fr / 0.92fr`. Eyebrow, title and actions
  on the left; lede and meta on the right; optional preview spans both columns.
  At ≤980 it collapses to one column in DOM order.

**Full-width rule policy.** A rule that touches both viewport edges is a page
boundary, not a section decoration. The complete list is:

| Allowed full-width rule | Where |
|---|---|
| header bottom rule | scrolled header only |
| reading progress | header's bottom edge |
| hero strip top rule | the home hero's signal band |
| footer top rule | above the footer |

Nothing else may use `width: 100vw` or a negative viewport offset. Section
boundaries are whitespace and optional `--tint` background. Lists, tables,
checks and result rows draw rules only inside their own container.
- Breakpoints: 1180 (nav density), 980 (stack two-column), 860 (mobile nav,
  single column), 640 (phone), 380 (hide language switch).
- Minimum supported width: 320px. No horizontal overflow at any width.

### 2.5 Radius, border, elevation

| Token | Value | Use |
|---|---|---|
| `--radius-xs` | `6px` | inline code, tiny tags |
| `--radius-sm` | `10px` | buttons, inputs, compact controls, code blocks |
| `--radius-md` | `16px` | inner surfaces, notes, mock screens, posters |
| `--radius-lg` | `22px` | cards, panels, badges, devices |
| `--radius-pill` | `999px` | chips, progress/section bars |
| `50%` | — | dots, avatars, score rings only |
| `--border` | `2px` | every structural rule and border |
| `--border-accent` | `4px` | blockquote / note left bar |
| `--focus-width` | `3px` | focus ring (offset 3px) |
| `--meter` | `4px` | progress/score bars |
| `--shadow-sm` | `0 10px 30px rgba(23,16,8,.07)` | card hover, scrolled header |
| `--shadow-md` | `0 20px 60px …` | raised hover only |

No 1px borders. No mixed 1px/2px rules. No arbitrary radius between steps.

### 2.6 Gradients

One spectrum ramp, two allowed orientations.

| Token | Definition |
|---|---|
| `--spectrum` | `linear-gradient(135deg, #ffb000 0%, #ff5a00 25%, #e01b7a 50%, #8b1bb0 74%, #2b0b8a 100%)` |
| `--spectrum-soft` | same ramp, softened stops, 135° |
| `--spectrum-line` | same ramp at 90° for 1D bars |

- Surfaces (poster mock, rainbow badge tile, spectrum bar): `--spectrum`.
- 1D fills (reading progress): `--spectrum-line`.
- Marks and SVG brand gradients keep their own generated definitions (see
  `DESIGN.md`). Do not hand-edit them.
- Never use a flat left-to-right ramp where a surface is angled; 135° is the
  house angle.

### 2.7 Motion

| Token | Value | Use |
|---|---|---|
| `--ease` | `cubic-bezier(.22,1,.36,1)` | all UI transitions |
| `--dur` | `240ms` | colour, border, transform |
| `--dur-slow` | `320ms` | menus, overlays |
| `--dur-reveal` | `560ms` | scroll reveal |

- Hover/active: `--dur`.
- The mark's bead and gradient rotation share a 14s clock; see `DESIGN.md`.
- Spinner 800ms; score ring 800ms; hue-flow 6s.
- `prefers-reduced-motion: reduce` removes reveal, swirl and bead motion.

---

## 3. Components

### Header / navigation
- Height 76px, fixed, translucent `--header-bg`, blur 16px, 2px bottom rule
  when scrolled.
- Brand mark 30px; wordmark 12px/600, `--track-caps`, uppercase.
- Nav 12px/600 uppercase, `--track-caps`; gap `clamp(24px, 2.2vw, 32px)`.
- Active page: 6px spectrum dot to the left; hover recolours the text only,
  never the mark's spark.
- Reading progress: 2px `--spectrum-line` on the bottom edge.

### Buttons
| Variant | Height | Padding-inline | Type |
|---|---|---|---|
| Default | 48 | 24 | 12/600 |
| Small | 38 | 16 | 11/600 |
| Compact (badge tools) | 32 | 12 | 10/600 |

- Radius `--radius-sm`, border 2px, gap 8px, arrow shifts 3px on hover.
- Primary: ink fill / paper text, inverts on hover.
- Secondary: transparent / line-strong border.
- Ghost: no border, paper-3 hover.
- Text link: 12/600 with ember arrow, no underline.

### Cards and panels
- Background `--paper-2`, border 2px `--line`, radius `--radius-lg`,
  padding `--space-5`.
- Hover: border `--line-strong`, translateY(-3px), `--shadow-sm`. Hover is
  always local to the card; never paint a full-bleed background behind a row.
- Level cards (home `04`): three equal cards, gap `--space-5`; the count row is
  pinned to the bottom with `margin-top:auto` and a local 2px rule; the title
  recolours to ember on hover. Two columns at 980, one at 640.
- Card title `--fs-h3` or `--fs-h4`; copy `--fs-body-s`; actions pinned to the
  bottom with `margin-top:auto`.
- Panel (scan, score): padding `clamp(var(--space-5), 4vw, var(--space-9))`.

### Placement mocks
- Miniatures use the **mini scale** (2/4/6/8/10/12/16) because they are scaled
  illustrations, not interface. They may use 8–10px type.
- Outer frame 2px `--line`, radius `--radius-md`; stage aspect 4:3, padding 24.
- Meta strip: 10/600 uppercase, padding 12 16, top rule 2px.

### Forms
- Field label 11/600 uppercase, `--track-label`.
- Input: underline only, 2px `--line-strong`, 16px text, padding 12 0;
  focus turns the underline ember. No boxes, no fills.
- Primary scan input: `clamp(18px, 2vw, 25px)` — the only oversized input.
- Form grid gap 24; two-column rows collapse at 640.

### Tables and lists
- Table: 2px outer + row rules, header 10/600 uppercase `--muted`, cells
  13/`--lh-micro`, padding 16. First column 25% ink; highlight column
  `--paper-compare`.
- Rows (checks, results, audit): grid, padding 12 0, top rule 2px, number 11/600
  ember, title 13–14/600.
- Disclosure rows (FAQ): each item is its own card — 2px `--line`,
  `--radius-md`, `--paper` fill, gap `--space-2`; summary padding 16 24,
  plus icon 16px ember, answer 14/1.8. No rules spanning the FAQ column.

### Code
- Block: 2px border, `--radius-sm`, bar padding 8 12, code 11/`--lh-body`
  mono, padding 16.
- Inline code: `--paper-3`, radius 6, padding 2 6.

### Badges
- Preview tile 3-up, gap 16, min-height 180, radius `--radius-lg`.
- Card: dark/light split stage 168px, body padding 24, gap 12.
- Rainbow surfaces use `--spectrum` and the poster two-colour mark.

### Footer
- Top border 2px; top padding `clamp(48px, 6vw, 80px)`; 2-column top grid.
- Column heading 10/600 uppercase; links 12, padding 4 0.
- Utility bars: 11px, padding-block 16, separated by 2px rules.

### Hero
- Home: `--hero-top` top padding; display + copy left, mark spans both text
  rows on the right; strip margin-top `clamp(32px, 6vh, 64px)`.
- Interior: `--page-hero-top`, bottom `clamp(40px, 5vw, 64px)`, 2px bottom
  rule, optional meta row 11/600 uppercase.
- At ≤980 the hero visual stacks below the copy, centred.

---

## 4. Accessibility

- Text contrast ≥ 4.5:1; large text ≥ 3:1; borders ≥ 3:1 where they carry
  meaning.
- Focus ring: 3px `--ember`, 3px offset, on every interactive element.
- Target size ≥ 32px compact / 44px primary.
- No colour-only state: pair with text, icon or position.
- Motion is decorative. Reduced motion swaps the bead for a static twin and
  disables reveal.
- All images have alt text or are `aria-hidden` when decorative.
- No horizontal scroll at 320px; tables scroll inside their own container.

---

## 5. Enforcement

Before shipping a change:

1. `grep` the CSS for raw `#` outside `:root` — must be zero (print and the
   generated SVG gradients excepted). `grep '100vw'` must also be zero; only
   the header, hero strip, progress bar and footer may own a viewport edge.
2. `grep` for `border-radius: [0-9]` — only `50%` and `0` allowed.
3. `grep` for `margin|padding|gap: .*[0-9]px` — every value must be a token, a
   documented clamp, or one of the optical offsets (`-1px`, `-3px`, `2px`).
   `grep '.section.*border-top'` must return nothing.
4. `grep` for `font-size: [0-9]` — allowed only in the mini mockups (`8px`)
   and inline code (`0.9em`); the scan input is the sole oversized clamp.
5. Run the regression suite: axe (22 pages, 0 violations), html-validate,
   overflow 320–1440, link check, functional tests.
6. Screenshot the changed page at 1440 / 980 / 640 / 390 and compare rhythm.
7. Re-run the computed-style audit at 1440: every margin, padding and gap
   resolves to a 4px multiple or a documented fluid clamp.

Change a token, not a component, when the system needs to move. If a new value
is genuinely required, add it to this document and to `:root` in the same
commit.
