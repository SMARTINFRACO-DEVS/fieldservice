<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_day = "SELECT DATE(date) as date, COUNT(*) as item_count FROM tb_images GROUP BY DATE(date)";
$result_day = $conn->query($sql_day);
if (!$result_day) {
    die("Query Failed (Day): " . $conn->error);
}

$sql_week = "SELECT YEARWEEK(date, 1) as week, COUNT(*) as item_count FROM tb_images GROUP BY YEARWEEK(date, 1)";
$result_week = $conn->query($sql_week);
if (!$result_week) {
    die("Query Failed (Week): " . $conn->error);
}

$sql_name = "SELECT creator, COUNT(*) as count FROM tb_images GROUP BY creator";
$result_name = $conn->query($sql_name);
if (!$result_name) {
    die("Query Failed (Creator): " . $conn->error);
}

$dataPoints_day = array();
while ($row = $result_day->fetch_assoc()) {
    $dataPoints_day[] = array("label" => $row['date'], "y" => $row['item_count']);
}

$dataPoints_week = array();
while ($row = $result_week->fetch_assoc()) {
    $dataPoints_week[] = array("label" => "Week " . $row['week'], "y" => $row['item_count']);
}

$dataPoints_name = array();
while ($row = $result_name->fetch_assoc()) {
    $dataPoints_name[] = array("label" => $row['creator'], "y" => $row['count']);
}

$conn->close();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Records</title>
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <style>
        .chart-container {
            display: inline-block;
            width: 48%;
            margin-top: 35px;
            margin-right: 1%;
        }
        .fullwidthchart {
            display: block;
            width: 100%;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        body {
            background-color: #111827;
            color: #f9fafb;
        }
        nav {
            background-color: rgba(75, 85, 99, 0.1);
            backdrop-blur: 10px;
            border-bottom: 1px solid #4b5563;
            padding-top: 10px;
            padding-bottom: 10px;
            transition: all 0.3s ease-in-out;
        }
    </style>
    <script>
    window.onload = function () {
        var chartDay = new CanvasJS.Chart("chartContainerDay", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light2",
            title: { text: "Count by Day" },
            axisY: { title: "Number of Items", includeZero: true },
            axisX: { title: "Date", interval: 1, labelAngle: -45 },
            data: [{
                type: "spline",
                indexLabel: "{y}",
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                dataPoints: <?php echo json_encode($dataPoints_day, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartDay.render();

        var chartWeek = new CanvasJS.Chart("chartContainerWeek", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light2",
            title: { text: "Count by Week" },
            axisY: { title: "Number of Items", includeZero: true },
            axisX: { title: "Week", interval: 1, labelAngle: -45 },
            data: [{
                type: "column",
                indexLabel: "{y}",
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                dataPoints: <?php echo json_encode($dataPoints_week, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartWeek.render();

        var chartName = new CanvasJS.Chart("chartContainerName", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1",
            title: { text: "Count by Team Member" },
            axisY: { title: "Number of Items", includeZero: true },
            axisX: { title: "Name", interval: 1, labelAngle: -45 },
            data: [{
                type: "column",
                indexLabel: "{y}",
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                dataPoints: <?php echo json_encode($dataPoints_name, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartName.render();
    };
    </script>
</head>
<body>

<nav class="fixed top-0 left-0 right-0 z-50 h-[60px]">
    <ul class="mt-[-5px]">
        <li style="float: left;"><a href="home.php">View Records</a></li>
        <li style="float: left;"><a href="upload.php">Create Record</a></li>
        <li style="float: left;"><a href="draftpage.php">Draft Records</a></li>
        <li style="float: left;"><a href="records.php">Deleted Records</a></li>
        <li style="float: right;">
            <a href="logout.php">Logout <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
        </li>
    </ul>
</nav>

<div class="overflow-x-auto mt-[5%] mx-[20%]">
    <h2>Items Count by Day</h2>
    <div class="chart-container">
        <div id="chartContainerDay" style="height: 370px;"></div>
    </div>

    <div class="chart-container">
        <div id="chartContainerWeek" style="height: 370px;"></div>
    </div>

    <div class="fullwidthchart">
        <div id="chartContainerName" style="height: 300px;"></div>
    </div>
</div>

</body>
</html>
