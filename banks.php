<?php
session_start(); // Инициализация сессий

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

// Обработка добавления банка
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bank'])) {
    $bankName = $_POST['bankName'];

    // Добавление банка
    $stmt = $mysqli->prepare("INSERT INTO Bank (BankName) VALUES (?)");
    $stmt->bind_param("s", $bankName);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Банк успешно добавлен.";
    } else {
        $_SESSION['error'] = "Ошибка при добавлении банка: " . $stmt->error;
    }

    $stmt->close();
}

// Обработка редактирования банка
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_bank'])) {
    $bankNumber = $_POST['bankNumber'];
    $bankName = $_POST['bankName'];

    // Обновление данных банка
    $stmt = $mysqli->prepare("UPDATE Bank SET BankName=? WHERE BankNumber=?");
    $stmt->bind_param("si", $bankName, $bankNumber);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Данные банка успешно обновлены.";
    } else {
        $_SESSION['error'] = "Ошибка при обновлении данных банка: " . $stmt->error;
    }

    $stmt->close();
}

// Обработка удаления банка
if (isset($_GET['delete'])) {
    $bankNumber = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM Bank WHERE BankNumber = ?");
    $stmt->bind_param("i", $bankNumber);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Банк успешно удален.";
    } else {
        $_SESSION['error'] = "Ошибка при удалении банка: " . $stmt->error;
    }
    $stmt->close();

    // Перенаправление на ту же страницу после удаления
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Завершение скрипта после перенаправления
}

// Получение списка банков
$banks = $mysqli->query("SELECT * FROM Bank");

// Проверка, нужно ли редактировать банк
$editBank = null;
if (isset($_GET['edit'])) {
    $bankNumber = $_GET['edit'];
    $stmt = $mysqli->prepare("SELECT * FROM Bank WHERE BankNumber = ?");
    $stmt->bind_param("i", $bankNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $editBank = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Админ Панель - Управление Банками</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">Админ Панель</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Настройки</a></li>
                    <li><a class="dropdown-item" href="#!">Журнал активности</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">Выход</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Основные</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Панель управления
                        </a>
                        <div class="sb-sidenav-menu-heading">Управление</div>
                        <a class="nav-link" href="pages/client.php">Клиенты</a>
                        <a class="nav-link" href="pages/bank.php">Банки</a>
                        <a class="nav-link" href="pages/terminal.php">Терминалы</a>
                        <a class="nav-link" href="pages/transaction.php">Транзакции</a>
                        <a class="nav-link" href="pages/attempt.php">Попытки</a>
                        <a class="nav-link" href="pages/client_status.php">Статусы клиентов</a>
                        <a class="nav-link" href="pages/card_type.php">Типы карт</a>
                        <a class="nav-link" href="pages/transaction_status.php">Статусы транзакций</a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Вошел как:</div>
                    Администратор
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Управление Банками</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Банки</li>
                    </ol>

                    <!-- Вывод сообщений об успехе или ошибке -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
                        unset($_SESSION['message']); // Удаляем сообщение после отображения
                    }

                    if (isset($_SESSION['error'])) {
                        echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                        unset($_SESSION['error']); // Удаляем сообщение после отображения
                    }
                    ?>

                    <!-- HTML форма для добавления или редактирования банка -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><?php echo $editBank ? 'Редактировать банк' : 'Добавить банк'; ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="hidden" name="bankNumber" value="<?php echo $editBank['BankNumber'] ?? ''; ?>">
                                <div class="mb-3">
                                    <label for="bankName" class="form-label">Название банка:</label>
                                    <input type="text" class="form-control" name="bankName" value="<?php echo $editBank['BankName'] ?? ''; ?>" required>
                                </div>
                                <button type="submit" name="<?php echo $editBank ? 'edit_bank' : 'add_bank'; ?>" class="btn btn-primary">
                                    <?php echo $editBank ? 'Сохранить изменения' : 'Добавить банк'; ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <a href="index.php" class="btn btn-secondary mb-4">Вернуться на главную страницу</a>

                    <h2>Список банков</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Номер банка</th>
                                <th>Название банка</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($bank = $banks->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $bank['BankNumber']; ?></td>
                                <td><?php echo $bank['BankName']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $bank['BankNumber']; ?>" class="btn btn-warning btn-sm">Редактировать</a>
                                    <a href="?delete=<?php echo $bank['BankNumber']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этот банк?');">Удалить</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Ваша Компания 2023</div>
                        <div>
                            <a href="#!">Политика конфиденциальности</a>
                            &middot;
                            <a href="#!">Условия использования</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
// Закрытие соединения
$mysqli->close();
?>
