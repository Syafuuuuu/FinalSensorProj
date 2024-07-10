<?php
/*************************************************************************************************
 *  Originally Created By: Tauseef Ahmad
 *  Originally Created On: 3 April, 2023
 * 
 *  YouTube Video: https://youtu.be/VEN5kgjEuh8
 *  My Channel: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
 * 
 *  Adapted by: Muhammad Syafiq
 *  Adapted on: 11 July 2024
 *  
 ***********************************************************************************************/

// Database connection parameters
$hostname = "localhost";
$username = "root";
$password = "";
$database = "midsem";

// Establish a new database connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful, if not, end the script
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Database connection is OK<br>";

echo "Attempting to send value";

// Check if all sensor values are set in the POST request
if (isset($_POST["sensor1"]) && isset($_POST["sensor2"]) && isset($_POST["sensor3"]) && isset($_POST["sensor4"])) {
    // Retrieve sensor values from the POST request
    $s1 = $_POST["sensor1"];
    $s2 = $_POST["sensor2"];
    $s3 = $_POST["sensor3"];
    $s4 = $_POST["sensor4"];


    // Prepare an SQL query to insert sensor values into the 'sensor' table
    $sql = "INSERT INTO sensor (sensor1, sensor2, sensor3, sensor4) VALUES (" . $s1 . "," . $s2 . "," . $s3 . "," . $s4 . ")";

    // Execute the SQL query and check if it was successful
    if ($conn->query($sql) === TRUE) {
        echo "Values inserted in MySQL database table.";
        echo "Received sensor values: s1=$s1, s2=$s2, s3=$s3, s4=$s4";
    } else {
        // If the query execution failed, print the error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>