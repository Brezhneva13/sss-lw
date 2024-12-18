На основе предоставленной вами структуры таблицы clients, я обновлю код для страницы clients.php, чтобы он соответствовал вашей новой структуре данных. В этом примере я добавлю поля ClientNumber, Phone, Address, CardNumber, Name, Surname, Patronymic и BankNumber. Также учтем, что BankNumber является внешним ключом, ссылающимся на таблицу Bank.

Обновленный код для clients.php
<?php
// Конфигурация базы данных
$host = 'localhost'; // Обычно localhost
$db = 'transaction_sistem'; // Укажите нужную базу данных
$user = 'egor'; // Имя пользователя базы данных
$password = '0000'; // Пароль пользователя базы данных

// Соединение с сервером MySQL
$conn = new mysqli($host, $user, $password);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Установка кодировки UTF-8
$conn->set_charset("utf8mb4");

// Обработка добавления записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_client'])) {
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $cardNumber = $conn->real_escape_string($_POST['card_number']);
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $patronymic = $conn->real_escape_string($_POST['patronymic']);
    $bankNumber = (int)$_POST['bank_number'];

    $sql = "INSERT INTO clients (Phone, Address, CardNumber, Name, Surname, Patronymic, BankNumber) 
            VALUES ('$phone', '$address', '$cardNumber', '$name', '$surname', '$patronymic', $bankNumber)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно добавлена.";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

// Обработка удаления записи
if (isset($_GET['delete'])) {
    $clientNumber = (int)$_GET['delete'];
    $sql = "DELETE FROM clients WHERE ClientNumber = $clientNumber";
    
    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно удалена.";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

// Получение данных из таблицы clients
$result = $conn->query("SELECT * FROM clients");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Управление клиентами</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h1>Управление клиентами</h1>
        
        <!-- Форма для добавления клиента -->
        <h2>Добавить клиента</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="phone" class="form-label">Телефон</label>
                <input type="text" name="phone" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Адрес</label>
                <input type="text" name="address" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="card_number" class="form-label">Номер карты</label>
                <input type="text" name="card_number" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Фамилия</label>
                <input type="text" name="surname" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="patronymic" class="form-label">Отчество</label>
                <input type="text" name="patronymic" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="bank_number" class="form-label">Номер банка</label>
                <input type="number" name="bank_number" class="form-control" required />
            </div>
            <button type="submit" name="add_client" class="btn btn-primary">Добавить</button>
        </form>

        <h2 class="mt-4">Список клиентов</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ClientNumber</th>
                    <th>Телефон</th>
                    <th>Адрес</th>
                    <th>Номер карты</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Отчество</th>
                    <th>Номер банка</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ClientNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['Address']); ?></td>
                        <td><?php echo htmlspecialchars($row['CardNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['Patronymic']); ?></td>
                        <td><?php echo htmlspecialchars($row['BankNumber']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['ClientNumber']; ?>" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту запись?');">Удалить</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>
