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
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    
    $sql = "INSERT INTO clients (name, email) VALUES ('$name', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно добавлена.";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

// Обработка удаления записи
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM clients WHERE id = $id";
    
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
                <label for="name" class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            <button type="submit" name="add_client" class="btn btn-primary">Добавить</button>
        </form>

        <h2 class="mt-4">Список клиентов</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту запись?');">Удалить</a>
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
