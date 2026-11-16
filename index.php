<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/header.php';

$pageFile = APP_ROOT . '/pages/' . $page . '.php';
if (!is_file($pageFile)) {
    http_response_code(500);
    echo '<p>Page template missing.</p>';
} else {
    require $pageFile;
}

require __DIR__ . '/app/footer.php';
