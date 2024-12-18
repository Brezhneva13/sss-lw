<?php
include 'db_connection.php';

// Обработка добавления транзакции
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    // Добавьте остальные поля
    $sql = "INSERT INTO Transaction (Date, Amount) VALUES ('$date', '$amount')";
    $conn->query($sql);
}

// Обработка редактирования транзакции
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $transactionNumber = $_POST['transactionNumber'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    // Добавьте остальные поля
    $sql = "UPDATE Transaction SET Date='$date', Amount='$amount' WHERE TransactionNumber='$transactionNumber'";
    $conn->query($sql);
}

// Обработка удаления транзакции
if (isset($_GET['delete'])) {
    $transactionNumber = $_GET['delete'];
    $sql = "DELETE FROM Transaction WHERE TransactionNumber='$transactionNumber'";
    $conn->query($sql);
}

// Получение данных из таблицы Transaction
$sql = "SELECT * FROM Transaction";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Transactions Management</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <h1>Transactions Management</h1>

    <form method="POST" action="transactions.php">
        <input type="date" name="date" required>
        <input type="number" step="0.01" name="amount" placeholder="Amount" required>
        <!-- Добавьте остальные поля -->
        <button type="submit" name="add">Add Transaction</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Transaction Number</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['TransactionNumber']}</td>
                            <td>{$row['Date']}</td>
                            <td>{$row['Amount']}</td>
                            <td>
                                <form method='POST' action='transactions.php'>
                                    <input type='hidden' name='transactionNumber' value='{$row['TransactionNumber']}'>
                                    <input type='date' name='date' value='{$row['Date']}' required>
                                    <input type='number' step='0.01' name='amount' value='{$row['Amount']}' required>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                                <a href='transactions.php?delete={$row['TransactionNumber']}'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
