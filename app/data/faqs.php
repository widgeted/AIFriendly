<?php
declare(strict_types=1);

return [
    'home' => [
        [
            'q' => ['en' => 'Is this a badge for AI-generated content?', 'zh' => '这是“AI 生成内容”的徽章吗？'],
            'a' => ['en' => '<p>No. It signals that your work is open to AI and built to be legible to it. It says nothing about who or what wrote your copy. We do not run AI detection, and we do not believe a badge can prove authorship.</p>', 'zh' => '<p>不是。它表示你的作品对 AI 开放，并且被构建成 AI 能读懂的样子。它不说明文案由谁或什么写成。我们不做 AI 检测，也不认为一个徽章能证明作者身份。</p>'],
        ],
        [
            'q' => ['en' => 'Do I need permission to use the mark?', 'zh' => '使用这个标志需要授权吗？'],
            'a' => ['en' => '<p>No. The badges are free for personal and commercial use, with no account and no attribution requirement. Use the unmodified files from this site and follow the <a href="{badges}">usage guidelines</a>.</p>', 'zh' => '<p>不需要。徽章对个人和商业用途免费，无需账号，也无需署名。请使用本站提供的未修改文件，并遵守<a href="{badges}">使用规范</a>。</p>'],
        ],
        [
            'q' => ['en' => 'Does the mark give AI companies permission to train on my content?', 'zh' => '贴上这个标志，就等于允许 AI 公司用我的内容训练吗？'],
            'a' => ['en' => '<p>No. The mark is not a content licence. Your copyright, terms and robots.txt still govern what machines may do. Being AI-friendly means being clear about those terms, not giving them up.</p>', 'zh' => '<p>不等于。这个标志不是内容许可。你的版权、条款和 robots.txt 仍然决定机器可以做什么。对 AI 友好意味着把条款说清楚，而不是放弃条款。</p>'],
        ],
        [
            'q' => ['en' => 'Why would a site block AI crawlers and still be AI-friendly?', 'zh' => '为什么一个网站可以拒绝 AI 爬虫，却仍然算 AI 友好？'],
            'a' => ['en' => '<p>Because a clear no is more useful to an agent than a silent maybe. The standard rewards a declared policy — allow or deny — over accidental exposure. Openness is about legibility and consent, not unlimited access.</p>', 'zh' => '<p>因为对智能体来说，明确的“不”比沉默的“也许”更有用。标准奖励的是清楚声明的政策——允许或拒绝——而不是意外暴露。开放讨论的是可读性与同意，不是无限访问。</p>'],
        ],
        [
            'q' => ['en' => 'What is the verified badge?', 'zh' => '认证徽章是什么？'],
            'a' => ['en' => '<p>A reviewed mark for sites and services that meet the standard, including a public API or tool manifest and the safety checks in Level 3. It is in beta; self-declared badges remain free forever.</p>', 'zh' => '<p>这是面向达到标准的网站和服务的审核徽章，要求公开 API 或工具清单，并满足第 3 级的安全检查。目前处于 beta；自我声明徽章永久免费。</p>'],
        ],
        [
            'q' => ['en' => 'How is this different from Not By AI?', 'zh' => '这和 Not By AI 有什么不同？'],
            'a' => ['en' => '<p>Not By AI answers “was this made by humans?” We answer “is this built to work with AI?” They are different questions, and both can be true at the same time.</p>', 'zh' => '<p>Not By AI 回答的是“这是人类做的吗？”我们回答的是“这是为与 AI 协作而构建的吗？”这是两个不同的问题，可以同时成立。</p>'],
        ],
        [
            'q' => ['en' => 'Is the standard finished?', 'zh' => '这套标准完成了吗？'],
            'a' => ['en' => '<p>No. It is v0.1, a public draft. It will change as the tooling does. <a href="{contact}">Feedback</a> is welcome.</p>', 'zh' => '<p>没有。它是 v0.1 公开草案，会随着工具生态变化。欢迎<a href="{contact}">反馈</a>。</p>'],
        ],
    ],
    'about' => [
        [
            'q' => ['en' => 'Why “AI Friendly” instead of “AI Free” or “Human Made”?', 'zh' => '为什么叫“AI Friendly”，而不是“AI Free”或“Human Made”？'],
            'a' => ['en' => '<p>Because the question is different. “AI-free” and “human-made” describe origin. “AI-friendly” describes a relationship: you are open to AI, and you have made your work legible to it on your terms. A site can be human-made and AI-friendly at the same time.</p>', 'zh' => '<p>因为问题不同。“AI-free”和“human-made”描述来源；“AI-friendly”描述关系：你对 AI 开放，并按自己的条款让作品可被理解。一个网站可以同时是“人类创作”和“AI 友好”。</p>'],
        ],
        [
            'q' => ['en' => 'Can I use the badge if I do not meet the standard?', 'zh' => '没有达到标准，也能使用徽章吗？'],
            'a' => ['en' => '<p>The self-declared badge is open to anyone who is genuinely open to AI. If you claim a level, that claim must be true. The verified badge is only for reviewed services with a current listing. Overclaiming is the fastest way to make the mark worthless.</p>', 'zh' => '<p>自我声明徽章对任何真正对 AI 开放的人开放。如果你声明某个等级，这个声明必须真实。认证徽章只发给经过审核、拥有当前列表的服务。过度声明是让标志贬值最快的方式。</p>'],
        ],
        [
            'q' => ['en' => 'Is the scan free? Will it stay free?', 'zh' => '扫描免费吗？会一直免费吗？'],
            'a' => ['en' => '<p>Yes. The scan is free and does not require an account. It is rate-limited to keep it available. If that changes, we will say so before it changes, not after.</p>', 'zh' => '<p>免费，也不需要账号。为了保持可用性，它有速率限制。如果未来有变化，我们会在变化前说明，而不是事后解释。</p>'],
        ],
        [
            'q' => ['en' => 'How do you make money?', 'zh' => '这个项目怎么赚钱？'],
            'a' => ['en' => '<p>Right now we do not. The badge and the standard are free. If verification later carries a fee, it will pay for the review and the listing — not for the right to use the self-declared mark. We will publish pricing before charging anyone.</p>', 'zh' => '<p>目前不赚钱。徽章和标准都是免费的。如果未来认证收费，费用用于覆盖审核与列表维护，而不是购买自我声明徽章的使用权。任何收费都会提前公开。</p>'],
        ],
        [
            'q' => ['en' => 'What about copyright and AI training?', 'zh' => '版权和 AI 训练怎么处理？'],
            'a' => ['en' => '<p>Not our remit, and we will not pretend otherwise. We ask for disclosure where AI materially shapes content, and for clear terms about what machines may do with your work. The mark does not grant or remove any rights.</p>', 'zh' => '<p>这不是我们的职权范围，我们也不会假装是。我们要求在 AI 实质参与内容时披露，并要求你用清楚的条款说明机器可以如何使用你的作品。这个标志不授予也不剥夺任何权利。</p>'],
        ],
        [
            'q' => ['en' => 'Can I translate or adapt the standard?', 'zh' => '我可以翻译或改编这套标准吗？'],
            'a' => ['en' => '<p>Yes. The standard text is CC BY 4.0. Translate it, fork it, annotate it, link back. Tell us and we will link to your version if it is public.</p>', 'zh' => '<p>可以。标准文本采用 CC BY 4.0。你可以翻译、fork、注释并回链。告诉我们，如果版本公开，我们会链接过去。</p>'],
        ],
        [
            'q' => ['en' => 'Someone is misusing the verified badge. What do I do?', 'zh' => '有人滥用认证徽章怎么办？'],
            'a' => ['en' => '<p>Use the contact form and choose “Report misuse”. Include the URL and, if possible, a screenshot. We will check the listing and act. The verified mark is the only one we can enforce, because it is the only one we review.</p>', 'zh' => '<p>使用联系表单并选择“举报滥用”，附上 URL，最好再附截图。我们会核对列表并处理。认证徽章是唯一经过我们审核、因而也是唯一可以执行的标志。</p>'],
        ],
        [
            'q' => ['en' => 'Who is behind this?', 'zh' => '谁在做这个项目？'],
            'a' => ['en' => '<p>An independent project, built and maintained by people who use AI every day and wanted a better way to say so. No data business, no tracking. The contact form reaches a human.</p>', 'zh' => '<p>一个独立项目，由每天使用 AI、并希望更好地表达这一点的人构建和维护。没有数据生意，不做追踪。联系表单会到达一个真实的人。</p>'],
        ],
    ],
];
