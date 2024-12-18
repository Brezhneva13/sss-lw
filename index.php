<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Transaction System Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.html">Transaction System</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <!-- Таблица Bank -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Bank Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Bank Number</th>
                                        <th>Bank Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'db.php';
                                    $sql = "SELECT BankNumber, BankName FROM Bank";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["BankNumber"] . "</td>
                                                    <td>" . $row["BankName"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Client -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Client Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Client Number</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Card Number</th>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Patronymic</th>
                                        <th>Bank Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT ClientNumber, Phone, Address, CardNumber, Name, Surname, Patronymic, BankNumber FROM Client";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["ClientNumber"] . "</td>
                                                    <td>" . $row["Phone"] . "</td>
                                                    <td>" . $row["Address"] . "</td>
                                                    <td>" . $row["CardNumber"] . "</td>
                                                    <td>" . $row["Name"] . "</td>
                                                    <td>" . $row["Surname"] . "</td>
                                                    <td>" . $row["Patronymic"] . "</td>
                                                    <td>" . $row["BankNumber"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Terminal -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Terminal Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Terminal Number</th>
                                        <th>Bank Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT TerminalNumber, BankNumber FROM Terminal";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["TerminalNumber"] . "</td>
                                                    <td>" . $row["BankNumber"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Transaction -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Transaction Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Transaction Number</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Client Number</th>
                                        <th>Terminal Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT TransactionNumber, Date, Amount, ClientNumber, TerminalNumber FROM Transaction";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["TransactionNumber"] . "</td>
                                                    <td>" . $row["Date"] . "</td>
                                                    <td>" . $row["Amount"] . "</td>
                                                    <td>" . $row["ClientNumber"] . "</td>
                                                    <td>" . $row["TerminalNumber"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Attempt -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Attempt Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Attempt Number</th>
                                        <th>Date</th>
                                        <th>Transaction Number</th>
                                        <th>Error Description</th>
                                        <th>Error Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT AttemptNumber, Date, TransactionNumber, ErrorDescription, ErrorCode FROM Attempt";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["AttemptNumber"] . "</td>
                                                    <td>" . $row["Date"] . "</td>
                                                    <td>" . $row["TransactionNumber"] . "</td>
                                                    <td>" . $row["ErrorDescription"] . "</td>
                                                    <td>" . $row["ErrorCode"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица ClientStatus -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Client Status Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Client Status ID</th>
                                        <th>Client Status Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT ClientStatusID, ClientStatusName FROM ClientStatus";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["ClientStatusID"] . "</td>
                                                    <td>" . $row["ClientStatusName"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица CardType -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Card Type Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Card Type ID</th>
                                        <th>Card Type Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT CardTypeID, CardTypeName FROM CardType";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["CardTypeID"] . "</td>
                                                    <td>" . $row["CardTypeName"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица TransactionStatus -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Transaction Status Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Transaction Status ID</th>
                                        <th>Transaction Status Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT TransactionStatusID, TransactionStatusName FROM TransactionStatus";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["TransactionStatusID"] . "</td>
                                                    <td>" . $row["TransactionStatusName"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Client_CardType -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Client Card Type Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Client Number</th>
                                        <th>Card Type ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT ClientNumber, CardTypeID FROM Client_CardType";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["ClientNumber"] . "</td>
                                                    <td>" . $row["CardTypeID"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Transaction_TransactionStatus -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Transaction Transaction Status Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Transaction Number</th>
                                        <th>Transaction Status ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT TransactionNumber, TransactionStatusID FROM Transaction_TransactionStatus";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["TransactionNumber"] . "</td>
                                                    <td>" . $row["TransactionStatusID"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Transaction_Interval -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Transaction Interval Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Transaction Number</th>
                                        <th>Interval ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT TransactionNumber, IntervalID FROM Transaction_Interval";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["TransactionNumber"] . "</td>
                                                    <td>" . $row["IntervalID"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Таблица Interval -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Interval Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Interval ID</th>
                                        <th>Interval Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT IntervalID, IntervalValue FROM Interval";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["IntervalID"] . "</td>
                                                    <td>" . $row["IntervalValue"] . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
