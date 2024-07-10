/*************************************************************************************************
 *  Originaly Created By: Tauseef Ahmad
 *  Originialy Created On: 3 April, 2023
 *
 *  Adapted By: Muhammad Syafiq
 *  Adapted On: 11 July 2024
 *  
 *  Code was adapted from:
 *
 *  Ahmed Logs - For database connection
 *  - YouTube Video: https://youtu.be/VEN5kgjEuh8
 *  - Youtube Channel: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
 *
 *  INOVATRIX - For Soil Moisture Sensor
 *  - Github Repo: https://github.com/INOVATRIX/ESP8266-SOIL-MOISTURE-SM/tree/main
 * 
 *  Co-Pilot 
 *  - To Generate the graph code and reactive website
 *  
 ***********************************************************************************************/

// Include the DHT sensor library
#include "DHT.h"

// Define the digital pin connected to the DHT sensor and the sensor type
#define DHTPIN 4       // D2 pin on the ESP8266
#define DHTTYPE DHT22  // DHT22 (AM2302) type
DHT dht(DHTPIN, DHTTYPE);

// Include the ESP8266 WiFi and HTTP client libraries
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

//define sound velocity in cm/uS
const int trigPin = 12;  // Trigger pin connected to the Ultrasonic sensor at D6
const int echoPin = 14;  // Echo pin connected to the Ultrasonic sensor at D5
#define SOUND_VELOCITY 0.034
#define CM_TO_INCH 0.393701

//define Ultrasonic related variables
long duration;
float distanceCm;

//Define fan and buzzer
const int fanPin = 13;
const int buzzPin = 15;

// URL for sending sensor data to the server
String URL = "http://192.168.145.163/finalproj2/test_data.php";

// WiFi credentials
const char* ssid = "V2027";
const char* password = "password";
const int ledPin = 5;

// Variables to store sensor readings
int s1, s2, sensorValue, validCount;
float s3, s4, dist;

//Variables for sensor logic
bool flagS1, flagS2, flagS3, flagS4;


// Setup function runs once at startup
void setup() {
  Serial.begin(115200);      // Start serial communication at 115200 baud rate
  connectWiFi();             // Connect to WiFi network
  dht.begin();               // Initialize the DHT sensor
  pinMode(trigPin, OUTPUT);  // Sets the trigPin as an Output
  pinMode(echoPin, INPUT);   // Sets the echoPin as an Input
  pinMode(fanPin, OUTPUT);   // Sets the fanPin as Output
  pinMode(buzzPin, OUTPUT);  // Sets the buzzPin as Output
  digitalWrite(fanPin, LOW);
  digitalWrite(buzzPin, LOW);
  validCount = 0;
}

// Loop function runs repeatedly
void loop() {
  // Reconnect to WiFi if connection is lost
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  // Read temperature and humidity from DHT sensor
  s1 = dht.readTemperature();
  s2 = dht.readHumidity();

  // Read distance
  //Initialise trigger pin to low at the start
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  // Sets the trigPin on HIGH state for 10 micro seconds
  digitalWrite(trigPin, HIGH);  //Shoot out signal
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);  //Stops signal

  // Reads the echoPin, returns the sound wave travel time in microseconds
  duration = pulseIn(echoPin, HIGH);

  // Calculate the distance
  dist = (duration * SOUND_VELOCITY / 2) / 100;

  if (dist > 0.15) {
    dist = 0.15;
  }

  Serial.println("Distance in m: ");
  Serial.println(dist);

  // Calculate volume of resevoir (assume resevoir at full is 0.15m tall, and lets say 1m2 in base area)
  s3 = (0.15 - dist) * 1000;  //Max should be 0.15m3 or 0.15l or 150l

  Serial.println("Volume in Litre:");
  Serial.println(s3);

  // Prints the distance on the Serial Monitor
  Serial.print("Distance (cm): ");
  Serial.println(distanceCm);

  sensorValue = analogRead(A0);       // read the input on analog pin 0
  s4 = (1024 - sensorValue) / 102.4;  // Convert the analog reading (which goes from 0 - 1023) to a voltage (0 - 5V)

  if (validCount > 5) {
    //Alert
    alertFunc();

    // Prepare data for HTTP POST request
    String postData = "sensor1=" + String(s1) + "&sensor2=" + String(s2) + "&sensor3=" + String(s3) + "&sensor4=" + String(s4);

    // Create HTTP and WiFi client objects
    HTTPClient http;
    WiFiClient wclient;

    // Begin HTTP connection to the server
    http.begin(wclient, URL);
    // Set the content type for the POST request
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Send the POST request and get the response code
    int httpCode = http.POST(postData);
    // Get the response payload from the server
    String payload = http.getString();

    // Check if the HTTP request was successful
    if (httpCode > 0) {
      if (httpCode == HTTP_CODE_OK) {
        // Print the server's response payload
        Serial.println(payload);
      } else {
        // Print the HTTP response code
        Serial.printf("[HTTP] GET... code: %d\n", httpCode);
      }
    } else {
      // Print the HTTP error
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    // Close the HTTP connection
    http.end();

    // Print debugging information
    Serial.print("URL : ");
    Serial.println(URL);
    Serial.print("Data: ");
    Serial.println(postData);
    Serial.print("httpCode: ");
    Serial.println(httpCode);
    Serial.print("payload : ");
    Serial.println(payload);
    Serial.println("--------------------------------------------------");
    // Wait for 5 seconds before repeating the loop
    delay(5000);
  } else {
    Serial.println("...Calibrating...");
    validCount++;
    delay(5000);
  }
}

// Function to connect to the WiFi network
void connectWiFi() {
  // Turn off WiFi to reset any previous configurations
  WiFi.mode(WIFI_OFF);
  delay(1000);
  // Set WiFi to station mode and connect to the network
  WiFi.mode(WIFI_STA);

  // Begin connection process
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");

  // Wait until connection is established
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  // Print connection details
  Serial.print("connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

bool checkFlag(float min, float max, float inp) {

  if (inp < min) {
    Serial.println("Lower Bounds");
    return true;
  } else if (inp > max) {
    Serial.println("Upper Bounds");
    return true;
  } else {
    Serial.println("OK!");
    return false;
  }
}

void alertFunc() {

  float tempMin = 21;
  float tempMax = 37;
  float humMin = 60;
  float humMax = 80;
  float volMin = 80;
  float volMax = 150;
  float lightMin = 5;
  float lightMax = 10;

  flagS1 = checkFlag(tempMin, tempMax, s1);
  flagS2 = checkFlag(humMin, humMax, s2);
  flagS3 = checkFlag(volMin, volMax, s3);
  flagS4 = checkFlag(lightMin, lightMax, s4);

  if (flagS1 || flagS2 || flagS3 || flagS4) {
    digitalWrite(buzzPin, HIGH);
  } else {
    Serial.println("ALL OK!");
    digitalWrite(buzzPin, LOW);
  }

  if (flagS1) {
    Serial.println("Too Hot");
    if (s1 > tempMax) {
      digitalWrite(fanPin, HIGH);
      Serial.println("FAN ON");
    } else {
      digitalWrite(fanPin, LOW);
      Serial.println("FAN OFF");
    }
  }
}
