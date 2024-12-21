<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Регистрация</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <?php
        // Подключение к базе данных (замените на свои данные)
        $servername = "localhost";
        $username = "your_db_username";
        $password = "your_db_password";
        $dbname = "your_db_name";

        // Создаем соединение
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Проверяем соединение
        if ($conn->connect_error) {
            die("Ошибка подключения к БД: " . $conn->connect_error);
        }

        $message = ""; // Переменная для сообщений

        // Проверка, была ли отправлена форма
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Получение данных из формы
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            // Проверка на совпадение паролей
            if ($password != $confirm_password) {
                $message = "Пароли не совпадают.";
            } else {
                // Хеширование пароля (очень важно для безопасности)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // SQL-запрос для добавления нового пользователя
                 $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

                 $stmt = $conn->prepare($sql);
                  if($stmt === false) {
                     $message = "Ошибка подготовки запроса: " . $conn->error;
                  } else {
                     $stmt->bind_param("sss", $username, $email, $hashed_password);
                    if ($stmt->execute() ) {
                        $message = "Регистрация прошла успешно!";
                        header("Location: index.html");
                        exit();
                     } else {
                     $message = "Ошибка при регистрации: " . $stmt->error;
                     }
                  }
            }
        }

        if (!empty($message)) {
             echo "<div class='message'>" . $message . "</div>";
        }

        ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Логин</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Подтвердите пароль</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
         <div class="mt-3">
           <button class="btn btn-link" onclick="location.href='index.html'">Вход</button>
        </div>
    </div>
</body>
</html>

<?php
    if (isset($conn)){
        $conn->close();
    }

?>
