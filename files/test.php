<?php
$host = 'mysql'; // Docker service name if using Docker Compose
$dbname = 'mydatabase';
$username = 'user';
$password = 'password';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for items per day
$sql_day = "SELECT DATE(date) as date, COUNT(*) as item_count
            FROM tb_images
            GROUP BY DATE(date)";
$result_day = $conn->query($sql_day);

// Prepare data points for CanvasJS
$dataPoints = array();
while ($row = $result_day->fetch_assoc()) {
    $dataPoints[] = array("label" => $row['date'], "y" => $row['item_count']);
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    exportEnabled: true,
    theme: "light2", // Change theme to light1, light2, dark1, dark2
    title:{
        text: "Items Count by Day"
    },
    axisY: {
        title: "Number of Items",
        includeZero: true
    },
    axisX: {
        title: "Date",
        interval: 1, // Ensures every day is displayed
        labelAngle: -45 // Rotates labels for better readability
    },
    data: [{
        type: "spline", // Change to "column", "bar", "line", etc.
        indexLabel: "{y}",
        indexLabelFontColor: "#5A5757",
        indexLabelPlacement: "outside",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();
 
}
</script>
</head>
<body>
<h2>Items Count by Day</h2>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>


<?php
$host = 'mysql'; // Docker service name if using Docker Compose
$dbname = 'mydatabase';
$username = 'user';
$password = 'password';
 
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Query for items per day
$sql_day = "SELECT DATE(date) as date, COUNT(*) as item_count
            FROM tb_images
            GROUP BY DATE(date)";
$result_day = $conn->query($sql_day);

// Query for items per week
$sql_week = "SELECT YEARWEEK(date, 1) as week, COUNT(*) as item_count
             FROM tb_images
             GROUP BY YEARWEEK(date, 1)";
$result_week = $conn->query($sql_week);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Items Count by Day</h2>
<table border="1">
    <tr>
        <th>Date</th>
        <th>Item Count</th>
    </tr>
    <?php while ($row = $result_day->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['item_count']; ?></td>
    </tr>
    <?php } ?>
</table>

<h2>Items Count by Week</h2>
<table border="1">
    <tr>
        <th>Week</th>
        <th>Item Count</th>
    </tr>
    <?php while ($row = $result_week->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['week']; ?></td>
        <td><?php echo $row['item_count']; ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Add Chart.js for trends -->
<canvas id="trendChart" width="400" height="200"></canvas>
<script>
var ctx = document.getElementById('trendChart').getContext('2d');
var trendChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [/* Add labels (dates or weeks) from PHP */],
        datasets: [{
            label: 'Number of Items',
            data: [/* Add data points (item count) from PHP */],
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    }
});
</script>


<script>
var ctx = document.getElementById('trendChart').getContext('2d');
var trendChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            <?php
            // Labels for the chart (dates or weeks)
            foreach($result_day as $row) {
                echo "'" . $row['date'] . "',";
            }
            ?>
        ],
        datasets: [{
            label: 'Number of Items',
            data: [
                <?php
                // Data points for the chart
                foreach($result_day as $row) {
                    echo $row['item_count'] . ",";
                }
                ?>
            ],
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    }
});
</script>


</body>
</html>


