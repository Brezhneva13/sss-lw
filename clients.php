Чтобы добавить кнопку "Вернуться на главную страницу" и изменить таблицу для редактирования, давайте сначала определим, что подразумевается под "главной страницей". Обычно это может быть страница со списком клиентов или общая информация о системе.

Я добавлю кнопку, которая будет возвращать пользователя на страницу со списком клиентов. Также я покажу, как можно изменить структуру таблицы для отображения данных.

Обновленный код с кнопкой "Вернуться на главную страницу"
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

// Проверка, нужно ли редактировать клиента
$editClient = null;
if (isset($_GET['edit'])) {
    $clientNumber = $_GET['edit'];
    $stmt = $mysqli->prepare("SELECT * FROM Client WHERE ClientNumber = ?");
    $stmt->bind_param("i", $clientNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $editClient = $result->fetch_assoc();
}
?>

<!-- HTML форма для добавления или редактирования клиента -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить/Редактировать клиента</title>
</head>
<body>
    <h1><?php echo $editClient ? 'Редактировать клиента' : 'Добавить клиента'; ?></h1>
    <form method="POST" action="">
        <input type="hidden" name="clientNumber" value="<?php echo $editClient['ClientNumber'] ?? ''; ?>">
        <label for="bankNumber">Номер банка:</label>
        <input type="number" name="bankNumber" value="<?php echo $editClient['BankNumber'] ?? ''; ?>" required><br>

        <label for="phone">Телефон:</label>
        <input type="text" name="phone" value="<?php echo $editClient['Phone'] ?? ''; ?>" required><br>

        <label for="address">Адрес:</label>
        <input type="text" name="address" value="<?php echo $editClient['Address'] ?? ''; ?>"><br>

        <label for="cardNumber">Номер карты:</label>
        <input type="text" name="cardNumber" value="<?php echo $editClient['CardNumber'] ?? ''; ?>" required><br>

        <label for="name">Имя:</label>
        <input type="text" name="name" value="<?php echo $editClient['Name'] ?? ''; ?>" required><br>

        <label for="surname">Фамилия:</label>
        <input type="text" name="surname" value="<?php echo $editClient['Surname'] ?? ''; ?>" required><br>

        <label for="patronymic">Отчество:</label>
        <input type="text" name="patronymic" value="<?php echo $editClient['Patronymic'] ?? ''; ?>"><br>

        <input type="submit" name="<?php echo $editClient ? 'edit_client' : 'add_client'; ?>" value="<?php echo $editClient ? 'Сохранить изменения' : 'Добавить клиента'; ?>">
    </form>

    <br>
    <button onclick="window.location.href='index.php'">Вернуться на главную страницу</button> <!-- Кнопка для возврата на главную страницу -->

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
                <a href="?edit=<?php echo $client['ClientNumber']; ?>">Редактировать</a>
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
