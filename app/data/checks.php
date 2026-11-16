<?php
declare(strict_types=1);

/**
 * The 24 checks of The AI Friendly Standard v0.1.
 * Each check feeds both the standard page and the self-audit.
 */
return [
    [
        'id' => 'r1', 'level' => 1,
        'title' => ['en' => 'Primary content is server-rendered', 'zh' => '主内容由服务端渲染'],
        'desc' => ['en' => 'The main text and links are present in the HTML response, not assembled only by client-side JavaScript.', 'zh' => '主要文字与链接直接出现在 HTML 响应中，而不是只靠客户端 JavaScript 拼装。'],
        'why' => ['en' => 'Many agents and crawlers fetch HTML without running a browser. If your content only exists after JavaScript, you are invisible to them.', 'zh' => '许多智能体和爬虫只抓取 HTML，不运行浏览器。如果内容只在 JavaScript 执行后出现，它们就看不见你。'],
        'how' => ['en' => 'Disable JavaScript, or run curl and search the response for the headline and body copy. If they are missing, add server-side rendering, static generation or prerendering. Some sites also offer Accept: text/markdown content negotiation; it is a useful extra, not a requirement.', 'zh' => '关闭 JavaScript，或用 curl 抓取页面，检查标题和正文是否在响应里。如果不在，请加入服务端渲染、静态生成或预渲染。有些网站还会通过 Accept: text/markdown 做内容协商；这是加分项，不是必需项。'],
    ],
    [
        'id' => 'r2', 'level' => 1,
        'title' => ['en' => 'Semantic structure and headings', 'zh' => '语义化结构与标题层级'],
        'desc' => ['en' => 'One clear H1, a logical heading order, and landmarks for header, navigation, main content and footer.', 'zh' => '一个清晰的 H1、合理的标题顺序，以及 header、nav、main、footer 等语义地标。'],
        'why' => ['en' => 'Structure is how a model knows what is a title, a section, a menu or a footnote. A div with a large font carries no meaning.', 'zh' => '结构让模型知道什么是标题、章节、菜单和脚注。一个字体很大的 div 并不携带这些含义。'],
        'how' => ['en' => 'Disable CSS. The page should still read in a sensible order. Check for one h1 per page, no skipped heading levels, and main, nav, header and footer elements.', 'zh' => '关闭 CSS，页面仍应按合理顺序阅读。检查每页只有一个 h1、没有跳级标题，并使用了 main、nav、header、footer。'],
    ],
    [
        'id' => 'r3', 'level' => 1,
        'title' => ['en' => 'Unique title and description', 'zh' => '唯一的标题与描述'],
        'desc' => ['en' => 'Every page has a unique, descriptive title and a meta description that summarises the page, not the brand.', 'zh' => '每个页面都有唯一、具体的 title，以及概括本页内容的 meta description，而不是只写品牌名。'],
        'why' => ['en' => 'Titles and descriptions are the most-used summary fields in search, browsing and agent tooling.', 'zh' => '标题和描述是搜索、浏览与智能体工具最常使用的摘要字段。'],
        'how' => ['en' => 'Inspect the document head. They should be unique across the site and specific enough to identify the page without its URL.', 'zh' => '检查文档 head。它们应在全站唯一，并且不看 URL 也能识别页面。'],
    ],
    [
        'id' => 'r4', 'level' => 1,
        'title' => ['en' => 'Canonical URLs and stable permalinks', 'zh' => '规范链接与稳定永久地址'],
        'desc' => ['en' => 'Pages declare a canonical URL and live at stable, human-readable addresses.', 'zh' => '页面声明规范 URL，并使用稳定、人类可读的地址。'],
        'why' => ['en' => 'Duplicate URLs split signals; changing URLs break references. Agents cache and cite what they find.', 'zh' => '重复 URL 会分散信号，频繁改地址会破坏引用。智能体会缓存并引用它找到的东西。'],
        'how' => ['en' => 'Look for link rel=canonical and confirm it matches the preferred URL. Old URLs should 301-redirect rather than disappear.', 'zh' => '检查 link rel=canonical 是否指向首选地址。旧地址应 301 跳转，而不是直接消失。'],
    ],
    [
        'id' => 'r5', 'level' => 1,
        'title' => ['en' => 'robots.txt and an XML sitemap', 'zh' => 'robots.txt 与 XML 站点地图'],
        'desc' => ['en' => 'A robots.txt file at the root and a sitemap listing your canonical pages.', 'zh' => '根目录有 robots.txt，并提供列出规范页面的站点地图。'],
        'why' => ['en' => 'robots.txt is the standard way to state crawler rules (RFC 9309). A sitemap makes discovery deterministic.', 'zh' => 'robots.txt 是声明爬虫规则的标准方式（RFC 9309）。站点地图让发现页面变得确定。'],
        'how' => ['en' => 'Fetch /robots.txt and confirm it references your sitemap; fetch the sitemap and check that it returns valid XML with canonical URLs.', 'zh' => '抓取 /robots.txt，确认它引用了站点地图；再抓取站点地图，检查 XML 是否有效、URL 是否为规范地址。'],
    ],
    [
        'id' => 'r6', 'level' => 1,
        'title' => ['en' => 'An explicit AI crawler policy', 'zh' => '明确的 AI 爬虫政策'],
        'desc' => ['en' => 'robots.txt names the AI agents you allow or disallow, rather than relying only on a wildcard.', 'zh' => 'robots.txt 明确写出你允许或拒绝的 AI 智能体，而不是只依赖通配符。'],
        'why' => ['en' => 'Vendors use different user agents. Naming them makes your intent auditable. A deliberate denial is valid; silence is not a policy.', 'zh' => '不同厂商使用不同 user agent。点名写出它们，意图才可审计。明确拒绝是有效的；沉默不是政策。'],
        'how' => ['en' => 'Add User-agent groups for the agents you care about, with Allow or Disallow. Keep the file valid and explain your position publicly. robots.txt is widely respected, not legally enforced.', 'zh' => '为你关心的智能体添加 User-agent 分组，并写 Allow 或 Disallow。保持文件有效，并公开解释你的立场。robots.txt 被广泛遵守，但不是法律强制。'],
    ],
    [
        'id' => 'r7', 'level' => 1,
        'title' => ['en' => 'Structured data', 'zh' => '结构化数据'],
        'desc' => ['en' => 'JSON-LD describes the organisation, the page and the things on it — Article, Product, Event, FAQ, BreadcrumbList — using schema.org.', 'zh' => '用 JSON-LD 描述组织、页面及页面上的事物（Article、Product、Event、FAQ、BreadcrumbList），词汇来自 schema.org。'],
        'why' => ['en' => 'It gives machines typed facts instead of guesses: who published this, what it is, what it costs.', 'zh' => '它把“谁发布、这是什么、多少钱”变成有类型的事实，而不是猜测。'],
        'how' => ['en' => 'Validate with the Schema Markup Validator or Google Rich Results Test. Use only properties that are true and visible on the page.', 'zh' => '用 Schema Markup Validator 或 Google Rich Results Test 校验。只使用页面真实存在且可见的属性。'],
    ],
    [
        'id' => 'r8', 'level' => 1,
        'title' => ['en' => 'Text alternatives for media', 'zh' => '媒体的文本替代'],
        'desc' => ['en' => 'Images have useful alt text; video has captions; audio has transcripts; charts have a text summary.', 'zh' => '图片有有用的 alt 文本；视频有字幕；音频有文字稿；图表有文字摘要。'],
        'why' => ['en' => 'Alt text is read by screen readers and models alike. Decorative images should have an empty alt, not a filename.', 'zh' => '屏幕阅读器和模型都会读取 alt。装饰图片应使用空 alt，而不是文件名。'],
        'how' => ['en' => 'Check that every meaningful image has alt text, every video has captions, and every audio file has a transcript.', 'zh' => '检查每张有意义的图片都有 alt，每个视频都有字幕，每个音频都有文字稿。'],
    ],
    [
        'id' => 'r9', 'level' => 1,
        'title' => ['en' => 'No wall on public content', 'zh' => '公开内容不设墙'],
        'desc' => ['en' => 'Content published for everyone is reachable without a login, cookie wall, newsletter interstitial or app install.', 'zh' => '面向公众发布的内容，无需登录、Cookie 墙、订阅弹窗或安装 App 即可访问。'],
        'why' => ['en' => 'A wall is a wall, whether the visitor is a person or an agent. If the content is public, make it public.', 'zh' => '无论访客是人还是智能体，墙就是墙。内容若是公开的，就让它真正公开。'],
        'how' => ['en' => 'Fetch the URL in a clean session. If a modal or redirect blocks the content, it is not readable. If content is intentionally private, say so and keep it out of the public index.', 'zh' => '在干净会话中抓取 URL。如果弹窗或跳转挡住内容，它就不可读。若内容有意设为私有，请明确说明并不要放进公开索引。'],
    ],
    [
        'id' => 'r10', 'level' => 1,
        'title' => ['en' => 'Language, charset and link previews', 'zh' => '语言、字符集与链接预览'],
        'desc' => ['en' => 'Pages declare their language and charset, and provide Open Graph metadata for clean previews.', 'zh' => '页面声明语言与字符集，并提供 Open Graph metadata，让链接预览干净可靠。'],
        'why' => ['en' => 'Language and charset prevent mangled text; OG tags make links legible when shared or summarised.', 'zh' => '语言和字符集防止乱码；OG 标签让链接在分享或摘要时更易读。'],
        'how' => ['en' => 'Check html lang, meta charset, og:title, og:description, og:image (1200×630) and og:url.', 'zh' => '检查 html lang、meta charset、og:title、og:description、og:image（1200×630）和 og:url。'],
    ],

    [
        'id' => 'a1', 'level' => 2,
        'title' => ['en' => 'A public machine-readable interface', 'zh' => '公开的机器可读接口'],
        'desc' => ['en' => 'Publish a documented API, or at minimum a structured feed (JSON, RSS, Atom) for the content you want machines to use.', 'zh' => '发布有文档的 API；至少为你希望机器使用的内容提供结构化 feed（JSON、RSS、Atom）。'],
        'why' => ['en' => 'Scraping HTML is fragile. A feed or API is a contract.', 'zh' => '抓取 HTML 很脆弱。feed 或 API 才是一份契约。'],
        'how' => ['en' => 'Document the endpoint, authentication and limits. Confirm it returns structured data with a stable schema.', 'zh' => '记录端点、认证和限制，并确认它返回结构稳定、可解析的数据。'],
    ],
    [
        'id' => 'a2', 'level' => 2,
        'title' => ['en' => 'A standard API description', 'zh' => '标准 API 描述'],
        'desc' => ['en' => 'Describe the API in a standard format — OpenAPI 3.1, a GraphQL schema, or a published MCP tool manifest — at a stable URL.', 'zh' => '用标准格式描述 API——OpenAPI 3.1、GraphQL schema 或公开的 MCP 工具清单——并放在稳定 URL。'],
        'why' => ['en' => 'Agents can discover operations without reverse-engineering your JavaScript.', 'zh' => '智能体可以发现能做什么，而不必逆向你的 JavaScript。'],
        'how' => ['en' => 'Link the description from your docs and from /llms.txt. Validate an OpenAPI file with a linter.', 'zh' => '从文档和 /llms.txt 链接该描述。用 linter 校验 OpenAPI 文件。'],
    ],
    [
        'id' => 'a3', 'level' => 2,
        'title' => ['en' => 'Machine-readable pricing and availability', 'zh' => '机器可读的价格与可用性'],
        'desc' => ['en' => 'Public prices, plans, limits and availability are available as text or structured data, not only inside an image or a sales call.', 'zh' => '公开价格、套餐、限制与可用性以文本或结构化数据呈现，而不是只放在图片或销售电话里。'],
        'why' => ['en' => 'An agent cannot quote a price it cannot read. Opaque pricing forces a human handoff.', 'zh' => '智能体无法引用它读不到的价格。不透明的定价会迫使人来接手。'],
        'how' => ['en' => 'Publish a pricing page in plain text and, where relevant, schema.org Offer or PriceSpecification.', 'zh' => '用纯文本发布定价页，并在合适时使用 schema.org Offer 或 PriceSpecification。'],
    ],
    [
        'id' => 'a4', 'level' => 2,
        'title' => ['en' => 'Stable identifiers and versioning', 'zh' => '稳定标识符与版本管理'],
        'desc' => ['en' => 'Resources have stable IDs; APIs are versioned; deprecations have a published timeline.', 'zh' => '资源有稳定 ID；API 有版本；弃用有公开时间表。'],
        'why' => ['en' => 'Agents store references. Silent breaking changes turn automation into incidents.', 'zh' => '智能体会保存引用。无声的破坏性变更会把自动化变成事故。'],
        'how' => ['en' => 'Use version prefixes such as /v1/, immutable IDs, and a changelog with deprecation dates.', 'zh' => '使用 /v1/ 之类的版本前缀、不可变 ID，以及带弃用日期的 changelog。'],
    ],
    [
        'id' => 'a5', 'level' => 2,
        'title' => ['en' => 'Authentication an agent can use', 'zh' => '智能体可用的认证'],
        'desc' => ['en' => 'Support API keys or OAuth 2.0 with scoped tokens. Do not require a browser-only flow for machine actions.', 'zh' => '支持 API key 或带作用域令牌的 OAuth 2.0。不要让机器操作只能通过浏览器流程完成。'],
        'why' => ['en' => 'If the only way in is a human clicking through a UI, the service is not actionable.', 'zh' => '如果唯一入口是人类点击 UI，这个服务就谈不上可操作。'],
        'how' => ['en' => 'Document the token flow, scopes and expiry. Use PKCE for public clients. Never put long-lived secrets in client code.', 'zh' => '记录令牌流程、作用域和有效期。公共客户端使用 PKCE。不要把长期密钥放进客户端代码。'],
    ],
    [
        'id' => 'a6', 'level' => 2,
        'title' => ['en' => 'Rate limits and standard errors', 'zh' => '速率限制与标准错误'],
        'desc' => ['en' => 'Publish rate limits and return machine-readable errors, ideally RFC 9457 problem details.', 'zh' => '公开速率限制，并返回机器可读的错误，最好是 RFC 9457 problem details。'],
        'why' => ['en' => 'Agents need to back off intelligently and explain failures to their users.', 'zh' => '智能体需要智能退避，并向用户解释失败原因。'],
        'how' => ['en' => 'Document limits and 429 behaviour. Return application/problem+json with type, title, status and detail.', 'zh' => '记录限制与 429 行为。返回 application/problem+json，包含 type、title、status、detail。'],
    ],
    [
        'id' => 'a7', 'level' => 2,
        'title' => ['en' => 'A route for agent issues', 'zh' => '智能体问题反馈渠道'],
        'desc' => ['en' => 'Provide a named contact or page for agent-related problems, abuse reports and access questions.', 'zh' => '为智能体相关问题、滥用举报和访问咨询提供明确的联系人或页面。'],
        'why' => ['en' => 'When an agent does something wrong, a human needs a path to a human.', 'zh' => '当智能体做错事时，人类需要一条找到人类的路径。'],
        'how' => ['en' => 'Publish a /agents page with expectations and response times, and a reachable address on your domain.', 'zh' => '发布 /agents 页面，写明预期与响应时间，并提供一个可用的域名邮箱地址。'],
    ],

    [
        'id' => 'g1', 'level' => 3,
        'title' => ['en' => 'A tool manifest or MCP server', 'zh' => '工具清单或 MCP 服务'],
        'desc' => ['en' => 'Publish a machine-discoverable tool manifest — for example an MCP server — that lists capabilities, inputs and limits.', 'zh' => '发布机器可发现的工具清单——例如 MCP 服务——列出能力、输入和限制。'],
        'why' => ['en' => 'A manifest turns a website into a tool an agent can call correctly instead of guessing at UI automation.', 'zh' => '清单把网站变成智能体能正确调用的工具，而不是让它猜 UI 自动化。'],
        'how' => ['en' => 'Document the server URL, transport, authentication, tools and example calls. Keep the manifest versioned.', 'zh' => '记录服务 URL、传输方式、认证、工具和调用示例，并给清单做版本管理。'],
    ],
    [
        'id' => 'g2', 'level' => 3,
        'title' => ['en' => 'Scoped permissions and confirmations', 'zh' => '最小权限与人工确认'],
        'desc' => ['en' => 'Agents receive the minimum scopes needed. Destructive, irreversible or paid actions require explicit human confirmation.', 'zh' => '智能体只获得最低限度的作用域。破坏性、不可逆或付费操作必须得到明确的人工确认。'],
        'why' => ['en' => 'Autonomy without a brake is a liability. Confirmation is the brake.', 'zh' => '没有刹车的自主性是负债。确认就是刹车。'],
        'how' => ['en' => 'Separate read and write scopes. Require a fresh confirmation for payments, deletions and permission changes. Make the consequence explicit.', 'zh' => '区分读写作用域。支付、删除和权限变更都要重新确认，并明确说明后果。'],
    ],
    [
        'id' => 'g3', 'level' => 3,
        'title' => ['en' => 'A user-visible audit log', 'zh' => '用户可见的审计日志'],
        'desc' => ['en' => 'Users can see what an agent did, when, and on whose behalf.', 'zh' => '用户可以看到智能体做了什么、何时做的、代表谁做的。'],
        'why' => ['en' => 'Trust requires a receipt. An audit trail makes errors discoverable and reversible.', 'zh' => '信任需要回执。审计轨迹让错误可发现、可挽回。'],
        'how' => ['en' => 'Log actor, action, target, timestamp and result. Show it in the account UI and make it exportable.', 'zh' => '记录行为者、动作、目标、时间和结果，在账户界面展示，并允许导出。'],
    ],
    [
        'id' => 'g4', 'level' => 3,
        'title' => ['en' => 'Export and deletion', 'zh' => '导出与删除'],
        'desc' => ['en' => 'Users can export their data and request deletion programmatically, within a stated timeframe.', 'zh' => '用户可以在明确时限内以程序化方式导出数据并请求删除。'],
        'why' => ['en' => 'Portability and exit are part of consent. They are also increasingly legal requirements.', 'zh' => '可携带与可退出是同意的一部分，也正成为越来越多地区的法律要求。'],
        'how' => ['en' => 'Document the endpoints or process, formats, timeframes and confirmation.', 'zh' => '记录端点或流程、格式、时限和确认方式。'],
    ],
    [
        'id' => 'g5', 'level' => 3,
        'title' => ['en' => 'Provenance and AI disclosure', 'zh' => '来源与 AI 披露'],
        'desc' => ['en' => 'Where AI materially shapes content, say so. Use provenance standards such as C2PA for media when appropriate.', 'zh' => '当 AI 实质参与内容生产时，明确披露。媒体内容可在合适时使用 C2PA 等来源标准。'],
        'why' => ['en' => 'People and agents both need to know what they are looking at. Disclosure is not the same as detection.', 'zh' => '人和智能体都需要知道自己在看什么。披露不等于检测。'],
        'how' => ['en' => 'Add a clear disclosure near the content, retain C2PA manifests, and never claim human authorship you cannot support.', 'zh' => '在内容附近做清晰披露，保留 C2PA 清单，不要声称无法证明的人类作者身份。'],
    ],
    [
        'id' => 'g6', 'level' => 3,
        'title' => ['en' => 'A security baseline', 'zh' => '安全基线'],
        'desc' => ['en' => 'TLS everywhere, a /.well-known/security.txt, no secrets in client code, input validation, and a vulnerability disclosure route.', 'zh' => '全站 TLS、/.well-known/security.txt、客户端无密钥、输入校验，以及漏洞披露渠道。'],
        'why' => ['en' => 'An agent-ready service is an attack surface. Basic hygiene is a prerequisite, not a bonus.', 'zh' => '面向智能体的服务就是攻击面。基础卫生是前提，不是加分项。'],
        'how' => ['en' => 'Publish security.txt (RFC 9116), scan dependencies, rotate keys, and test authorization boundaries.', 'zh' => '发布 security.txt（RFC 9116），扫描依赖，轮换密钥，并测试授权边界。'],
    ],
    [
        'id' => 'g7', 'level' => 3,
        'title' => ['en' => 'WCAG 2.2 AA', 'zh' => 'WCAG 2.2 AA'],
        'desc' => ['en' => 'Meet WCAG 2.2 AA for the public experience.', 'zh' => '公开体验达到 WCAG 2.2 AA。'],
        'why' => ['en' => 'Machine legibility and human accessibility share the same foundations: structure, labels, alternatives and predictable behaviour.', 'zh' => '机器可读与人类可访问共享同一套基础：结构、标签、替代文本和可预测的行为。'],
        'how' => ['en' => 'Audit with keyboard-only navigation, a screen reader and automated checks; fix the manual issues automation misses.', 'zh' => '用纯键盘、屏幕阅读器和自动化检查做审计，并修复自动化漏掉的人工问题。'],
    ],
];
