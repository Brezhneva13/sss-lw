<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Админ Панель - Управление Клиентами</title>
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
                    <h1 class="mt-4">Управление Клиентами</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Клиенты</li>
                    </ol>

                    
                    <!-- HTML форма для добавления или редактирования клиента -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Добавить клиента</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="hidden" name="clientNumber" value="">
                                <div class="mb-3">
                                    <label for="bankNumber" class="form-label">Номер банка:</label>
                                    <input type="number" class="form-control" name="bankNumber" value="" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Телефон:</label>
                                    <input type="text" class="form-control" name="phone" value="" required>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Адрес:</label>
                                    <input type="text" class="form-control" name="address" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Номер карты:</label>
                                    <input type="text" class="form-control" name="cardNumber" value="" required>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Имя:</label>
                                    <input type="text" class="form-control" name="name" value="" required>
                                </div>

                                <div class="mb-3">
                                    <label for="surname" class="form-label">Фамилия:</label>
                                    <input type="text" class="form-control" name="surname" value="" required>
                                </div>

                                <div class="mb-3">
                                    <label for="patronymic" class="form-label">Отчество:</label>
                                    <input type="text" class="form-control" name="patronymic" value="">
                                </div>

                                <button type="submit" name="add_client" class="btn btn-primary">
                                    Добавить клиента                                </button>
                            </form>
                        </div>
                    </div>

                    <a href="index.php" class="btn btn-secondary mb-4">Вернуться на главную страницу</a>

                    <h2>Список клиентов</h2>
                    <table class="table table-striped">
                        <thead>
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
                        </thead>
                        <tbody>
                                                        <tr>
                                <td>2</td>
                                <td>89524563</td>
                                <td>сыктывкар</td>
                                <td>22005324</td>
                                <td>Иванов</td>
                                <td>Иван</td>
                                <td>Иванович</td>
                                <td>2</td>
                                <td>
                                    <a href="?edit=2" class="btn btn-warning btn-sm">Редактировать</a>
                                    <a href="?delete=2" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить</a>
                                </td>
                            </tr>
                                                        <tr>
                                <td>3</td>
                                <td>14141</td>
                                <td>71474</td>
                                <td>741747</td>
                                <td>1747</td>
                                <td>17417</td>
                                <td>747</td>
                                <td>2</td>
                                <td>
                                    <a href="?edit=3" class="btn btn-warning btn-sm">Редактировать</a>
                                    <a href="?delete=3" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить</a>
                                </td>
                            </tr>
                                                        <tr>
                                <td>5</td>
                                <td>фф</td>
                                <td>ффф</td>
                                <td>ффф</td>
                                <td>фф</td>
                                <td>фф</td>
                                <td>фф</td>
                                <td>2</td>
                                <td>
                                    <a href="?edit=5" class="btn btn-warning btn-sm">Редактировать</a>
                                    <a href="?delete=5" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить</a>
                                </td>
                            </tr>
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

