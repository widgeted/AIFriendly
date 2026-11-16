<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');

/**
 * Shared helpers for the AI Friendly scanner and form endpoints.
 * No external libraries. No outbound requests except the scan target.
 */

const DATA_DIR = __DIR__ . '/../.data';
const CACHE_TTL = 21600;       // 6 hours
const SCAN_LIMIT = 30;         // per IP per hour
const FORM_LIMIT = 6;          // per IP per hour
const MAX_BODY = 700000;       // 700 KB
const UA = 'AIFriendlyBot/0.1 (+https://aifriendly.fyi/scan/)';

function json_out(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function fail(int $status, string $message): void
{
    json_out($status, ['ok' => false, 'error' => $message]);
}

function client_ip(): string
{
    $cf = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '';
    if ($cf !== '' && filter_var($cf, FILTER_VALIDATE_IP)) {
        return $cf;
    }
    $ra = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ra, FILTER_VALIDATE_IP) ? $ra : '0.0.0.0';
}

function data_path(string $sub): string
{
    $dir = DATA_DIR . '/' . $sub;
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    return $dir;
}

/** Sliding-window file rate limit. Returns true when allowed. */
function rate_limit(string $bucket, int $limit, int $window = 3600): bool
{
    $dir = data_path('rate');
    $key = hash('sha256', $bucket . '|' . client_ip());
    $file = $dir . '/' . $key . '.json';
    $fp = @fopen($file, 'c+');
    if (!$fp) {
        return true; // fail open rather than break the site
    }
    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp) ?: '[]';
    $hits = json_decode($raw, true);
    if (!is_array($hits)) {
        $hits = [];
    }
    $now = time();
    $hits = array_values(array_filter($hits, fn($t) => is_int($t) && $t > $now - $window));
    $allowed = count($hits) < $limit;
    if ($allowed) {
        $hits[] = $now;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($hits));
        fflush($fp);
    }
    flock($fp, LOCK_UN);
    fclose($fp);
    if (random_int(1, 40) === 1) {
        foreach (glob($dir . '/*.json') ?: [] as $old) {
            if (@filemtime($old) < time() - 7200) {
                @unlink($old);
            }
        }
    }
    return $allowed;
}

function is_public_ip(string $ip): bool
{
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return false;
    }
    if (strpos($ip, ':') !== false) {
        $bin = @inet_pton($ip);
        if ($bin === false || strlen($bin) !== 16) {
            return false;
        }
        if ($ip === '::' || $ip === '::1') {
            return false;
        }
        // IPv4-mapped / IPv4-compatible
        if (substr($bin, 0, 12) === str_repeat("\0", 10) . "\xff\xff" || substr($bin, 0, 12) === str_repeat("\0", 12)) {
            $v4 = @inet_ntop(substr($bin, 12));
            return $v4 !== false && is_public_ip($v4);
        }
        $b0 = ord($bin[0]);
        $b1 = ord($bin[1]);
        if (($b0 & 0xFE) === 0xFC) { // fc00::/7 unique local
            return false;
        }
        if ($b0 === 0xFE && ($b1 & 0xC0) === 0x80) { // fe80::/10 link local
            return false;
        }
        if ($b0 === 0xFF) { // multicast
            return false;
        }
        return true;
    }
    $long = ip2long($ip);
    if ($long === false) {
        return false;
    }
    $ranges = [
        ['0.0.0.0', '0.255.255.255'],
        ['10.0.0.0', '10.255.255.255'],
        ['100.64.0.0', '100.127.255.255'],
        ['127.0.0.0', '127.255.255.255'],
        ['169.254.0.0', '169.254.255.255'],
        ['172.16.0.0', '172.31.255.255'],
        ['192.0.0.0', '192.0.2.255'],
        ['192.168.0.0', '192.168.255.255'],
        ['198.18.0.0', '198.19.255.255'],
        ['198.51.100.0', '198.51.100.255'],
        ['203.0.113.0', '203.0.113.255'],
        ['224.0.0.0', '239.255.255.255'],
        ['240.0.0.0', '255.255.255.255'],
    ];
    foreach ($ranges as [$from, $to]) {
        if ($long >= ip2long($from) && $long <= ip2long($to)) {
            return false;
        }
    }
    return true;
}

/** @return array{url:string,host:string,port:int,scheme:string} */
function validate_url(string $url): array
{
    $url = trim($url);
    if ($url === '' || strlen($url) > 2048) {
        throw new InvalidArgumentException('Enter a URL shorter than 2048 characters.');
    }
    $parts = parse_url($url);
    if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
        throw new InvalidArgumentException('That does not look like a URL.');
    }
    $scheme = strtolower($parts['scheme']);
    if (!in_array($scheme, ['http', 'https'], true)) {
        throw new InvalidArgumentException('Only http and https URLs can be scanned.');
    }
    if (isset($parts['user']) || isset($parts['pass'])) {
        throw new InvalidArgumentException('URLs with credentials are not allowed.');
    }
    $port = isset($parts['port']) ? (int) $parts['port'] : ($scheme === 'https' ? 443 : 80);
    if (!in_array($port, [80, 443], true)) {
        throw new InvalidArgumentException('Only ports 80 and 443 are allowed.');
    }
    $host = strtolower(rtrim($parts['host'], '.'));
    if ($host === '' || strlen($host) > 253) {
        throw new InvalidArgumentException('Invalid hostname.');
    }
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        if (!is_public_ip($host)) {
            throw new InvalidArgumentException('Private and local addresses cannot be scanned.');
        }
    } elseif (!preg_match('/^[a-z0-9]([a-z0-9\-\.]*[a-z0-9])?$/', $host)) {
        throw new InvalidArgumentException('Use an ASCII hostname or a public IP address.');
    }
    return ['url' => $url, 'host' => $host, 'port' => $port, 'scheme' => $scheme];
}

/** Resolve and reject any private answer (DNS rebinding guard). */
function resolve_public(string $host, int $port): string
{
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        if (!is_public_ip($host)) {
            throw new RuntimeException('The address is not public.');
        }
        return $host;
    }
    $ips = [];
    $v4 = @gethostbynamel($host);
    if (is_array($v4)) {
        $ips = array_merge($ips, $v4);
    }
    $aaaa = @dns_get_record($host, DNS_AAAA);
    if (is_array($aaaa)) {
        foreach ($aaaa as $rec) {
            if (!empty($rec['ipv6'])) {
                $ips[] = $rec['ipv6'];
            }
        }
    }
    $ips = array_values(array_unique($ips));
    if (!$ips) {
        throw new RuntimeException('That hostname could not be resolved.');
    }
    foreach ($ips as $ip) {
        if (!is_public_ip($ip)) {
            throw new RuntimeException('That hostname resolves to a private address.');
        }
    }
    return $ips[0];
}

function absolutize(string $base, string $loc): string
{
    if (preg_match('#^https?://#i', $loc)) {
        return $loc;
    }
    $p = parse_url($base);
    if (!$p || empty($p['host'])) {
        return $loc;
    }
    $origin = ($p['scheme'] ?? 'https') . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '');
    if (str_starts_with($loc, '//')) {
        return ($p['scheme'] ?? 'https') . ':' . $loc;
    }
    if (str_starts_with($loc, '/')) {
        return $origin . $loc;
    }
    $path = $p['path'] ?? '/';
    $dir = substr($path, 0, (int) strrpos($path, '/') + 1);
    return $origin . $dir . $loc;
}

/**
 * Fetch a URL with manual redirects, DNS pinning and a body cap.
 * @return array{status:int,headers:array<string,string>,body:string,url:string,error:string}
 */
function http_get(string $url, int $timeout = 6, int $maxRedirects = 3, int $maxBytes = MAX_BODY): array
{
    $current = $url;
    for ($hop = 0; $hop <= $maxRedirects; $hop++) {
        try {
            $info = validate_url($current);
            $ip = resolve_public($info['host'], $info['port']);
        } catch (Throwable $e) {
            return ['status' => 0, 'headers' => [], 'body' => '', 'url' => $current, 'error' => $e->getMessage()];
        }
        $pinned = $info['host'] . ':' . $info['port'] . ':' . (strpos($ip, ':') !== false ? '[' . $ip . ']' : $ip);
        $headers = [];
        $body = '';
        $ch = curl_init($current);
        if ($ch === false) {
            return ['status' => 0, 'headers' => [], 'body' => '', 'url' => $current, 'error' => 'Could not initialise the request.'];
        }
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_USERAGENT => UA,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_RESOLVE => [$pinned],
            CURLOPT_ENCODING => '',
            CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml,application/xml,text/plain;q=0.9,*/*;q=0.5'],
            CURLOPT_HEADERFUNCTION => function ($ch, $line) use (&$headers) {
                $len = strlen($line);
                $trim = trim($line);
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', $trim, $m)) {
                    $headers = ['__status' => $m[1]];
                } elseif (strpos($trim, ':') !== false) {
                    [$k, $v] = explode(':', $trim, 2);
                    $headers[strtolower(trim($k))] = trim($v);
                }
                return $len;
            },
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$body, $maxBytes) {
                $room = $maxBytes - strlen($body);
                if ($room <= 0) {
                    return 0;
                }
                $body .= substr($chunk, 0, $room);
                return strlen($chunk);
            },
        ]);
        $ok = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int) ($headers['__status'] ?? curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
        curl_close($ch);
        unset($headers['__status']);
        if ($errno !== 0 && $errno !== 23) { // 23 = write error from our cap
            return ['status' => 0, 'headers' => [], 'body' => '', 'url' => $current, 'error' => $error ?: 'Request failed.'];
        }
        if ($status >= 300 && $status < 400 && !empty($headers['location'])) {
            $next = absolutize($current, $headers['location']);
            try {
                validate_url($next);
            } catch (Throwable $e) {
                return ['status' => $status, 'headers' => $headers, 'body' => '', 'url' => $current, 'error' => 'Unsafe redirect blocked.'];
            }
            $current = $next;
            continue;
        }
        return ['status' => $status, 'headers' => $headers, 'body' => $body, 'url' => $current, 'error' => ''];
    }
    return ['status' => 0, 'headers' => [], 'body' => '', 'url' => $current, 'error' => 'Too many redirects.'];
}

function text_len(string $html): int
{
    $html = preg_replace('#<(script|style|noscript|svg)[^>]*>.*?</\1>#is', ' ', $html) ?? '';
    $text = strip_tags($html);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text) ?? '';
    return mb_strlen(trim($text));
}

function main_text_len(string $html): int
{
    if (preg_match('#<main\b[^>]*>(.*?)</main>#is', $html, $m)) {
        return text_len($m[1]);
    }
    if (preg_match('#<article\b[^>]*>(.*?)</article>#is', $html, $m)) {
        return text_len($m[1]);
    }
    return text_len($html);
}

function check(string $id, string $category, string $label, int $weight, string $status, string $detail, string $fix = ''): array
{
    return [
        'id' => $id,
        'category' => $category,
        'label' => $label,
        'weight' => $weight,
        'status' => $status,
        'detail' => $detail,
        'fix' => $fix,
    ];
}

set_exception_handler(function (Throwable $e): void {
    error_log('aifriendly: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(['ok' => false, 'error' => 'Something went wrong on our side.']);
    }
});
