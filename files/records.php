<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Records</title>
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.tailwindcss.com"></script>

  
</head>
<body class="bg-[#111827]">

    <nav class="fixed top-0 left-0 right-0 z-50 h-[60px] bg-gray-600/10 backdrop-blur-md border-b border-gray-600 pt-2 pb-2 transition duration-300 ease-in-out">
        <ul class="mt-[-5px]">
            <li style="float: left;"><a href="upload.php">Create Record</a></li>
            <li style="float: left;"><a href="home.php">Home</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
            
        </ul>
    </nav>

    <div class="overflow-x-auto mt-[5%] mx-[20%]">

<form>
    <input type="text" id="search" placeholder="Search by id" style="width: 30%; padding: 10px; margin-bottom: 10px;  font-size: 20px;"
    class="bg-gray-600/25 hover:bg-gray-600/50 block w-full rounded-md border-0 py-1.5 text-gray-100 shadow-sm bg-ray-600 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 
    focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
</form>

<table class="min-w-[90%] divide-y divide-gray-700">
            <thead class="bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                        Date of installation
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                        Type of service
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                       Timestamp
                    </th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                <?php
                try {
                    $query = "SELECT * FROM table_backup";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'><a href='details.php?id={$row['id']}'>" . htmlspecialchars($row['customer_name']) . "</a></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'><a href='details.php?id={$row['id']}'>" . htmlspecialchars($row['date']) . "</a></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'><a href='details.php?id={$row['id']}'>" . htmlspecialchars($row['type_of_service']) . "</a></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'><a href='details.php?id={$row['id']}'>" . htmlspecialchars($row['timestamp_field']) . "</a></td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }

                    $result->free_result();
                    $conn->close();
                } catch (Exception $e) {
                    echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for search functionality -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var query = $(this).val();
                $.ajax({
                    url: 'bsearch.php',
                    method: 'POST',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        $('tbody').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
