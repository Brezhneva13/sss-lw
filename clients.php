
<?php
// Конфигурация базы данных
$host = 'localhost'; 
$db = 'transaction_sistem'; 
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

// Обработка добавления, редактирования и удаления записей
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = 'Client'; // Название таблицы

    // Добавление новой записи
    if (isset($_POST['add'])) {
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        $cardNumber = $conn->real_escape_string($_POST['card_number']);
        $name = $conn->real_escape_string($_POST['name']);
        $surname = $conn->real_escape_string($_POST['surname']);
        $patronymic = $conn->real_escape_string($_POST['patronymic']);
        $bankNumber = (int)$_POST['bank_number'];

        $conn->query("INSERT INTO $table (Phone, Address, CardNumber, Name, Surname, Patronymic, BankNumber) 
                       VALUES ('$phone', '$address', '$cardNumber', '$name', '$surname', '$patronymic', '$bankNumber')");
    }

    // Удаление записи
    if (isset($_POST['delete'])) {
        $clientNumber = (int)$_POST['client_number'];
        $conn->query("DELETE FROM $table WHERE ClientNumber = $clientNumber");
    }

    // Редактирование записи
    if (isset($_POST['edit'])) {
        $clientNumber = (int)$_POST['client_number'];
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        $cardNumber = $conn->real_escape_string($_POST['card_number']);
        $name = $conn->real_escape_string($_POST['name']);
        $surname = $conn->real_escape_string($_POST['surname']);
        $patronymic = $conn->real_escape_string($_POST['patronymic']);
        $bankNumber = (int)$_POST['bank_number'];

        $conn->query("UPDATE $table SET Phone = '$phone', Address = '$address', CardNumber = '$cardNumber', 
                       Name = '$name', Surname = '$surname', Patronymic = '$patronymic', BankNumber = '$bankNumber' 
                       WHERE ClientNumber = $clientNumber");
    }
}

// Получаем данные из таблицы
$result = $conn->query("SELECT * FROM Client");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Клиенты</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Клиенты</h1>
        <table class="table">
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
                        <button class="btn btn-primary btn-sm edit-btn" data-client-number="<?php echo htmlspecialchars($row['ClientNumber']); ?>" 
                                data-values='<?php echo htmlspecialchars(json_encode($row)); ?>'>Редактировать</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-client-number="<?php echo htmlspecialchars($row['ClientNumber']); ?>">Удалить</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Добавить нового клиента</h3>
        <form id="addForm" method="post">
            <div class="mb-3">
                <input type="text" name="phone" placeholder="Телефон" required>
            </div>
            <div class="mb-3">
                <input type="text" name="address" placeholder="Адрес">
            </div>
            <div class="mb-3">
                <input type="text" name="card_number" placeholder="Номер карты">
            </div>
            <div class="mb-3">
                <input type="text" name="name" placeholder="Имя" required>
            </div>
            <div class="mb-3">
                <input type="text" name="surname" placeholder="Фамилия" required>
            </div>
            <div class="mb-3">
                <input type="text" name="patronymic" placeholder="Отчество">
            </div>
            <div class="mb-3">
                <input type="number" name="bank_number" placeholder="Номер банка">
            </div>
            <button type="submit" name="add" class="btn btn-success">Добавить</button>
        </form>
    </div>

    <!-- Модальное окно для редактирования -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Редактирование клиента</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post">
                        <input type="hidden" name="client_number" id="editClientNumber">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editPhone" name="phone" placeholder="Телефон" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editAddress" name="address" placeholder="Адрес">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editCardNumber" name="card_number" placeholder="Номер карты">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editName" name="name" placeholder="Имя" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editSurname" name="surname" placeholder="Фамилия" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="editPatronymic" name="patronymic" placeholder="Отчество">
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" id="editBankNumber" name="bank_number" placeholder="Номер банка">
                        </div>
                        <button type="submit" name="edit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для подтверждения удаления -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены, что хотите удалить этого клиента?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="post">
                        <input type="hidden" name="client_number" id="deleteClientNumber">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" name="delete" class="btn btn-danger">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Открытие модального окна для редактирования
            $('.edit-btn').on('click', function() {
                const clientNumber = $(this).data('client-number');
                const values = $(this).data('values');
                
                $('#editClientNumber').val(clientNumber);
                $('#editPhone').val(values.Phone);
                $('#editAddress').val(values.Address);
                $('#editCardNumber').val(values.CardNumber);
                $('#editName').val(values.Name);
                $('#editSurname').val(values.Surname);
                $('#editPatronymic').val(values.Patronymic);
                $('#editBankNumber').val(values.BankNumber);

                $('#editModal').modal('show');
            });

            // Открытие модального окна для удаления
            $('.delete-btn').on('click', function() {
                const clientNumber = $(this).data('client-number');
                $('#deleteClientNumber').val(clientNumber);
                $('#deleteModal').modal('show');
            });
        });
    </script>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>
