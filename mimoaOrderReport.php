<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Set default sorting option to 'group_id'
    $sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'group_id';

    // Prepare the SQL statement with sorting
    switch ($sort_option) {
        case 'user_id':
            $orderBy = "u.USER_ID";
            break;
        case 'fname':
            $orderBy = "u.USER_FNAME";
            break;
        case 'lname':
            $orderBy = "u.USER_LNAME";
            break;
        case 'store_name':
            $orderBy = "s.STORE_BRANCH";
            break;
        case 'payment_datetime':
            $orderBy = "pm.PAY_DATETIME";
            break;
        case 'payment_method': // New case for sorting by payment method
            $orderBy = "pm.PAY_METHOD";
            break;
        case 'group_id':
        default:
            $orderBy = "o.GROUP_ID"; // Default sorting by GROUP_ID
            break;
    }

    // SQL query to group orders by GROUP_ID and concatenate order IDs, product names, quantities, and prices
    $sql = "SELECT 
                o.GROUP_ID,
                GROUP_CONCAT(o.ORDER_ID ORDER BY o.ORDER_ID SEPARATOR '<br>') AS ORDER_IDS,
                u.USER_ID, 
                u.USER_FNAME, 
                u.USER_LNAME, 
                s.STORE_ID, 
                s.STORE_BRANCH, 
                GROUP_CONCAT(p.PROD_NAME ORDER BY o.ORDER_ID SEPARATOR '<br>') AS PRODUCT_NAMES,
                GROUP_CONCAT(o.ORDER_QUANTITY ORDER BY o.ORDER_ID SEPARATOR '<br>') AS QUANTITIES,
                GROUP_CONCAT(o.ORDER_TOTALAMOUNT ORDER BY o.ORDER_ID SEPARATOR '<br>') AS PRICES,
                SUM(o.ORDER_TOTALAMOUNT) AS TOTAL_AMOUNT,
                o.ORDER_STATUS,
                pm.PAY_METHOD
            FROM 
                ORDERPROD o
            INNER JOIN 
                USER u ON o.USER_ID = u.USER_ID
            INNER JOIN 
                STORE s ON o.STORE_ID = s.STORE_ID
            INNER JOIN 
                PRODUCT p ON o.PROD_ID = p.PROD_ID
            LEFT JOIN 
                PAYMENT pm ON o.ORDER_ID = pm.ORDER_ID
            WHERE 
                o.ORDER_STATUS = 1";

    // If sorting by store name and a specific store branch is selected, filter by that branch
    if ($sort_option == 'store_name' && isset($_GET['store_branch']) && $_GET['store_branch'] !== 'all') {
        $store_branch = $_GET['store_branch'];
        $sql .= " AND s.STORE_BRANCH = '$store_branch'";
    }

    // If sorting by payment method and a specific payment method is selected, filter by that payment method
    if ($sort_option == 'payment_method' && isset($_GET['payment_method_filter']) && $_GET['payment_method_filter'] !== 'all') {
        $payment_method_filter = $_GET['payment_method_filter'];
        $sql .= " AND pm.PAY_METHOD = '$payment_method_filter'";
    }

    $sql .= " GROUP BY 
                o.GROUP_ID
            ORDER BY 
                $orderBy";

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        die("Error executing the query: " . mysqli_error($connect));
    }

    // Fetch all store branches for the sorting dropdown
    $storeBranchesQuery = "SELECT STORE_ID, STORE_BRANCH FROM STORE ORDER BY STORE_ID";
    $storeBranchesResult = mysqli_query($connect, $storeBranchesQuery);
    $storeBranches = [];
    if ($storeBranchesResult) {
        while ($row = mysqli_fetch_assoc($storeBranchesResult)) {
            $storeBranches[] = $row;
        }
    } else {
        die("Error fetching store branches: " . mysqli_error($connect));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        select {
            padding: 5px;
            border-radius: 5px;
        }

        button {
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 10px;
            font-size: 12px;
            border: none;
            border-radius: 5px;
            background-color: #e93737;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #c53131;
        }
    </style>
</head>
<body>
    <h1>Order Report</h1>
     <!-- Sorting form -->
     <form id="sortForm" method="GET" action="mimoaOrderReport.php">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort">
            <option value="group_id" <?php echo $sort_option == 'group_id' ? 'selected' : ''; ?>>Group ID</option>
            <option value="user_id" <?php echo $sort_option == 'user_id' ? 'selected' : ''; ?>>User ID</option>
            <option value="fname" <?php echo $sort_option == 'fname' ? 'selected' : ''; ?>>First Name</option>
            <option value="lname" <?php echo $sort_option == 'lname' ? 'selected' : ''; ?>>Last Name</option>
            <option value="store_name" <?php echo $sort_option == 'store_name' ? 'selected' : ''; ?>>Store Branch</option>
            <option value="payment_method" <?php echo $sort_option == 'payment_method' ? 'selected' : ''; ?>>Payment Method</option> <!-- Maintained payment method option -->
            <option value="payment_datetime" <?php echo $sort_option == 'payment_datetime' ? 'selected' : ''; ?>>Payment Datetime</option> <!-- Moved datetime option -->
            
        </select>
        <div id="storeBranchDropdown" style="display: <?php echo $sort_option == 'store_name' ? 'block' : 'none'; ?>">
            <label for="store_branch">Store Branch:</label>
            <select name="store_branch" id="store_branch">
                <option value="all" <?php echo (!isset($_GET['store_branch']) || $_GET['store_branch'] == 'all') ? 'selected' : ''; ?>>All Stores</option>
                <?php foreach ($storeBranches as $branch): ?>
                    <option value="<?php echo $branch['STORE_BRANCH']; ?>" <?php echo isset($_GET['store_branch']) && $_GET['store_branch'] == $branch['STORE_BRANCH'] ? 'selected' : ''; ?>><?php echo $branch['STORE_BRANCH']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <a href="mimoaGenerateReport.php" class="back-button">Back</a>
    <table>
        <thead>
            <tr>
                <th>Group ID</th>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Store ID</th>
                <th>Store Branch</th>
                <th>Order IDs</th>
                <th>Product Names</th>
                <th>Quantities</th>
                <th>Prices</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['GROUP_ID'] . "</td>";
                        echo "<td>" . $row['USER_ID'] . "</td>";
                        echo "<td>" . $row['USER_FNAME'] . "</td>";
                        echo "<td>" . $row['USER_LNAME'] . "</td>";
                        echo "<td>" . $row['STORE_ID'] . "</td>";
                        echo "<td>" . $row['STORE_BRANCH'] . "</td>";
                        echo "<td>" . $row['ORDER_IDS'] . "</td>";
                        echo "<td>" . $row['PRODUCT_NAMES'] . "</td>";
                        echo "<td>" . $row['QUANTITIES'] . "</td>";
                        echo "<td>" . $row['PRICES'] . "</td>";
                        echo "<td>" . $row['TOTAL_AMOUNT'] . "</td>";
                        $payment_method = $row['PAY_METHOD'] == 1 ? "Cash" : "GCash"; // Modified to display "Cash" or "GCash" based on PAY_METHOD value
                        echo "<td>" . $payment_method . "</td>";
                        $order_status = $row['ORDER_STATUS'] == 1 ? "Completed" : "Pending";
                        echo "<td>" . $order_status . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No orders found.</td></tr>";
                }
            ?>
        </tbody>
    </table>

    <!-- Your JavaScript code here -->
    <script>
        document.getElementById('sort').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });

        document.getElementById('store_branch').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });

        // Set "All Stores" as default when "Store Name" is selected
        document.addEventListener('DOMContentLoaded', function() {
            var sortOption = document.getElementById('sort');
            var storeBranchDropdown = document.getElementById('storeBranchDropdown');
            var storeBranchSelect = document.getElementById('store_branch');
            
            sortOption.addEventListener('change', function() {
                if (sortOption.value === 'store_name') {
                    storeBranchDropdown.style.display = 'block';
                    storeBranchSelect.value = 'all'; // Set "All Stores" as default
                } else {
                    storeBranchDropdown.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
