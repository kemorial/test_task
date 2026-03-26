<?php
declare(strict_types=1);

$path = __DIR__ . DIRECTORY_SEPARATOR . 'mock_data.json';

try {
    $json = file_get_contents($path);
    if ($json === false) {
        throw new RuntimeException('Не удалось прочитать mock_data.json');
    }
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Ошибка: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    exit;
}

$users = $data['users'] ?? [];
$deals = $data['deals'] ?? [];
$filteredDeals = array_values(array_filter($deals, static function (array $deal): bool {
    return in_array($deal['STATUS'] ?? '', ['WON', 'LOSE'], true);
}));

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Task 1</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Пользователи</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h((string)($user['ID'] ?? '')) ?></td>
                <td><?= h((string)($user['NAME'] ?? '')) ?></td>
                <td><?= h((string)($user['EMAIL'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Сделки со статусом WON или LOSE</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Статус</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($filteredDeals as $deal): ?>
            <tr>
                <td><?= h((string)($deal['ID'] ?? '')) ?></td>
                <td><?= h((string)($deal['TITLE'] ?? '')) ?></td>
                <td><?= h((string)($deal['STATUS'] ?? '')) ?></td>
                <td><?= h((string)($deal['AMOUNT'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
