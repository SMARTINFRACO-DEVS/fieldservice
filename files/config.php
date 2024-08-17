<?php

$host = '10.247.5.180';
$username = 'mstracker_admin';
$password = 'AuZ2ZrOfmSTltz4ZdM57AACfhYSRPSsVBV16ACztCJjYFPiDm5zFcuejDVP1DaJs';
$database = 'mydatabase';
$port = 5432; // Make sure this is an integer, not a string

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>



<?php
$host = '10.247.5.180';
$username = 'mstracker_admin';
$password = 'AuZ2ZrOfmSTltz4ZdM57AACfhYSRPSsVBV16ACztCJjYFPiDm5zFcuejDVP1DaJs';
$database = 'mydatabase';
$port = 5432;
// Create connection
 $conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
