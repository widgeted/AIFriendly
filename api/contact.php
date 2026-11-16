<?php
declare(strict_types=1);

require __DIR__ . '/lib.php';

$lang = (($_POST['lang'] ?? 'en') === 'zh') ? 'zh' : 'en';
$msg = [
    'en' => [
        'method' => 'Use POST.', 'rate' => 'Too many messages from this connection. Try again later.',
        'type' => 'Unknown form type.', 'name' => 'Add your name.', 'email' => 'Add a valid email address.',
        'url' => 'The URL should start with http:// or https://.', 'message' => 'Add a message.',
        'verify' => 'Add the service URL and at least one evidence link.', 'gallery' => 'Add the URL and a sentence about the placement.',
        'store' => 'The message could not be stored. Try again later.', 'thanks' => 'Thank you. A human will read this.',
        'received' => 'Application received. We aim to reply within ten business days.',
        'placement' => 'Placement received. We will take a look and reply if it is a fit.',
    ],
    'zh' => [
        'method' => '请使用 POST。', 'rate' => '这个连接发送太频繁，请稍后再试。',
        'type' => '未知的表单类型。', 'name' => '请填写姓名。', 'email' => '请填写有效的邮箱地址。',
        'url' => 'URL 需要以 http:// 或 https:// 开头。', 'message' => '请填写留言。',
        'verify' => '请填写服务 URL，并至少提供一条证据链接。', 'gallery' => '请填写 URL，并用一句话描述放置场景。',
        'store' => '消息保存失败，请稍后再试。', 'thanks' => '谢谢，我们会有人阅读。',
        'received' => '申请已收到。我们目标在十个工作日内回复。',
        'placement' => '放置示例已收到。如果合适，我们会回复。',
    ],
];
$m = $msg[$lang];
$cssV = substr(hash('sha256', (string) @filemtime(__DIR__ . '/../assets/css/site.css')), 0, 8);
$wantsJson = str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
$back = $lang === 'zh' ? '/zh/' : '/';
$backLabel = $lang === 'zh' ? '返回 AI Friendly' : 'Back to AI Friendly';

function form_page(int $status, bool $ok, string $message, string $lang, string $back, string $backLabel): void
{
    http_response_code($status);
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');
    $title = $lang === 'zh' ? ($ok ? '已完成' : '出错了') : ($ok ? 'Done' : 'Something went wrong');
    $label = $lang === 'zh' ? 'AI Friendly' : 'AI Friendly';
    $html = '<!doctype html><html lang="' . ($lang === 'zh' ? 'zh-Hans' : 'en') . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $label . ' — ' . $title . '</title><link rel="stylesheet" href="/assets/css/site.css?v=' . $cssV . '"></head><body class="api-result"><main class="wrap"><p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span>' . $label . '</p><h1 class="h2">' . $title . '</h1><p class="lede">' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p><a class="btn btn--primary" href="' . $back . '">' . $backLabel . '</a></main></body></html>';
    echo $html;
    exit;
}

function form_fail(int $status, string $message): void
{
    global $wantsJson, $lang, $back, $backLabel;
    if ($wantsJson) {
        json_out($status, ['ok' => false, 'error' => $message]);
    }
    form_page($status, false, $message, $lang, $back, $backLabel);
}

function form_ok(string $message): void
{
    global $wantsJson, $lang, $back, $backLabel;
    if ($wantsJson) {
        json_out(200, ['ok' => true, 'message' => $message]);
    }
    form_page(200, true, $message, $lang, $back, $backLabel);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    form_fail(405, $m['method']);
}
if (!rate_limit('form', FORM_LIMIT)) {
    form_fail(429, $m['rate']);
}

$type = trim((string) ($_POST['type'] ?? 'contact'));
if (!in_array($type, ['contact', 'verify', 'gallery'], true)) {
    form_fail(400, $m['type']);
}
$back = $lang === 'zh'
    ? ($type === 'gallery' ? '/zh/gallery/#submit' : ($type === 'verify' ? '/zh/verify/#apply' : '/zh/about/#contact'))
    : ($type === 'gallery' ? '/gallery/#submit' : ($type === 'verify' ? '/verify/#apply' : '/about/#contact'));

// Honeypot: accept silently, store nothing.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    form_ok($m['thanks']);
}

$clean = static function (string $key, int $max): string {
    $v = (string) ($_POST[$key] ?? '');
    $v = str_replace(["\r\n", "\r"], "\n", $v);
    return mb_substr(trim($v), 0, $max);
};

$name = $clean('name', 120);
$email = $clean('email', 200);
$subject = $clean('subject', 200);
$url = $clean('url', 300);
$message = $clean('message', 5000);
$evidence = $clean('evidence', 5000);

if ($name === '') {
    form_fail(422, $m['name']);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    form_fail(422, $m['email']);
}
if ($url !== '' && !preg_match('#^https?://#i', $url)) {
    form_fail(422, $m['url']);
}
if ($type === 'contact' && $message === '') {
    form_fail(422, $m['message']);
}
if ($type === 'verify' && ($url === '' || $evidence === '')) {
    form_fail(422, $m['verify']);
}
if ($type === 'gallery' && ($url === '' || $message === '')) {
    form_fail(422, $m['gallery']);
}

$record = [
    'created_at' => gmdate('c'),
    'type' => $type,
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'url' => $url,
    'message' => $message,
    'evidence' => $evidence,
    'ip_hash' => hash('sha256', client_ip() . '|aifriendly'),
    'ua' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 240),
];

$dir = data_path('inbox');
$file = $dir . '/' . gmdate('Y-m') . '.jsonl';
$line = json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
$fp = @fopen($file, 'ab');
if (!$fp) {
    form_fail(500, $m['store']);
}
flock($fp, LOCK_EX);
fwrite($fp, $line);
fflush($fp);
flock($fp, LOCK_UN);
fclose($fp);
@chmod($file, 0640);

$messages = [
    'contact' => $m['thanks'],
    'verify' => $m['received'],
    'gallery' => $m['placement'],
];
form_ok($messages[$type]);
