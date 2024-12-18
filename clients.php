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

// Обработка добавления клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_client'])) {
    // Получение данных из формы
    $bankNumber = $_POST['bankNumber'];
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

// Обработка редактирования клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_client'])) {
    $clientNumber = $_POST['clientNumber'];
    $bankNumber = $_POST['bankNumber'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cardNumber = $_POST['cardNumber'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $patronymic = $_POST['patronymic'];

    // Обновление данных клиента
    $stmt = $mysqli->prepare("UPDATE Client SET Phone=?, Address=?, CardNumber=?, Name=?, Surname=?, Patronymic=?, BankNumber=? WHERE ClientNumber=?");
    $stmt->bind_param("ssssssii", $phone, $address, $cardNumber, $name, $surname, $patronymic, $bankNumber, $clientNumber);

    if ($stmt->execute()) {
        echo "Данные клиента успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных клиента: " . $stmt->error;
    }

    $stmt->close();
}

// Обработка удаления клиента
if (isset($_GET['delete'])) {
    $clientNumber = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM Client WHERE ClientNumber = ?");
    $stmt->bind_param("i", $clientNumber);
    $stmt->execute();
    echo "Клиент успешно удален.";
    $stmt->close();
}

// Получение списка клиентов
$clients = $mysqli->query("SELECT * FROM Client");

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

        <input type="submit" name="add_client" value="Добавить клиента">
    </form>

    <h2>Список клиентов</h2>
    <table border="1">
        <tr>
            <th>Номер клиента</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Номер карты</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Отчество</th>
            <th>Номер банка</th>
            <th>Действия</th>
        </tr>
        <?php while ($client = $clients->fetch_assoc()): ?>
        <tr>
            <td><?php echo $client['ClientNumber']; ?></td>
            <td><?php echo $client['Phone']; ?></td>
            <td><?php echo $client['Address']; ?></td>
            <td><?php echo $client['CardNumber']; ?></td>
            <td><?php echo $client['Name']; ?></td>
            <td><?php echo $client['Surname']; ?></td>
            <td><?php echo $client['Patronymic']; ?></td>
            <td><?php echo $client['BankNumber']; ?></td>
            <td>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="clientNumber" value="<?php echo $client['ClientNumber']; ?>">
                    <input type="hidden" name="bankNumber" value="<?php echo $client['BankNumber']; ?>">
                    <input type="hidden" name="phone" value="<?php echo $client['Phone']; ?>">
                    <input type="hidden" name="address" value="<?php echo $client['Address']; ?>">
                    <input type="hidden" name="cardNumber" value="<?php echo $client['CardNumber']; ?>">
                    <input type="hidden" name="name" value="<?php echo $client['Name']; ?>">
                    <input type="hidden" name="surname" value="<?php echo $client['Surname']; ?>">
                    <input type="hidden" name="patronymic" value="<?php echo $client['Patronymic']; ?>">
                    <input type="submit" name="edit_client" value="Редактировать">
                </form>
                <a href="?delete=<?php echo $client['ClientNumber']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
// Закрытие соединения
$mysqli->close();
?>
