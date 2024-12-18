<?php
$servername = "localhost"; // Имя сервера
$username = "egor"; // Ваше имя пользователя
$password = "0000"; // Ваш пароль
$dbname = "transaction_sistem"; // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
