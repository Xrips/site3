<?php
// ============================================
// НАСТРОЙКИ — ЗАМЕНИ НА СВОИ
// ============================================
$botToken = '8905769996:AAHREUqWOrADavGE5O92zB87tN0wAzYRF0Q';
$chatId = '990447245';

// ============================================
// ВКЛЮЧАЕМ JSON-ОТВЕТ СРАЗУ
// ============================================
header('Content-Type: application/json');

// ============================================
// ПОЛУЧАЕМ ДАННЫЕ ИЗ ФОРМЫ
// ============================================
$name = isset($_POST['name']) ? trim($_POST['name']) : 'Не указано';
$attendance = isset($_POST['attendance']) ? $_POST['attendance'] : 'Не выбрано';

// Напитки
$drinks = isset($_POST['drink']) ? $_POST['drink'] : [];
if (is_array($drinks) && count($drinks) > 0) {
    $drinksList = implode(', ', $drinks);
} else {
    $drinksList = 'Не выбраны';
}

// Блюда
$dishes = isset($_POST['dish']) ? $_POST['dish'] : [];
if (is_array($dishes) && count($dishes) > 0) {
    $dishesList = implode(', ', $dishes);
} else {
    $dishesList = 'Не выбраны';
}

// ============================================
// ФОРМИРУЕМ СООБЩЕНИЕ
// ============================================
$message = "📩 *Новый ответ на приглашение!*" . "\n\n";
$message .= "👤 *Имя:* " . $name . "\n";
$message .= "✅ *Статус:* " . $attendance . "\n";
$message .= "🍷 *Напитки:* " . $drinksList . "\n";
$message .= "🍽️ *Блюда:* " . $dishesList . "\n";
$message .= "🕒 *Время ответа:* " . date('d.m.Y H:i:s');

// ============================================
// ОТПРАВЛЯЕМ В TELEGRAM
// ============================================
$url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
];

// Используем file_get_contents (без CURL) — работает всегда
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 10,
        'ignore_errors' => true // Чтобы получить ответ даже при ошибке
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// ============================================
// ОТВЕТ ПОЛЬЗОВАТЕЛЮ
// ============================================
if ($response !== false) {
    // Проверяем, что Telegram вернул ok: true
    $result = json_decode($response, true);
    if (isset($result['ok']) && $result['ok'] === true) {
        echo json_encode(['success' => true, 'message' => '✅ Спасибо! Ваш ответ отправлен.']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Ошибка Telegram: ' . ($result['description'] ?? 'Неизвестная ошибка')]);
    }
} else {
    echo json_encode(['success' => false, 'message' => '❌ Ошибка соединения с Telegram. Попробуйте ещё раз.']);
}
?>