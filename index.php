<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Admin Panel</title>
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
                        <div class="sb-sidenav-menu-heading">Database Management</div>
                        <a class="nav-link" href="?table=clients">Clients</a>
                        <a class="nav-link" href="?table=terminals">Terminals</a>
                        <a class="nav-link" href="?table=transactions">Transactions</a>
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

                    // Соответствие английских и русских названий таблиц
                    $tableNames = [
                        'clients' => 'Клиенты',
                        'terminals' => 'Терминалы',
                        'transactions' => 'Транзакции',
                    ];

                    // Функция для отображения таблиц
                    function getTables($tableNames) {
                        echo "<h2>Таблицы в базе данных</h2>";
                        echo "<ul>";
                        foreach ($tableNames as $table => $displayName) {
                            echo '<li><a href="?table=' . urlencode($table) . '">' . htmlspecialchars($displayName) . '</a></li>';
                        }
                        echo "</ul>";
                    }

                    // Функция для отображения данных таблицы
                    function getTableData($conn, $table, $tableNames) {
                        $displayName = isset($tableNames[$table]) ? $tableNames[$table] : $table;
                        echo "<h2>Данные таблицы: " . htmlspecialchars($displayName) . "</h2>";

                        // Вывод данных таблицы
                        $result = $conn->query("SELECT * FROM " . $conn->real_escape_string($table));
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
                    }

                    // Основная логика
                    if (isset($_GET['table'])) {
                        getTableData($conn, $_GET['table'], $tableNames);
                    } else {
                        getTables($tableNames);
                    }

                    // Закрытие соединения
                    $conn->close();
                    ?>
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
