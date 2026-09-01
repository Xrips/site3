<?php
// Включаем отображение всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// НАСТРОЙКИ — ЗАМЕНИ НА СВОИ
// ============================================
$botToken = '8537194712:AAEPZyPPkDvYEdJWu3egRg9AKpaSCuUJX80'; // Твой токен
$chatId = '-1003055593113'; // Твой Chat ID

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

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ============================================
// ОТВЕТ ПОЛЬЗОВАТЕЛЮ
// ============================================
header('Content-Type: application/json');

if ($httpCode === 200) {
    echo json_encode(['success' => true, 'message' => '✅ Спасибо! Ваш ответ отправлен.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Ошибка отправки. Попробуйте ещё раз.']);
}
?>