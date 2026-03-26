<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Logger.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Database.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ProductRepository.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';

$logger = new Logger(__DIR__ . DIRECTORY_SEPARATOR . 'app.log');

try {
    $config = new Config('mysql', 'localhost', 'app', 'utf8mb4', 'root', 'pass');
    $dsn = $config->getDbDriver()
        . ':host=' . $config->getDbHost()
        . ';dbname=' . $config->getDbName()
        . ';charset=' . $config->getDbCharset();
    $db = new Database($dsn, $config->getDbUser(), $config->getDbPass());
    $repo = new ProductRepository($db->pdo());

    foreach ($repo->all() as $row) {
        echo htmlspecialchars((string)($row['name'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo PHP_EOL;
    }

    $logger->info('Список товаров успешно выведен.');
} catch (Throwable $e) {
    $logger->error($e->getMessage());
    http_response_code(500);
    echo 'Ошибка: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
