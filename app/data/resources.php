<?php
declare(strict_types=1);

return [
    [
        'title' => ['en' => 'Badge kit', 'zh' => '徽章套件'],
        'meta' => 'ZIP / 396 KB',
        'desc' => [
            'en' => 'All six designs in SVG and PNG, the mark, the poster, the social cards and the license.',
            'zh' => '全部六种设计的 SVG 与 PNG、独立标志、海报、社交卡片和许可。',
        ],
        'files' => [['Download', '/assets/kit/aifriendly-badge-kit.zip']],
    ],
    [
        'title' => ['en' => 'The standard v0.1', 'zh' => '标准 v0.1'],
        'meta' => 'Markdown / 13 KB',
        'desc' => [
            'en' => 'The full 24-check standard in Markdown, ready for a repo, a wiki or a review.',
            'zh' => '完整的 24 项检查标准，Markdown 格式，可放进仓库、wiki 或评审流程。',
        ],
        'files' => [['EN', '/assets/kit/standard.md'], ['中文', '/assets/kit/standard-zh.md']],
    ],
    [
        'title' => ['en' => 'Poster', 'zh' => '海报'],
        'meta' => 'A3 / print',
        'desc' => [
            'en' => 'Vector poster. “Open to AI. Accountable to humans.” Print it, pin it, argue with it.',
            'zh' => '矢量海报。“对 AI 开放。对人类负责。” 打印、张贴，或者拿来争论。',
        ],
        'files' => [['SVG', '/assets/brand/poster-a3.svg'], ['PDF', '/assets/brand/poster-a3.pdf']],
    ],
    [
        'title' => ['en' => 'Social cards', 'zh' => '社交卡片'],
        'meta' => '1200 × 630',
        'desc' => [
            'en' => 'Link previews and announcement images, in English and Chinese.',
            'zh' => '链接预览与发布图片，提供英文和中文版本。',
        ],
        'files' => [['EN SVG', '/assets/brand/social-en.svg'], ['ZH SVG', '/assets/brand/social-zh.svg'], ['EN PNG', '/og-en.png'], ['ZH PNG', '/og-zh.png']],
    ],
    [
        'title' => ['en' => 'robots.txt starter', 'zh' => 'robots.txt 起始文件'],
        'meta' => 'TXT / 1 KB',
        'desc' => [
            'en' => 'A valid file with an explicit AI crawler policy and comments on switching to a denial.',
            'zh' => '有效的文件，含明确的 AI 爬虫政策，以及切换为拒绝的注释说明。',
        ],
        'files' => [['Download', '/assets/kit/robots.txt']],
    ],
    [
        'title' => ['en' => 'llms.txt starter', 'zh' => 'llms.txt 起始文件'],
        'meta' => 'TXT / 1 KB',
        'desc' => [
            'en' => 'A short map of your site for LLM tools. Emerging proposal — useful, not mandatory.',
            'zh' => '给 LLM 工具看的简短站点地图。新兴提案——有用，但不是必需。',
        ],
        'files' => [['Download', '/assets/kit/llms.txt']],
    ],
    [
        'title' => ['en' => 'JSON-LD starter', 'zh' => 'JSON-LD 起始文件'],
        'meta' => 'JSON-LD / 1 KB',
        'desc' => [
            'en' => 'Organization, WebSite and WebPage markup with the fields that actually matter.',
            'zh' => 'Organization、WebSite 与 WebPage 标记，只保留真正重要的字段。',
        ],
        'files' => [['Download', '/assets/kit/organization.jsonld']],
    ],
    [
        'title' => ['en' => 'security.txt starter', 'zh' => 'security.txt 起始文件'],
        'meta' => 'TXT / 1 KB',
        'desc' => [
            'en' => 'RFC 9116 template with contact, expiry and disclosure language.',
            'zh' => 'RFC 9116 模板，包含联系方式、过期时间和披露说明。',
        ],
        'files' => [['Download', '/assets/kit/security.txt']],
    ],
    [
        'title' => ['en' => 'OpenAPI 3.1 starter', 'zh' => 'OpenAPI 3.1 起始文件'],
        'meta' => 'YAML / 2 KB',
        'desc' => [
            'en' => 'A minimal, valid description with scoped auth, rate limits and RFC 9457 errors.',
            'zh' => '最小但有效的描述，包含作用域认证、速率限制和 RFC 9457 错误。',
        ],
        'files' => [['Download', '/assets/kit/openapi.yaml']],
    ],
];
