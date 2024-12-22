<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Админ Панель - Управление Терминалами</title>
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
                    <a class="nav-link" href="client.php">Клиенты</a>
                    <a class="nav-link" href="terminal.php">Терминалы</a>
                    <a class="nav-link" href="transaction.php">Транзакции</a>
                    <a class="nav-link" href="attempt.php">Попытки</a>
                    <a class="nav-link" href="card_type.php">Типы карт</a>
                    <a class="nav-link" href="bank.php">Банк</a>
                    <a class="nav-link" href="transaction_status.php">Статусы транзакций</a>
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
                <h1 class="mt-4">Управление Терминалами</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Терминалы</li>
                </ol>

                <?php
                // Подключение к базе данных
                $host = 'localhost'; // или ваш хост
                $user = 'admin'; // ваш пользователь
                $password = '0000'; // ваш пароль
                $database = 'sistem'; // имя базы данных

                $mysqli = new mysqli($host, $user, $password, $database);

                // Проверка соединения
                if ($mysqli->connect_error) {
                    die("Ошибка подключения: " . $mysqli->connect_error);
                }

                // Создание таблицы Bank, если она не существует
                $createBankTable = "
                CREATE TABLE IF NOT EXISTS Bank (
                    BankNumber INT AUTO_INCREMENT PRIMARY KEY,
                    BankName VARCHAR(100) NOT NULL UNIQUE
                )";
                $mysqli->query($createBankTable);

                // Создание таблицы Terminal, если она не существует
                $createTerminalTable = "
                CREATE TABLE IF NOT EXISTS Terminal (
                    TerminalNumber INT AUTO_INCREMENT PRIMARY KEY,
                    TerminalName VARCHAR(255) NOT NULL,
                    BankNumber INT,
                    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber) ON DELETE SET NULL
                )";
                $mysqli->query($createTerminalTable);

                // Функция для получения списка терминалов
                function getTerminals($mysqli) {
                    return $mysqli->query("SELECT * FROM Terminal");
                }

                // Функция для получения списка банков
                function getBanks($mysqli) {
                    return $mysqli->query("SELECT * FROM Bank");
                }

                // Обработка удаления терминала
                if (isset($_GET['delete'])) {
                    $terminalNumber = $_GET['delete'];
                    $stmt = $mysqli->prepare("DELETE FROM Terminal WHERE TerminalNumber = ?");
                    $stmt->bind_param("i", $terminalNumber);
                    $stmt->execute();
                    $stmt->close();
                    // Перенаправление на ту же страницу для обновления
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }

                // Обработка добавления терминала
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_terminal'])) {
                    $terminalNumber = $_POST['terminalNumber'];
                    $terminalName = $_POST['terminalName'];
                    $bankNumber = $_POST['bankNumber'];

                    // Проверка существования терминала
                    $stmt = $mysqli->prepare("SELECT TerminalNumber FROM Terminal WHERE TerminalNumber = ?");
                    $stmt->bind_param("i", $terminalNumber);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 0) {
                        // Проверка существования банка
                        $stmt = $mysqli->prepare("SELECT BankNumber FROM Bank WHERE BankNumber = ?");
                        $stmt->bind_param("i", $bankNumber);
                        $stmt->execute();
                        $resultBank = $stmt->get_result();

                        if ($resultBank->num_rows > 0) {
                            // Банк существует, добавляем терминал
                            $stmt = $mysqli->prepare("INSERT INTO Terminal (TerminalNumber, TerminalName, BankNumber) VALUES (?, ?, ?)");
                            $stmt->bind_param("isi", $terminalNumber, $terminalName, $bankNumber);
                            
                            if ($stmt->execute()) {
                                echo "<div class='alert alert-success'>Терминал успешно добавлен.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Ошибка при добавлении терминала: " . $stmt->error . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Ошибка: указанный банк не существует.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Ошибка: терминал с таким номером уже существует.</div>";
                    }

                    $stmt->close();
                }

                // Обработка редактирования терминала
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_terminal'])) {
                    $terminalNumber = $_POST['terminalNumber'];
                    $terminalName = $_POST['terminalName'];
                    $bankNumber = $_POST['bankNumber'];

                    // Проверка существования банка
                    $stmt = $mysqli->prepare("SELECT BankNumber FROM Bank WHERE BankNumber = ?");
                    $stmt->bind_param("i", $bankNumber);
                    $stmt->execute();
                    $resultBank = $stmt->get_result();

                    if ($resultBank->num_rows > 0) {
                        // Банк существует, обновляем терминал
                        $stmt = $mysqli->prepare("UPDATE Terminal SET TerminalName = ?, BankNumber = ? WHERE TerminalNumber = ?");
                        $stmt->bind_param("sii", $terminalName, $bankNumber, $terminalNumber);
                        
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Терминал успешно обновлен.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Ошибка при обновлении терминала: " . $stmt->error . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Ошибка: указанный банк не существует.</div>";
                    }

                    $stmt->close();
                }

                // Получение списка терминалов
                $terminals = getTerminals($mysqli);

                // Получение списка банков для выпадающего списка
                $banks = getBanks($mysqli);
                ?>

                <!-- HTML форма для добавления терминала -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Добавить терминал</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="terminalNumber" class="form-label">Номер терминала:</label>
                                <input type="number" class="form-control" name="terminalNumber" required>
                            </div>
                            <div class="mb-3">
                                <label for="terminalName" class="form-label">Название терминала:</label>
                                <input type="text" class="form-control" name="terminalName" required>
                            </div>
                            <div class="mb-3">
                                <label for="bankNumber" class="form-label">Выберите банк:</label>
                                <select class="form-control" name="bankNumber" required>
                                    <option value="">Выберите банк</option>
                                    <?php while ($bank = $banks->fetch_assoc()): ?>
                                        <option value="<?php echo $bank['BankNumber']; ?>">
                                            <?php echo $bank['BankName']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <button type="submit" name="add_terminal" class="btn btn-primary">
                                Добавить терминал
                            </button>
                        </form>
                    </div>
                </div>

                <h2>Список терминалов</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Номер терминала</th>
                            <th>Название терминала</th>
                            <th>Номер банка</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($terminal = $terminals->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $terminal['TerminalNumber']; ?></td>
                            <td><?php echo $terminal['TerminalName']; ?></td>
                            <td><?php echo $terminal['BankNumber']; ?></td>
                            <td>
                                <a href="?delete=<?php echo $terminal['TerminalNumber']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этот терминал?');">Удалить</a>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $terminal['TerminalNumber']; ?>">Редактировать</button>

                                <!-- Модальное окно для редактирования терминала -->
                                <div class="modal fade" id="editModal<?php echo $terminal['TerminalNumber']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Редактировать терминал</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="terminalNumber" value="<?php echo $terminal['TerminalNumber']; ?>">
                                                    <div class="mb-3">
                                                        <label for="terminalName" class="form-label">Название терминала:</label>
                                                        <input type="text" class="form-control" name="terminalName" value="<?php echo $terminal['TerminalName']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="bankNumber" class="form-label">Выберите банк:</label>
                                                        <select class="form-control" name="bankNumber" required>
                                                            <option value="">Выберите банк</option>
                                                            <?php
                                                            $banksEdit = getBanks($mysqli);
                                                            while ($bankEdit = $banksEdit->fetch_assoc()): ?>
                                                                <option value="<?php echo $bankEdit['BankNumber']; ?>" <?php echo ($bankEdit['BankNumber'] == $terminal['BankNumber']) ? 'selected' : ''; ?>>
                                                                    <?php echo $bankEdit['BankName']; ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" name="edit_terminal" class="btn btn-primary">Сохранить изменения</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<?php // Закрытие соединения
$mysqli->close();
?>
