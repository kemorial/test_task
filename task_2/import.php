<?php

declare(strict_types=1);
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Database.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';

// Настройте подключение к БД под своё окружение.
$dbDriver = 'mysql';
$dbHost = 'localhost';
$dbName = 'app';
$dbCharset = 'utf8mb4';
$dbUser = 'root';
$dbPass = 'pass';

$config = new Config($dbDriver, $dbHost, $dbName, $dbCharset, $dbUser, $dbPass);
$dsn = $config->getDbDriver()
    . ':host=' . $config->getDbHost()
    . ';dbname=' . $config->getDbName()
    . ';charset=' . $config->getDbCharset();

$csvPath = __DIR__ . DIRECTORY_SEPARATOR . 'product.csv';

try {
    $db = new Database($dsn, $config->getDbUser(), $config->getDbPass());
    $pdo = $db->pdo();
} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getMessage();
    exit;
}

if (!is_readable($csvPath)) {
    echo 'CSV файл не найден: ' . $csvPath;
    exit;
}

$handle = fopen($csvPath, 'rb');
if ($handle === false) {
    echo 'Не удалось открыть CSV файл.';
    exit;
}

fgetcsv($handle, 0, ';', '"', "\0");

$inserted = 0;
$updated = 0;

$selectStmt = $pdo->prepare(
    'SELECT 1 FROM product WHERE name = :name AND art = :art LIMIT 1'
);
$insertStmt = $pdo->prepare(
    'INSERT INTO product (name, art, price, quantity) VALUES (:name, :art, :price, :quantity)'
);
$updateStmt = $pdo->prepare(
    'UPDATE product SET price = :price, quantity = :quantity WHERE name = :name AND art = :art'
);

$pdo->beginTransaction();

try {
    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        [$name, $art, $price, $quantity] = $row;

        $selectStmt->execute([
            ':name' => $name,
            ':art' => $art,
        ]);

        if ($selectStmt->fetchColumn()) {
            $updateStmt->execute([
                ':name' => $name,
                ':art' => $art,
                ':price' => (float)$price,
                ':quantity' => (int)$quantity,
            ]);
            $updated++;
        } else {
            $insertStmt->execute([
                ':name' => $name,
                ':art' => $art,
                ':price' => (float)$price,
                ':quantity' => (int)$quantity,
            ]);
            $inserted++;
        }
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    fclose($handle);
    echo 'Ошибка импорта: ' . $e->getMessage();
    exit;
}

fclose($handle);

echo 'Добавлено: ' . $inserted . PHP_EOL;
echo 'Обновлено: ' . $updated . PHP_EOL;
