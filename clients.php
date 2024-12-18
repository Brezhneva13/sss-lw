<?php
include 'db_connection.php';

// Обработка добавления клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    // Добавьте остальные поля
    $sql = "INSERT INTO Client (Name, Surname) VALUES ('$name', '$surname')";
    $conn->query($sql);
}

// Обработка редактирования клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $clientNumber = $_POST['clientNumber'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    // Добавьте остальные поля
    $sql = "UPDATE Client SET Name='$name', Surname='$surname' WHERE ClientNumber='$clientNumber'";
    $conn->query($sql);
}

// Обработка удаления клиента
if (isset($_GET['delete'])) {
    $clientNumber = $_GET['delete'];
    $sql = "DELETE FROM Client WHERE ClientNumber='$clientNumber'";
    $conn->query($sql);
}

// Получение данных из таблицы Client
$sql = "SELECT * FROM Client";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Clients Management</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <h1>Clients Management</h1>

    <form method="POST" action="clients.php">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="surname" placeholder="Surname" required>
        <!-- Добавьте остальные поля -->
        <button type="submit" name="add">Add Client</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Client Number</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ClientNumber']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Surname']}</td>
                            <td>
                                <form method='POST' action='clients.php'>
                                    <input type='hidden' name='clientNumber' value='{$row['ClientNumber']}'>
                                    <input type='text' name='name' value='{$row['Name']}' required>
                                    <input type='text' name='surname' value='{$row['Surname']}' required>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                                <a href='clients.php?delete={$row['ClientNumber']}'>Delete</a>
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
