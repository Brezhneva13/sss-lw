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
                    <h1 class="mt-4">Управление Клиентами</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Клиенты</li>
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

                    // Создание таблицы ClientStatus, если она не существует
                    $createClientStatusTable = "
                    CREATE TABLE IF NOT EXISTS ClientStatus (
                        ClientStatusID INT AUTO_INCREMENT PRIMARY KEY,
                        StatusName VARCHAR(50) NOT NULL UNIQUE
                    )";
                    $mysqli->query($createClientStatusTable);

                    // Создание таблицы Client, если она не существует
                    $createClientTable = "
                    CREATE TABLE IF NOT EXISTS Client (
                        ClientNumber INT NOT NULL UNIQUE,
                        Phone VARCHAR(20) NOT NULL UNIQUE,
                        Address VARCHAR(100),
                        CardNumber VARCHAR(20) UNIQUE,
                        Name VARCHAR(50) NOT NULL,
                        Surname VARCHAR(50) NOT NULL,
                        Patronymic VARCHAR(50),
                        BankNumber INT,
                        ClientStatusID INT,
                        PRIMARY KEY (ClientNumber),
                        FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber) ON DELETE CASCADE,
                        FOREIGN KEY (ClientStatusID) REFERENCES ClientStatus(ClientStatusID) ON DELETE SET NULL
                    )";
                    $mysqli->query($createClientTable);

                    // Функция для получения списка клиентов
                    function getClients($mysqli) {
                        return $mysqli->query("SELECT * FROM Client");
                    }

                    // Функция для получения списка банков
                    function getBanks($mysqli) {
                        return $mysqli->query("SELECT * FROM Bank");
                    }

                    // Обработка удаления клиента
                    if (isset($_GET['delete'])) {
                        $clientNumber = $_GET['delete'];
                        $stmt = $mysqli->prepare("DELETE FROM Client WHERE ClientNumber = ?");
                        $stmt->bind_param("i", $clientNumber);
                        $stmt->execute();
                        $stmt->close();
                        // Перенаправление на ту же страницу для обновления
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }

                    // Обработка добавления клиента
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_client'])) {
                        $clientNumber = $_POST['clientNumber']; // Получаем номер клиента
                        $bankNumber = $_POST['bankNumber'];
                        $phone = $_POST['phone'];
                        $address = $_POST['address'];
                        $cardNumber = $_POST['cardNumber'];
                        $name = $_POST['name'];
                        $surname = $_POST['surname'];
                        $patronymic = $_POST['patronymic'];

                        // Проверка, существует ли клиент с таким номером
                        $stmt = $mysqli->prepare("SELECT ClientNumber FROM Client WHERE ClientNumber = ?");
                        $stmt->bind_param("i", $clientNumber);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<div class='alert alert-danger'>Ошибка: Клиент с таким номером уже существует.</div>";
                        } else {
                            // Проверка формата телефона
                            if (!preg_match('/^[0-9]{8,20}$/', $phone)) {
                                echo "<div class='alert alert-danger'>Ошибка: Неверный формат телефона. Должно быть от 8 до 20 цифр.</div>";
                                return;
                            }

                            // Проверка формата номера карты
                            if (!preg_match('/^[0-9]{8,20}$/', $cardNumber)) {
                                echo "<div class='alert alert-danger'>Ошибка: Неверный формат номера карты. Должно быть от 8 до 20 цифр.</div>";
                                return;
                            }

                            // Проверка существования банка
                            $stmt = $mysqli->prepare("SELECT BankNumber FROM Bank WHERE BankNumber = ?");
                            $stmt->bind_param("i", $bankNumber);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                // Банк существует, добавляем клиента
                                $stmt = $mysqli->prepare("INSERT INTO Client (ClientNumber, Phone, Address, CardNumber, Name, Surname, Patronymic, BankNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param("issssssi", $clientNumber, $phone, $address, $cardNumber, $name, $surname, $patronymic, $bankNumber);
                                
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Клиент успешно добавлен.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Ошибка при добавлении клиента: " . $stmt->error . "</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Ошибка: указанный банк не существует.</div>";
                            }
                        }

                        $stmt->close();
                    }

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

                        // Обновление информации о клиенте
                        $stmt = $mysqli->prepare("UPDATE Client SET Phone = ?, Address = ?, CardNumber = ?, Name = ?, Surname = ?, Patronymic = ?, BankNumber = ? WHERE ClientNumber = ?");
                        $stmt->bind_param("sssssiii", $phone, $address, $cardNumber, $name, $surname, $patronymic, $bankNumber, $clientNumber);
                        
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Клиент успешно обновлен.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Ошибка при обновлении клиента: " . $stmt->error . "</div>";
                        }

                        $stmt->close();
                        // Перенаправление на ту же страницу для обновления
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }

                    // Получение списка клиентов
                    $clients = getClients($mysqli);

                    // Получение списка банков для выпадающего списка
                    $banks = getBanks($mysqli);
                    ?>

                    <!-- HTML форма для добавления или редактирования клиента -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><?php echo $editClient ? 'Редактировать клиента' : 'Добавить клиента'; ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="clientNumber" class="form-label">Номер клиента:</label>
                                    <input type="text" class="form-control" name="clientNumber" value="<?php echo $editClient['ClientNumber'] ?? ''; ?>" required pattern="^[0-9]+$" title="Номер клиента должен содержать только цифры." inputmode="numeric" maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>

                                <div class="mb-3">
                                    <label for="bankNumber" class="form-label">Выберите банк:</label>
                                    <select class="form-control" name="bankNumber" required>
                                        <option value="">Выберите банк</option>
                                        <?php while ($bank = $banks->fetch_assoc()): ?>
                                            <option value="<?php echo $bank['BankNumber']; ?>" <?php echo (isset($editClient) && $editClient['BankNumber'] == $bank['BankNumber']) ? 'selected' : ''; ?>>
                                                <?php echo $bank['BankName']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Телефон:</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo $editClient['Phone'] ?? ''; ?>" required pattern="^[0-9]{8,20}$" title="Неверный формат телефона. Должно быть от 8 до 20 цифр." inputmode="numeric" maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Адрес:</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo $editClient['Address'] ?? ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Номер карты:</label>
                                    <input type="text" class="form-control" name="cardNumber" value="<?php echo $editClient['CardNumber'] ?? ''; ?>" required pattern="^[0-9]{8,20}$" title="Неверный формат номера карты. Должно быть от 8 до 20 цифр." inputmode="numeric" maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Имя:</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo $editClient['Name'] ?? ''; ?>" required pattern="^[A-Za-zА-Яа-яЁё\s]+$" title="Имя должно содержать только буквы.">
                                </div>

                                <div class="mb-3">
                                    <label for="surname" class="form-label">Фамилия:</label>
                                    <input type="text" class="form-control" name="surname" value="<?php echo $editClient['Surname'] ?? ''; ?>" required pattern="^[A-Za-zА-Яа-яЁё\s]+$" title="Фамилия должна содержать только буквы.">
                                </div>

                                <div class="mb-3">
                                    <label for="patronymic" class="form-label">Отчество:</label>
                                    <input type="text" class="form-control" name="patronymic" value="<?php echo $editClient['Patronymic'] ?? ''; ?>" pattern="^[A-Za-zА-Яа-яЁё\s]*$" title="Отчество должно содержать только буквы.">
                                </div>

                                <button type="submit" name="<?php echo $editClient ? 'edit_client' : 'add_client'; ?>" class="btn btn-primary">
                                    <?php echo $editClient ? 'Сохранить изменения' : 'Добавить клиента'; ?>
                                </button>
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
                                    <a href="?edit=<?php echo $client['ClientNumber']; ?>" class="btn btn-warning btn-sm">Редактировать</a>
                                    <a href="?delete=<?php echo $client['ClientNumber']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить</a>
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
