<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }
  
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5c6961;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 30px;
            background-color: #fff;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            margin-bottom: 30px;
            color: #333;
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        button {
            padding: 15px;
            font-size: 16px;
            border: 2px solid transparent;
            border-radius: 10px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            width: 100%;
            outline: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        button.logout {
            background-color: #dc3545;
            margin-top: 20px;
            border-color: #dc3545;
        }

        button.logout:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Admin Dashboard</h1>
        <div class="button-container">
            <form method="POST" action="mimoaAdminTransactions.php">
                <button>Transactions</button>
            </form>

            <form method="POST" action="mimoaAdminProdAvail.php">
                <button>Product Availability</button>
            </form>
            
            <form method="POST" action="mimoaAdminProd.php">
                <button>Product</button>
            </form>

            <form method="POST" action="mimoaAdminStore.php">
                <button>Store</button>
            </form>

            <form method="post" action="mimoaGenerateReport.php">
                <button>Generate Report</button>
            </form>
        </div>
        <form action="mimoaLogout.php" method="POST">
            <button type="submit" class="logout">Logout</button>
        </form>
    </div>
</body>
</html>