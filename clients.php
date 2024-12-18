<?php
// Подключение к базе данных
$host = 'localhost'; // или ваш хост
$user = 'root'; // ваш пользователь
$password = ''; // ваш пароль
$database = 'transaction_system'; // имя базы данных

$mysqli = new mysqli($host, $user, $password, $database);

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Создание таблицы Bank, если она не существует
$createBankTable = "
CREATE TABLE IF NOT EXISTS Bank (
    BankNumber INT AUTO_INCREMENT PRIMARY KEY,
    BankName VARCHAR(100) NOT NULL
)";
$mysqli->query($createBankTable);

// Создание таблицы Client, если она не существует
$createClientTable = "
CREATE TABLE IF NOT EXISTS Client (
    ClientNumber INT AUTO_INCREMENT PRIMARY KEY,
    Phone VARCHAR(20) NOT NULL,
    Address VARCHAR(100),
    CardNumber VARCHAR(20),
    Name VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    Patronymic VARCHAR(50),
    BankNumber INT,
    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber)
)";
$mysqli->query($createClientTable);

// Пример вставки банка (можно убрать, если банки уже добавлены)
$bankName = 'Название банка';
$insertBank = $mysqli->prepare("INSERT INTO Bank (BankName) VALUES (?)");
$insertBank->bind_param("s", $bankName);
$insertBank->execute();
$insertBank->close();

// Вставка клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $bankNumber = $_POST['bankNumber']; // Предположим, вы получаете BankNumber из формы
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cardNumber = $_POST['cardNumber'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $patronymic = $_POST['patronymic'];

    // Проверка существования банка
    $stmt = $mysqli->prepare("SELECT BankNumber FROM Bank WHERE BankNumber = ?");
    $stmt->bind_param("i", $bankNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Банк существует, можно добавлять клиента
        $stmt = $mysqli->prepare("INSERT INTO Client (Phone, Address, CardNumber, Name, Surname, Patronymic, BankNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $phone, $address, $cardNumber, $name, $surname, $patronymic, $bankNumber);
        
        if ($stmt->execute()) {
            echo "Клиент успешно добавлен.";
        } else {
            echo "Ошибка при добавлении клиента: " . $stmt->error;
        }
    } else {
        echo "Ошибка: указанный банк не существует.";
    }

    $stmt->close();
}

// Закрытие соединения
$mysqli->close();
?>

<!-- HTML форма для добавления клиента -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить клиента</title>
</head>
<body>
    <h1>Добавить клиента</h1>
    <form method="POST" action="">
        <label for="bankNumber">Номер банка:</label>
        <input type="number" name="bankNumber" required><br>

        <label for="phone">Телефон:</label>
        <input type="text" name="phone" required><br>

        <label for="address">Адрес:</label>
        <input type="text" name="address"><br>

        <label for="cardNumber">Номер карты:</label>
        <input type="text" name="cardNumber" required><br>

        <label for="name">Имя:</label>
        <input type="text" name="name" required><br>

        <label for="surname">Фамилия:</label>
        <input type="text" name="surname" required><br>

        <label for="patronymic">Отчество:</label>
        <input type="text" name="patronymic"><br>

        <input type="submit" value="Добавить клиента">
    </form>
</body>
</html>
