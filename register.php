<?php
// Конфигурация базы данных
$host = 'localhost';
$db = 'transaction_system';
$user = 'egor';
$password = '0000';

// Соединение с сервером MySQL
$conn = new mysqli($host, $user, $password, $db);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Установка кодировки UTF-8
$conn->set_charset("utf8mb4");

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Подготовка SQL-запроса для вставки данных
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Выполнение запроса и проверка на ошибки
    if ($stmt->execute()) {
        // Перенаправление на страницу входа после успешной регистрации
        header("Location: login.php");
        exit(); // Завершение скрипта после перенаправления
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    // Закрытие подготовленного выражения
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h2>Регистрация пользователя</h2>
    <form method="POST" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Зарегистрироваться">
    </form>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>
