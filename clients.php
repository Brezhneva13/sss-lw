<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Client Management - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="../index.php">Admin Panel</a>
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
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link" href="client.php">Клиенты</a>
                        <a class="nav-link" href="terminal.php">Терминалы</a>
                        <a class="nav-link" href="transaction.php">Транзакции</a>
                        <a class="nav-link" href="attempt.php">Попытки</a>
                        <a class="nav-link" href="client_status.php">Статусы клиентов</a>
                        <a class="nav-link" href="card_type.php">Типы карт</a>
                        <a class="nav-link" href="transaction_status.php">Статусы транзакций</a>
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
                <header>
                    <h1>Управление клиентами</h1>
                </header>

                <h2>Список клиентов</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID клиента</th>
                            <th>Имя клиента</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../db_connection.php';
                        // Получение всех клиентов
                        $clientsQuery = "SELECT * FROM Client";
                        $clientsResult = $conn->query($clientsQuery);
                        if ($clientsResult->num_rows > 0) {
                            while ($row = $clientsResult->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['ClientID']}</td>
                                        <td>{$row['ClientName']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>Нет клиентов</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h3>Добавить нового клиента</h3>
                <form method="POST" action="client.php">
                    <input type="text" name="client_name" placeholder="Имя клиента" required>
                    <button type="submit" name="add_client">Добавить</button>
                </form>

                <h3>Удалить клиента</h3>
                <form method="POST" action="client.php">
                    <input type="number" name="client_id" placeholder="ID клиента" required>
                    <button type="submit" name="delete_client">Удалить</button>
                </form>
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
