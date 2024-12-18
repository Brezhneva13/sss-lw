<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">Admin Panel</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link" href="pages/client.php">Клиенты</a>
                        <a class="nav-link" href="pages/terminal.php">Терминалы</a>
                        <a class="nav-link" href="pages/transaction.php">Транзакции</a>
                        <a class="nav-link" href="pages/attempt.php">Попытки</a>
                        <a class="nav-link" href="pages/client_status.php">Статусы клиентов</a>
                        <a class="nav-link" href="pages/card_type.php">Типы карт</a>
                        <a class="nav-link" href="pages/transaction_status.php">Статусы транзакций</a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <!-- Здесь начинается PHP-код для отображения таблиц и данных -->
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

                    // Функция для отображения таблиц
                    function getTables($conn, $db) {
                        $conn->select_db($db);
                        echo "<h2>Таблицы в базе данных: " . htmlspecialchars($db) . "</h2>";
                        echo "<ul>";
                        $result = $conn->query("SHOW TABLES");
                        while ($row = $result->fetch_row()) {
                            echo '<li><a href="?db=' . urlencode($db) . '&table=' . urlencode($row[0]) . '">' . htmlspecialchars($row[0]) . '</a></li>';
                        }
                        echo "</ul>";
                    }

                    // Функция для отображения данных таблицы
                    function getTableData($conn, $db, $table) {
                        $conn->select_db($db);
                        echo "<h2>Данные таблицы: " . htmlspecialchars($table) . "</h2>";

                        // Вывод структуры таблицы
                        $result = $conn->query("SHOW COLUMNS FROM " . $conn->real_escape_string($table));
                        echo "<h3>Структура таблицы</h3>";
                        echo "<table class='table'>";
                        echo "<tr><th>Поле</th><th>Тип</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . htmlspecialchars($row['Field']) . "</td><td>" . htmlspecialchars($row['Type']) . "</td></tr>";
                        }
                        echo "</table>";

                        // Пагинация
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $limit = 50;
                        $offset = ($page - 1) * $limit;

                        // Вывод данных таблицы
                        $result = $conn->query("SELECT * FROM " . $conn->real_escape_string($table) . " LIMIT $limit OFFSET $offset");
                        echo "<h3>Данные таблицы</h3>";
                        echo "<table class='table'><tr>";

                        // Получаем названия колонок
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            echo "<th>" . htmlspecialchars($field->name) . "</th>";
                        }
                        echo "</tr>";

                        // Вывод данных
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";

                        // Если есть данные для пагинации
                        $resultTotal = $conn->query("SELECT COUNT(*) AS total FROM " . $conn->real_escape_string($table));
                        $total = $resultTotal->fetch_assoc()['total'];
                        $totalPages = ceil($total / $limit);

                        // Вывод пагинации
                        echo "<div>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<a href="?db=' . urlencode($db) . '&table=' . urlencode($table) . '&page=' . $i . '">' . $i . '</a> ';
                        }
                        echo "</div>";
                    }

                    // Основная логика
                    $db = $db; // Используем заранее определенную базу данных
                    if (isset($_GET['table'])) {
                        getTableData($conn, $db, $_GET['table']);
                    } else {
                        getTables($conn, $db);
                    }

                    // Закрытие соединения
                    $conn->close();
                    ?>
                    <!-- Здесь заканчивается PHP-код -->
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
