<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Climate Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color: #303030;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Plant Support</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="/finalproj2/table.php">Details</a>
                <a class="nav-item nav-link" href="#">Credits</a>
            </div>
        </div>
    </nav>
    <div class="container text-center" style="padding: 30px;">
        <div class="card" style="padding: 20px;">
            <h1 class="text-center my-3">Climate Data</h1>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card bg-dark text-white h-70">
                        <div class="card-header">Temperature</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <h4>Current:</h4>
                                    <h1 class="text-lg fw-bold" id="currtemperature"></h1>
                                    <br>
                                    <h4>Average:</h4>
                                    <h3 class="text-lg fw-bold" id="avgtemperature"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tempMsg" class="card text-white text-center h-20" style="padding: 10px;">
                        <h3></h3>
                        <div class="card-footer">
                            <h4></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-dark text-white h-70">
                        <div class="card-header">Humidity</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <h4>Current:</h4>
                                    <h1 class="text-lg fw-bold" id="currhumidity"></h1>
                                    <br>
                                    <h4>Average:</h4>
                                    <h3 class="text-lg fw-bold" id="avghumidity"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="humMsg" class="card text-white text-center h-20" style="padding: 10px;">
                        <h3></h3>
                        <div class="card-footer">
                            <h4></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-dark text-white h-70">
                        <div class="card-header">Water Tank Volume</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <h4>Current:</h4>
                                    <h1 class="text-lg fw-bold" id="currsoilMoisture"></h1>
                                    <br>
                                    <h4>Average:</h4>
                                    <h3 class="text-lg fw-bold" id="avgsoilMoisture"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="waterMsg" class="card text-white text-center h-20" style="padding: 10px;">
                        <h3></h3>
                        <div class="card-footer">
                            <h4></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-dark text-white h-70">
                        <div class="card-header">Light Level</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <h4>Current:</h4>
                                    <h1 class="text-lg fw-bold" id="currlight"></h1>
                                    <br>
                                    <h4>Average:</h4>
                                    <h3 class="text-lg fw-bold" id="avglight"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="lightMsg" class="card text-white text-center h-20" style="padding: 10px;">
                        <h3></h3>
                        <div class="card-footer">
                            <h4></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <br>
        <div class="card" style="padding: 20px;">
            <h1 class="text-center my-3">Graphs & Charts</h1>
            <div class="row">
                <div class="col-lg-6">
                    <canvas id="temperatureChart"></canvas>
                </div>
                <div class="col-lg-6">
                    <canvas id="humidityChart"></canvas>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-6">
                    <canvas id="soilMoistureChart"></canvas>
                </div>
                <div class="col-lg-6">
                    <canvas id="lightChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Do i need this php? -->
    <?php
    $conn = mysqli_connect("localhost", "root", "", "midsem");
    $s1result = mysqli_query($conn, "SELECT sensor1 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
    $s1currresult = mysqli_query($conn, "SELECT sensor1 FROM `sensor` ORDER BY `id` DESC LIMIT 1");

    $temperatureData = [];
    while ($row = mysqli_fetch_array($s1result)) {
        $temperatureData[] = $row['sensor1'];
    }

    $s2result = mysqli_query($conn, "SELECT sensor2 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
    $s2currresult = mysqli_query($conn, "SELECT sensor2 FROM `sensor` ORDER BY `id` DESC LIMIT 1");
    $humidityData = [];
    while ($row = mysqli_fetch_array($s2result)) {
        $humidityData[] = $row['sensor2'];
    }

    $s3result = mysqli_query($conn, "SELECT sensor3 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
    $s3currresult = mysqli_query($conn, "SELECT sensor3 FROM `sensor` ORDER BY `id` DESC LIMIT 1");
    $soilMoistureData = [];
    while ($row = mysqli_fetch_array($s3result)) {
        $soilMoistureData[] = $row['sensor3'];
    }

    $s4result = mysqli_query($conn, "SELECT sensor4 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
    $s4currresult = mysqli_query($conn, "SELECT sensor4 FROM `sensor` ORDER BY `id` DESC LIMIT 1");
    $lightData = [];
    while ($row = mysqli_fetch_array($s4result)) {
        $lightData[] = $row['sensor4'];
    }

    $temperatureDataJson = json_encode($temperatureData);
    $humidityDataJson = json_encode($humidityData);
    $soilMoistureDataJson = json_encode($soilMoistureData);
    $lightDataJson = json_encode($lightData);

    $currtempDataJson = json_encode(mysqli_fetch_assoc($s1currresult)['sensor1']);
    $currhumDataJson = json_encode(mysqli_fetch_assoc($s2currresult)['sensor2']);
    $currmoistDataJson = json_encode(mysqli_fetch_assoc($s3currresult)['sensor3']);
    $currlightDataJson = json_encode(mysqli_fetch_assoc($s4currresult)['sensor4']);
    ?>


    <script type="text/javascript">
        var temperatureData = <?php echo $temperatureDataJson; ?>;
        var humidityData = <?php echo $humidityDataJson; ?>;
        var soilMoistureData = <?php echo $soilMoistureDataJson; ?>;
        var lightData = <?php echo $lightDataJson; ?>;

        var currtemperatureData = <?php echo $currtempDataJson; ?>;
        var currhumidityData = <?php echo $currhumDataJson; ?>;
        var currsoilMoistureData = <?php echo $currmoistDataJson; ?>;
        var currlightData = <?php echo $currlightDataJson; ?>;

        var labels = Array.from({ length: 60 }, (_, i) => i + 1); // labels for 60 seconds

        var ctx1 = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperature',
                    data: temperatureData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx2 = document.getElementById('humidityChart').getContext('2d');
        var humidityChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Humidity',
                    data: humidityData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx3 = document.getElementById('soilMoistureChart').getContext('2d');
        var soilMoistureChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Water Level',
                    data: soilMoistureData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx4 = document.getElementById('lightChart').getContext('2d');
        var lightChart = new Chart(ctx4, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Light Level',
                    data: lightData,
                    backgroundColor: 'rgba(80, 152, 50, 0.2)',
                    borderColor: 'rgba(80, 152, 50, 1)',
                    borderWidth: 1
                }]
            }
        });

        function calculateAverage(data) {
            const total = data.reduce((sum, value) => sum + parseFloat(value), 0);
            return (total / data.length).toFixed(2);
        }

        function updateStatus(cardId, value, min, max, type) {
            var card = document.getElementById(cardId);
            var flag = false;
            if (value >= min && value <= max) {
                card.className = "card bg-success text-white text-center h-20";
                card.querySelector("h3").innerText = "Perfect";
                card.querySelector("h4").innerText = "No need to worry! ";

            } else {
                card.className = "card bg-warning text-white text-center h-20";
                card.querySelector("h3").innerText = "Caution";
                var prompt = "";
                if (value < min) {
                    switch (type) {
                        case 'temp':
                            prompt = "Increase the temperature!"; break;
                        case 'hum':
                            prompt = "Increase the humidity!"; break;
                        case 'water':
                            prompt = "Fill up the tank!"; break;
                        case 'light':
                            prompt = "Too Dark! Let in more sun!"; break;
                        default:
                            prompt = "Out of range! Danger! less than"; break;

                    }

                } else if (value > max) {
                    switch (type) {
                        case 'temp':
                            prompt = "Decrease the temperature!"; break;
                        case 'hum':
                            prompt = "Decrease the humidity"; break;
                        case 'water':
                            prompt = "Tank overflowing!"; break;
                        case 'light':
                            prompt = "Too Bright! Need more shade!"; break;
                        default:
                            prompt = "Out of range! Danger! more than"; break;

                    }

                }
                flag = true;
                card.querySelector("h4").innerText = prompt;
            }
            return flag;
        }

        document.getElementById('currtemperature').innerText = currtemperatureData + '°C';
        document.getElementById('currhumidity').innerText = currhumidityData + '%';
        document.getElementById('currsoilMoisture').innerText = currsoilMoistureData + 'l';
        document.getElementById('currlight').innerText = currlightData + '/10';

        document.getElementById('avgtemperature').innerText = calculateAverage(temperatureData) + '°C';
        document.getElementById('avghumidity').innerText = calculateAverage(humidityData) + '%';
        document.getElementById('avgsoilMoisture').innerText = calculateAverage(soilMoistureData) + 'l';
        document.getElementById('avglight').innerText = calculateAverage(lightData) + '/10';

        updateStatus("tempMsg", currtemperatureData, 21, 37, 'temp');
        updateStatus("humMsg", currhumidityData, 60, 80, 'hum');
        updateStatus("waterMsg", currsoilMoistureData, 80, 150, 'water');
        updateStatus("lightMsg", currlightData, 5, 10, 'light');

        // Set an interval to fetch new data from the server every 5 seconds
        setInterval(function () {
            fetch('fetch_data.php') // Fetch data from the server
                .then(response => response.json()) // Parse the response as JSON
                .then(data => {
                    // Update the chart data with the new data from the server
                    temperatureChart.data.datasets[0].data = data.temperatureData;
                    humidityChart.data.datasets[0].data = data.humidityData;
                    soilMoistureChart.data.datasets[0].data = data.soilMoistureData;
                    lightChart.data.datasets[0].data = data.lightData;

                    //Update the current values
                    document.getElementById('currtemperature').innerText = data.currtemperatureData + '°C';
                    document.getElementById('currhumidity').innerText = data.currhumidityData + '%';
                    document.getElementById('currsoilMoisture').innerText = data.currsoilMoistureData + 'l';
                    document.getElementById('currlight').innerText = data.currlightData + '/10';

                    // Update the averages
                    document.getElementById('avgtemperature').innerText = calculateAverage(data.temperatureData) + '°C';
                    document.getElementById('avghumidity').innerText = calculateAverage(data.humidityData) + '%';
                    document.getElementById('avgsoilMoisture').innerText = calculateAverage(data.soilMoistureData) + 'l';
                    document.getElementById('avglight').innerText = calculateAverage(data.lightData) + '/10';

                    // Update the status cards
                    flagTemp = updateStatus("tempMsg", data.currtemperatureData, 21, 37, 'temp');
                    flagHum = updateStatus("humMsg", data.currhumidityData, 60, 80, 'hum');
                    flagWater = updateStatus("waterMsg", data.currsoilMoistureData, 80, 150, 'water');
                    flagLight = updateStatus("lightMsg", data.currlightData, 5, 10, 'light');

                    if (flagTemp || flagHum || flagWater || flagLight) {
                        alert("Caution! Check on your plants!");
                    }

                    // Update the charts to reflect the new data
                    temperatureChart.update();
                    humidityChart.update();
                    soilMoistureChart.update();
                    lightChart.update();
                });
        }, 5000); // Fetch new data every 5 seconds

    </script>
</body>

</html>