<?php
include 'db_connection.php';

// Обработка добавления банка
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $bankName = $_POST['bankName'];
    $sql = "INSERT INTO Bank (BankName) VALUES ('$bankName')";
    $conn->query($sql);
}

// Обработка редактирования банка
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $bankNumber = $_POST['bankNumber'];
    $bankName = $_POST['bankName'];
    $sql = "UPDATE Bank SET BankName='$bankName' WHERE BankNumber='$bankNumber'";
    $conn->query($sql);
}

// Обработка удаления банка
if (isset($_GET['delete'])) {
    $bankNumber = $_GET['delete'];
    $sql = "DELETE FROM Bank WHERE BankNumber='$bankNumber'";
    $conn->query($sql);
}

// Получение данных из таблицы Bank
$sql = "SELECT * FROM Bank";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Banks Management</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <h1>Banks Management</h1>

    <form method="POST" action="banks.php">
        <input type="text" name="bankName" placeholder="Bank Name" required>
        <button type="submit" name="add">Add Bank</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Bank Number</th>
                <th>Bank Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['BankNumber']}</td>
                            <td>{$row['BankName']}</td>
                            <td>
                                <form method='POST' action='banks.php'>
                                    <input type='hidden' name='bankNumber' value='{$row['BankNumber']}'>
                                    <input type='text' name='bankName' value='{$row['BankName']}' required>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                                <a href='banks.php?delete={$row['BankNumber']}'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
