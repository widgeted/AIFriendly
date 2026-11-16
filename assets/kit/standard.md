# The AI Friendly Standard v0.1

**Status:** Public draft · **Published:** 15 November 2026 · **License:** CC BY 4.0
**Canonical URL:** https://aifriendly.fyi/standard/

> A practical, open standard for sites and services that want to work with AI agents — without giving up control of their content, data or users.

## What this is
The AI Friendly Standard describes what “friendly to AI” means in practice. It is not a test for whether content was made by AI. It is a checklist for making a site or service legible, operable and safe when an AI agent — or a person using one — arrives.

It is organised in three levels. Each level is useful on its own. Level 1 is about being read. Level 2 is about being used. Level 3 is about being trusted with actions.

The standard is deliberately technology-neutral. It names established formats where they exist — robots.txt (RFC 9309), sitemap.xml, schema.org, OpenAPI, RFC 9457, security.txt (RFC 9116), WCAG 2.2 — and treats emerging ones such as llms.txt and MCP as recommended where useful, not mandatory.

It does not require you to give content away. A service can be fully AI-friendly while denying every AI crawler. What matters is that the policy is explicit, the terms are readable, and agents cannot stumble into permissions you never granted.

## Principles
1. **Declare, don't drift** — A clear policy is better than an accidental one. Allow or deny, but say so.
2. **Readable first** — If a machine cannot read the page, nothing else matters.
3. **Consent is architecture** — Openness is not a licence. Agents act within stated permissions.
4. **Humans hold the keys** — Irreversible, destructive or paid actions need a human decision.
5. **Same web, both audiences** — Accessibility and machine legibility are the same work.
6. **No certification theatre** — Automated checks are evidence, not proof. Verified means reviewed.

## Level 1 — Readable

The foundation. No special API, no agent integration, no new protocol. Just a web page that says what it is, in a form both a person and a parser can follow.

### R1. Primary content is server-rendered

The main text and links are present in the HTML response, not assembled only by client-side JavaScript.

**Why.** Many agents and crawlers fetch HTML without running a browser. If your content only exists after JavaScript, you are invisible to them.

**How to check.** Disable JavaScript, or run curl and search the response for the headline and body copy. If they are missing, add server-side rendering, static generation or prerendering. Some sites also offer Accept: text/markdown content negotiation; it is a useful extra, not a requirement.

### R2. Semantic structure and headings

One clear H1, a logical heading order, and landmarks for header, navigation, main content and footer.

**Why.** Structure is how a model knows what is a title, a section, a menu or a footnote. A div with a large font carries no meaning.

**How to check.** Disable CSS. The page should still read in a sensible order. Check for one h1 per page, no skipped heading levels, and main, nav, header and footer elements.

### R3. Unique title and description

Every page has a unique, descriptive title and a meta description that summarises the page, not the brand.

**Why.** Titles and descriptions are the most-used summary fields in search, browsing and agent tooling.

**How to check.** Inspect the document head. They should be unique across the site and specific enough to identify the page without its URL.

### R4. Canonical URLs and stable permalinks

Pages declare a canonical URL and live at stable, human-readable addresses.

**Why.** Duplicate URLs split signals; changing URLs break references. Agents cache and cite what they find.

**How to check.** Look for link rel=canonical and confirm it matches the preferred URL. Old URLs should 301-redirect rather than disappear.

### R5. robots.txt and an XML sitemap

A robots.txt file at the root and a sitemap listing your canonical pages.

**Why.** robots.txt is the standard way to state crawler rules (RFC 9309). A sitemap makes discovery deterministic.

**How to check.** Fetch /robots.txt and confirm it references your sitemap; fetch the sitemap and check that it returns valid XML with canonical URLs.

### R6. An explicit AI crawler policy

robots.txt names the AI agents you allow or disallow, rather than relying only on a wildcard.

**Why.** Vendors use different user agents. Naming them makes your intent auditable. A deliberate denial is valid; silence is not a policy.

**How to check.** Add User-agent groups for the agents you care about, with Allow or Disallow. Keep the file valid and explain your position publicly. robots.txt is widely respected, not legally enforced.

### R7. Structured data

JSON-LD describes the organisation, the page and the things on it — Article, Product, Event, FAQ, BreadcrumbList — using schema.org.

**Why.** It gives machines typed facts instead of guesses: who published this, what it is, what it costs.

**How to check.** Validate with the Schema Markup Validator or Google Rich Results Test. Use only properties that are true and visible on the page.

### R8. Text alternatives for media

Images have useful alt text; video has captions; audio has transcripts; charts have a text summary.

**Why.** Alt text is read by screen readers and models alike. Decorative images should have an empty alt, not a filename.

**How to check.** Check that every meaningful image has alt text, every video has captions, and every audio file has a transcript.

### R9. No wall on public content

Content published for everyone is reachable without a login, cookie wall, newsletter interstitial or app install.

**Why.** A wall is a wall, whether the visitor is a person or an agent. If the content is public, make it public.

**How to check.** Fetch the URL in a clean session. If a modal or redirect blocks the content, it is not readable. If content is intentionally private, say so and keep it out of the public index.

### R10. Language, charset and link previews

Pages declare their language and charset, and provide Open Graph metadata for clean previews.

**Why.** Language and charset prevent mangled text; OG tags make links legible when shared or summarised.

**How to check.** Check html lang, meta charset, og:title, og:description, og:image (1200×630) and og:url.

## Level 2 — Actionable

Reading is not enough. At this level you publish a contract: what can be done, by whom, at what price, with what limits, and what happens when something fails.

### A1. A public machine-readable interface

Publish a documented API, or at minimum a structured feed (JSON, RSS, Atom) for the content you want machines to use.

**Why.** Scraping HTML is fragile. A feed or API is a contract.

**How to check.** Document the endpoint, authentication and limits. Confirm it returns structured data with a stable schema.

### A2. A standard API description

Describe the API in a standard format — OpenAPI 3.1, a GraphQL schema, or a published MCP tool manifest — at a stable URL.

**Why.** Agents can discover operations without reverse-engineering your JavaScript.

**How to check.** Link the description from your docs and from /llms.txt. Validate an OpenAPI file with a linter.

### A3. Machine-readable pricing and availability

Public prices, plans, limits and availability are available as text or structured data, not only inside an image or a sales call.

**Why.** An agent cannot quote a price it cannot read. Opaque pricing forces a human handoff.

**How to check.** Publish a pricing page in plain text and, where relevant, schema.org Offer or PriceSpecification.

### A4. Stable identifiers and versioning

Resources have stable IDs; APIs are versioned; deprecations have a published timeline.

**Why.** Agents store references. Silent breaking changes turn automation into incidents.

**How to check.** Use version prefixes such as /v1/, immutable IDs, and a changelog with deprecation dates.

### A5. Authentication an agent can use

Support API keys or OAuth 2.0 with scoped tokens. Do not require a browser-only flow for machine actions.

**Why.** If the only way in is a human clicking through a UI, the service is not actionable.

**How to check.** Document the token flow, scopes and expiry. Use PKCE for public clients. Never put long-lived secrets in client code.

### A6. Rate limits and standard errors

Publish rate limits and return machine-readable errors, ideally RFC 9457 problem details.

**Why.** Agents need to back off intelligently and explain failures to their users.

**How to check.** Document limits and 429 behaviour. Return application/problem+json with type, title, status and detail.

### A7. A route for agent issues

Provide a named contact or page for agent-related problems, abuse reports and access questions.

**Why.** When an agent does something wrong, a human needs a path to a human.

**How to check.** Publish a /agents page with expectations and response times, and a reachable address on your domain.

## Level 3 — Agent-ready

This is where most of the risk lives. Acting on the web means spending money, changing data, sending messages and deleting things. The checks here are about brakes, receipts and exit.

### G1. A tool manifest or MCP server

Publish a machine-discoverable tool manifest — for example an MCP server — that lists capabilities, inputs and limits.

**Why.** A manifest turns a website into a tool an agent can call correctly instead of guessing at UI automation.

**How to check.** Document the server URL, transport, authentication, tools and example calls. Keep the manifest versioned.

### G2. Scoped permissions and confirmations

Agents receive the minimum scopes needed. Destructive, irreversible or paid actions require explicit human confirmation.

**Why.** Autonomy without a brake is a liability. Confirmation is the brake.

**How to check.** Separate read and write scopes. Require a fresh confirmation for payments, deletions and permission changes. Make the consequence explicit.

### G3. A user-visible audit log

Users can see what an agent did, when, and on whose behalf.

**Why.** Trust requires a receipt. An audit trail makes errors discoverable and reversible.

**How to check.** Log actor, action, target, timestamp and result. Show it in the account UI and make it exportable.

### G4. Export and deletion

Users can export their data and request deletion programmatically, within a stated timeframe.

**Why.** Portability and exit are part of consent. They are also increasingly legal requirements.

**How to check.** Document the endpoints or process, formats, timeframes and confirmation.

### G5. Provenance and AI disclosure

Where AI materially shapes content, say so. Use provenance standards such as C2PA for media when appropriate.

**Why.** People and agents both need to know what they are looking at. Disclosure is not the same as detection.

**How to check.** Add a clear disclosure near the content, retain C2PA manifests, and never claim human authorship you cannot support.

### G6. A security baseline

TLS everywhere, a /.well-known/security.txt, no secrets in client code, input validation, and a vulnerability disclosure route.

**Why.** An agent-ready service is an attack surface. Basic hygiene is a prerequisite, not a bonus.

**How to check.** Publish security.txt (RFC 9116), scan dependencies, rotate keys, and test authorization boundaries.

### G7. WCAG 2.2 AA

Meet WCAG 2.2 AA for the public experience.

**Why.** Machine legibility and human accessibility share the same foundations: structure, labels, alternatives and predictable behaviour.

**How to check.** Audit with keyboard-only navigation, a screen reader and automated checks; fix the manual issues automation misses.

## Claiming it

### Use the badge

*Self-declared / free*

Download the mark, use it on your site or product, and state which level you meet. No application, no fee, no permission needed. You are responsible for the claim.

### Apply for review

*Verified / beta*

The verified mark is for services that can show evidence: a completed self-audit, a public API or tool manifest, and the safety checks in Level 3. A human reviews the application; the mark is re-checked annually.

The honest part. A self-declared badge is a claim, not an audit. We do not check every site that displays one. Only the verified mark is reviewed, and only the verified mark may say “verified”. If you see the verified mark used without a listing, tell us.

## Sources

- RFC 9309 — Robots Exclusion Protocol: https://www.rfc-editor.org/rfc/rfc9309
- sitemaps.org — XML sitemap protocol: https://www.sitemaps.org/protocol.html
- schema.org — structured data vocabulary: https://schema.org/
- OpenAPI Specification 3.1: https://spec.openapis.org/oas/v3.1.0
- RFC 9457 — Problem Details for HTTP APIs: https://www.rfc-editor.org/rfc/rfc9457
- RFC 9116 — security.txt: https://www.rfc-editor.org/rfc/rfc9116
- WCAG 2.2: https://www.w3.org/TR/WCAG22/
- llms.txt — an emerging proposal: https://llmstxt.org/
- Model Context Protocol (MCP): https://modelcontextprotocol.io/
- C2PA — content provenance: https://c2pa.org/
- Community list of AI crawler user agents: https://github.com/ai-robots-txt/ai.robots.txt

---

The AI Friendly Standard v0.1 by AI Friendly (https://aifriendly.fyi) is licensed under CC BY 4.0. Suggested attribution: “Based on The AI Friendly Standard v0.1 by AI Friendly (aifriendly.fyi), licensed CC BY 4.0.”
