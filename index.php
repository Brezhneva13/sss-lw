

<?php
include 'db_connection.php'; // Подключение к базе данных

// Запросы для получения данных для карточек и графиков
$totalBanksQuery = "SELECT COUNT(*) as total FROM Bank";
$totalClientsQuery = "SELECT COUNT(*) as total FROM Client";
$totalTransactionsQuery = "SELECT COUNT(*) as total FROM Transaction";

$totalBanksResult = $conn->query($totalBanksQuery);
$totalClientsResult = $conn->query($totalClientsQuery);
$totalTransactionsResult = $conn->query($totalTransactionsQuery);

$totalBanks = $totalBanksResult->fetch_assoc()['total'];
$totalClients = $totalClientsResult->fetch_assoc()['total'];
$totalTransactions = $totalTransactionsResult->fetch_assoc()['total'];

// Получение данных для графиков (например, по транзакциям)
$transactionDataQuery = "SELECT DATE(Date) as date, SUM(Amount) as total FROM Transaction GROUP BY DATE(Date)";
$transactionDataResult = $conn->query($transactionDataQuery);

$transactionDates = [];
$transactionTotals = [];

while ($row = $transactionDataResult->fetch_assoc()) {
    $transactionDates[] = $row['date'];
    $transactionTotals[] = $row['total'];
}

$conn->close(); // Закрываем соединение с базой данных
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Home
                        </a>
                        <a class="nav-link" href="banks.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-university"></i></div>
                            Banks
                        </a>
                        <a class="nav-link" href="clients.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Clients
                        </a>
                        <a class="nav-link" href="transactions.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                            Transactions
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Welcome to the Transaction Management System</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Total Banks: <?php echo $totalBanks; ?></div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="banks.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Total Clients: <?php echo $totalClients; ?></div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="clients.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Total Transactions: <?php echo $totalTransactions; ?></div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="transactions.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Transaction Amount Over Time
                                </div>
                                <div class="card-body">
                                    <canvas id="transactionChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    Transaction Data
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Transaction Number</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Получение данных из таблицы Transaction для отображения в таблице
                                            $transactionQuery = "SELECT * FROM Transaction";
                                            $transactionResult = $conn->query($transactionQuery);

                                            if ($transactionResult->num_rows > 0) {
                                                while ($row = $transactionResult->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>{$row['TransactionNumber']}</td>
                                                            <td>{$row['Date']}</td>
                                                            <td>{$row['Amount']}</td>
                                                          </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No transactions found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">© 2023 Your Company</div>
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

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        // График транзакций
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($transactionDates); ?>,
                datasets: [{
                    label: 'Total Amount',
                    data: <?php echo json_encode($transactionTotals); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
