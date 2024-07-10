<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Climate Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php
$conn = mysqli_connect("localhost", "root", "", "midsem");
$results = mysqli_query($conn, "SELECT * FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 100");

$id = [];
$timestamp = [];
$temperatureData = [];
$humidityData = [];
$soilMoistureData = [];
$lightData = [];

while ($row = mysqli_fetch_array($results)) {
    $id[] = $row['id'];
    $timestamp[] = $row['timestampp'];
    $temperatureData[] = $row['sensor1'];
    $humidityData[] = $row['sensor2'];
    $soilMoistureData[] = $row['sensor3'];
    $lightData[] = $row['sensor4'];
}

$idJson = json_encode($id);
$timestampJson = json_encode($timestamp);
$temperatureDataJson = json_encode($temperatureData);
$humidityDataJson = json_encode($humidityData);
$soilMoistureDataJson = json_encode($soilMoistureData);
$lightDataJson = json_encode($lightData);
?>

<body style="background-color: #303030;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Plant Support</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="/finalproj2/dashboard.php">Home</a>
                <a class="nav-item nav-link active" href="/finalproj2/table.php">Details<span
                        class="sr-only">(current)</span></a>
            </div>
        </div>
    </nav>
    <div class="container" style="padding: 20px;">
        <div class="card" style="padding: 20px;">
            <h1 class="text-center my-4">Climate Data</h1>

            <div class="datatable-container">
                <table id="datatablesSimple" class="table table-stiped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Timestamp</th>
                            <th>Temperature (°C)</th>
                            <th>Humidity (%)</th>
                            <th>Tank Volume (l)</th>
                            <th>Light Level (x/10)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <script type="text/javascript">
                            var id = <?php echo $idJson; ?>;
                            var timestamp = <?php echo $timestampJson; ?>;
                            var temperatureData = <?php echo $temperatureDataJson; ?>;
                            var humidityData = <?php echo $humidityDataJson; ?>;
                            var soilMoistureData = <?php echo $soilMoistureDataJson; ?>;
                            var lightData = <?php echo $lightDataJson; ?>

                            var tableBody = document.querySelector('tbody');

                            for (var i = 0; i < id.length; i++) {
                                var row = document.createElement('tr');

                                var cellId = document.createElement('td');
                                cellId.textContent = id[i];
                                row.appendChild(cellId);

                                var cellTimestamp = document.createElement('td');
                                cellTimestamp.textContent = timestamp[i];
                                row.appendChild(cellTimestamp);

                                var cellTemperature = document.createElement('td');
                                cellTemperature.textContent = temperatureData[i];
                                row.appendChild(cellTemperature);

                                var cellHumidity = document.createElement('td');
                                cellHumidity.textContent = humidityData[i];
                                row.appendChild(cellHumidity);

                                var cellSoilMoisture = document.createElement('td');
                                cellSoilMoisture.textContent = soilMoistureData[i];
                                row.appendChild(cellSoilMoisture);

                                var cellLight = document.createElement('td');
                                cellLight.textContent = lightData[i];
                                row.appendChild(cellLight);

                                tableBody.appendChild(row);
                            }
                        </script>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    </script>
</body>

</html>