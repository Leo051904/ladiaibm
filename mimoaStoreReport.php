<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }
  
    $user_id = $_SESSION['user_id'];

    // Set default sorting option
    $sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'store_id';

    // Prepare SQL statement with sorting
    $sql = "SELECT * FROM store ORDER BY ";
    switch ($sort_option) {
        case 'branch':
            $sql .= "STORE_BRANCH";
            break;
        case 'city':
            $sql .= "STORE_CITY";
            break;
        case 'province':
            $sql .= "STORE_PROVINCE";
            break;
        case 'timestamp':
            $sql .= "STORE_DATETIME DESC";
            break;
        case 'store_id':
        default:
            $sql .= "STORE_ID";
            break;
    }

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        die("Error executing the query: " . mysqli_error($connect));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Report</title>
    <style>
        /* Your CSS styles here */
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
        button {
            display: none; /* Hide the sort button */
        }
    </style>
</head>
<body>
    <h1>Store Report</h1>
    
    <!-- Sorting form -->
    <form id="sortForm" method="GET" action="mimoaStoreReport.php">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort">
            <option value="store_id" <?php echo $sort_option == 'store_id' ? 'selected' : ''; ?>>Store ID</option>
            <option value="branch" <?php echo $sort_option == 'branch' ? 'selected' : ''; ?>>Branch</option>
            <option value="city" <?php echo $sort_option == 'city' ? 'selected' : ''; ?>>City</option>
            <option value="province" <?php echo $sort_option == 'province' ? 'selected' : ''; ?>>Province</option>
            <option value="timestamp" <?php echo $sort_option == 'timestamp' ? 'selected' : ''; ?>>Timestamp</option>
        </select>
        <!-- No need for the sort button -->
    </form>
    <br>
    <a href="mimoaGenerateReport.php" class="back-button">Back</a>
    <table>
        <thead>
            <tr>
                <th>Store ID</th>
                <th>Branch</th>
                <th>City</th>
                <th>Province</th>
                <th>Phone</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['STORE_ID'] . "</td>";
                        echo "<td>" . $row['STORE_BRANCH'] . "</td>";
                        echo "<td>" . $row['STORE_CITY'] . "</td>";
                        echo "<td>" . $row['STORE_PROVINCE'] . "</td>";
                        echo "<td>" . $row['STORE_PHONE'] . "</td>";
                        echo "<td>" . $row['STORE_DATETIME'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No stores found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    
    <!-- Back button -->
    
    <!-- Your JavaScript code here -->
    <script>
        document.getElementById('sort').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });
    </script>
</body>
</html>
