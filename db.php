<?php
$host = 'localhost'; // или ваш хост
$db = 'transaction_sistem'; // имя вашей базы данных
$user = 'egor'; // ваш пользователь
$pass = '0000'; // ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
