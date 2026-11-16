<?php
declare(strict_types=1);
$r = $T['resources'];
$downloads = require APP_ROOT . '/data/resources.php';
$codeRobots = <<<'TXT'
# robots.txt — AI Friendly starter
# Replace example.com. Keep this file at the site root.

User-agent: *
Allow: /

# Named AI agents. Use Disallow: / to exclude an agent.
User-agent: GPTBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: CCBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: Applebot-Extended
Allow: /

Sitemap: https://example.com/sitemap.xml
TXT;
$codeLlms = <<<'TXT'
# Example Company

> One sentence that explains what this site is and who it is for.

Key facts: founded in 2021, based in Lisbon, API at /v1.
Canonical domain: https://example.com

## Docs
- [Getting started](https://example.com/docs/start): install, auth, first request.
- [API reference](https://example.com/docs/api): endpoints, scopes, rate limits.
- [Pricing](https://example.com/pricing): plans, limits, overage.

## Policies
- [AI crawler policy](https://example.com/ai-policy): what is allowed and why.
- [Privacy](https://example.com/privacy): what we collect, what we do not.
- [Terms](https://example.com/terms): the contract.

## Optional
- [Changelog](https://example.com/changelog): versioned changes.
- [Status](https://example.com/status): current availability.
TXT;
$codeJsonld = <<<'JSON'
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://example.com/#org",
      "name": "Example Company",
      "url": "https://example.com/",
      "logo": "https://example.com/logo.png",
      "description": "What the organisation does, in one sentence.",
      "sameAs": ["https://github.com/example"]
    },
    {
      "@type": "WebSite",
      "@id": "https://example.com/#site",
      "url": "https://example.com/",
      "name": "Example Company",
      "publisher": { "@id": "https://example.com/#org" },
      "inLanguage": "en"
    }
  ]
}
JSON;
$codeSec = <<<'TXT'
Contact: https://example.com/security
Expires: 2027-12-31T23:59:59.000Z
Preferred-Languages: en
Canonical: https://example.com/.well-known/security.txt
Policy: https://example.com/security-policy
Acknowledgments: https://example.com/security/hall-of-fame
TXT;
$codeOas = <<<'YAML'
openapi: 3.1.0
info:
  title: Example API
  version: "1.0"
  description: |
    Public API for Example Company. Rate limit: 60 requests/minute
    per token. Errors use RFC 9457 problem details.
servers:
  - url: https://api.example.com/v1
security:
  - bearerAuth: []
paths:
  /things:
    get:
      summary: List things
      operationId: listThings
      parameters:
        - name: limit
          in: query
          schema: { type: integer, minimum: 1, maximum: 100, default: 20 }
      responses:
        "200":
          description: A page of things
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items: { $ref: "#/components/schemas/Thing" }
        "429":
          description: Rate limited
          content:
            application/problem+json:
              schema: { $ref: "#/components/schemas/Problem" }
components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
  schemas:
    Thing:
      type: object
      required: [id, name]
      properties:
        id: { type: string, example: th_01J2ABCDEF }
        name: { type: string }
    Problem:
      type: object
      required: [type, title, status]
      properties:
        type: { type: string, format: uri }
        title: { type: string }
        status: { type: integer }
        detail: { type: string }
YAML;
$samples = [
    ['robots', '/robots.txt', $codeRobots, $r['starters']['robots']],
    ['llms', '/llms.txt', $codeLlms, $r['starters']['llms']],
    ['jsonld', 'organization.jsonld', $codeJsonld, $r['starters']['jsonld']],
    ['sec', '/.well-known/security.txt', $codeSec, $r['starters']['security']],
    ['oas', 'openapi.yaml', $codeOas, $r['starters']['openapi']],
];
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $r['hero']['eyebrow'] ?></p>
    <h1><?= $r['hero']['title'] ?></h1>
    <p class="lede"><?= e($r['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($r['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--flush" id="downloads">
  <div class="wrap">
    <?= section_head($r['downloads']['eyebrow'], $r['downloads']['title'], $r['downloads']['lede']) ?>
    <div class="resource-grid">
      <?php foreach ($downloads as $item): ?>
      <article class="resource-card">
        <p class="micro"><?= e($item['meta']) ?></p>
        <h3><?= e(tr($item['title'])) ?></h3>
        <p><?= e(tr($item['desc'])) ?></p>
        <div class="resource-card__files">
          <?php foreach ($item['files'] as [$label, $href]): ?>
          <a href="<?= e($href) ?>" download><?= e($label) ?><?= arrow_icon() ?></a>
          <?php endforeach; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tint" id="starters">
  <div class="wrap">
    <?= section_head($r['starters']['eyebrow'], $r['starters']['title'], $r['starters']['lede']) ?>
    <div class="code-sections">
      <?php foreach ($samples as [$id, $file, $code, $copy]): ?>
      <div class="code-section">
        <div class="code-section__head">
          <div><h3><?= e($copy['title']) ?></h3><p><?= links($copy['lede']) ?></p></div>
        </div>
        <div class="code-block">
          <div class="code-block__bar"><span><?= e($file) ?></span><button class="copy-btn" type="button" data-copy-target="#code-<?= e($id) ?>"><?= copy_icon() ?><?= e(t('common.cta.copy')) ?></button></div>
          <pre tabindex="0" id="code-<?= e($id) ?>"><code><?= e($code) ?></code></pre>
        </div>
        <?php if (!empty($copy['note'])): ?><div class="note mt-s"><p><?= links($copy['note']) ?></p></div><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="use">
  <div class="wrap">
    <?= section_head($r['use']['eyebrow'], $r['use']['title']) ?>
    <div class="grid-3">
      <?php foreach ($r['use']['items'] as [$num, $title, $text]): ?>
      <article class="info-card"><p class="micro"><?= e($num) ?></p><h3><?= e($title) ?></h3><p><?= links($text) ?></p></article>
      <?php endforeach; ?>
    </div>
    <div class="note mt-l"><p><?= links($r['license']) ?></p></div>
  </div>
</section>

<section class="section section--cta">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($r['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($r['cta']['title']) ?></h2>
      <p class="lede"><?= e($r['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($r['cta']['primary']), url('/scan/'), 'primary') ?>
        <?= btn(e($r['cta']['secondary']), url('/standard/#audit'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>
