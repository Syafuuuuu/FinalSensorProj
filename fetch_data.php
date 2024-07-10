<?php
// Establish a new database connection
$conn = mysqli_connect("localhost", "root", "", "midsem");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest 60 data points from the database for each sensor
$s1result = mysqli_query($conn, "SELECT sensor1 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
$s2result = mysqli_query($conn, "SELECT sensor2 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
$s3result = mysqli_query($conn, "SELECT sensor3 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
$s4result = mysqli_query($conn, "SELECT sensor4 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");

// Fetch the current values for each sensor
$curr1result = mysqli_query($conn, "SELECT sensor1 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 1");
$curr2result = mysqli_query($conn, "SELECT sensor2 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 1");
$curr3result = mysqli_query($conn, "SELECT sensor3 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 1");
$curr4result = mysqli_query($conn, "SELECT sensor4 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 1");

// Initialize arrays to store the sensor data
$temperatureData = [];
$humidityData = [];
$soilMoistureData = [];
$lightData = [];

// Fetch the temperature data from the database and store it in the temperatureData array
while ($row = mysqli_fetch_assoc($s1result)) {
    $temperatureData[] = $row['sensor1'];
}

// Fetch the humidity data from the database and store it in the humidityData array
while ($row = mysqli_fetch_assoc($s2result)) {
    $humidityData[] = $row['sensor2'];
}

// Fetch the soil moisture data from the database and store it in the soilMoistureData array
while ($row = mysqli_fetch_assoc($s3result)) {
    $soilMoistureData[] = $row['sensor3'];
}

// Fetch the light data from the database and store it in the lightData array
while ($row = mysqli_fetch_assoc($s4result)) {
    $lightData[] = $row['sensor4'];
}

// Calculate the averages for the first 10 data points
$totalTemp = array_sum(array_slice($temperatureData, 0, 10)) / 10;
$totalHum = array_sum(array_slice($humidityData, 0, 10)) / 10;
$totalSoil = array_sum(array_slice($soilMoistureData, 0, 10)) / 10;
$totalLight = array_sum(array_slice($lightData, 0, 10)) / 10;

// Fetch the current values for each sensor
$currTemp = mysqli_fetch_assoc($curr1result)['sensor1'];
$currHum = mysqli_fetch_assoc($curr2result)['sensor2'];
$currSoil = mysqli_fetch_assoc($curr3result)['sensor3'];
$currLight = mysqli_fetch_assoc($curr4result)['sensor4'];

// Set the content type of the response to application/json
header('Content-Type: application/json');

// Encode the sensor data arrays as a JSON object and echo it as the response
echo json_encode([
    'temperatureData' => $temperatureData,
    'humidityData' => $humidityData,
    'soilMoistureData' => $soilMoistureData,
    'lightData' => $lightData,
    'currtemperatureData' => $currTemp,
    'currhumidityData' => $currHum,
    'currsoilMoistureData' => $currSoil,
    'currlightData' => $currLight,
    'avgTemp' => $totalTemp,
    'avgHum' => $totalHum,
    'avgSoil' => $totalSoil,
    'avgLight' => $totalLight
]);
?>
