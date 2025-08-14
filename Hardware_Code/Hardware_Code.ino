#include <HX711_ADC.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>  // Marco Schwartz library recommended
#include <TinyGPS++.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>  // For JSON parsing

// WiFi credentials
const char* ssid = "..........";
const char* password = ".........";

// Server URL (replace with your local server PHP script)
const char* serverURL = "http://............./esp32/add_newborn.php";

// HX711 pins
#define HX711_DOUT 19
#define HX711_SCK  18

// Button pins
const int buttonPins[8] = {
  14, // Button 1
  27, // Button 2
  26, // Button 3
  25, // Button 4
  12, // Button 5
  32, // Button 6
  4,  // Button 7 (was 34)
  13  // Button 8 (was 35)
};

// Debounce delay
const unsigned long debounceDelay = 50;

// GPS pins
#define GPS_RX 16
#define GPS_TX 17

// Hardware instances
HX711_ADC LoadCell(HX711_DOUT, HX711_SCK);
LiquidCrystal_I2C lcd(0x27, 16, 2);
TinyGPSPlus gps;
HardwareSerial SerialGPS(2);

// Button states for debounce
bool buttonState[8];
bool lastButtonState[8];
unsigned long lastDebounceTime[8];

// Data variables
float weight = 0.0;
int gestation = 0;
String gender = "";
double latitude = 0.0;
double longitude = 0.0;

// Gestation labels to show on LCD
const char* gestationLabels[4] = {"7", "8", "9", "10"};

// Forward declarations
void sendData();
String sendDataToServer(String jsonPayload);
void connectWiFi();
void handleButtonPress(int buttonNum);
String reverseGeocode(double lat, double lon);

void setup() {
  Serial.begin(115200);
  SerialGPS.begin(9600, SERIAL_8N1, GPS_RX, GPS_TX);

  for (int i = 0; i < 8; i++) {
    pinMode(buttonPins[i], INPUT_PULLUP);
    buttonState[i] = HIGH;
    lastButtonState[i] = HIGH;
    lastDebounceTime[i] = 0;
  }

  LoadCell.begin();
  LoadCell.start(1000);
  LoadCell.setCalFactor(98.0);  // Your calibrated factor

  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Starting...");
  delay(2000);
  lcd.clear();

  connectWiFi();
}

void loop() {
  // Read GPS data
  while (SerialGPS.available() > 0) {
    gps.encode(SerialGPS.read());
  }
  if (gps.location.isUpdated()) {
    latitude = gps.location.lat();
    longitude = gps.location.lng();
  }

  // Update weight reading
  LoadCell.update();
  weight = LoadCell.getData();
  if (weight < 0) weight = 0;

  // Read buttons with debounce
  for (int i = 0; i < 8; i++) {
    int reading = digitalRead(buttonPins[i]);
    if (reading != lastButtonState[i]) {
      lastDebounceTime[i] = millis();
    }
    if ((millis() - lastDebounceTime[i]) > debounceDelay) {
      if (reading != buttonState[i]) {
        buttonState[i] = reading;
        if (buttonState[i] == LOW) {
          handleButtonPress(i + 1);
        }
      }
    }
    lastButtonState[i] = reading;
  }

  // Display weight on top line
  lcd.setCursor(0, 0);
  lcd.print("Wt:            ");
  if (weight < 1000) {
    lcd.setCursor(4, 0);
    lcd.print(weight, 0);
    lcd.print(" g");
  } else {
    float weightKg = weight / 1000.0;
    lcd.setCursor(4, 0);
    lcd.print(weightKg, 2);
    lcd.print(" kg");
  }

  // Display gestation and gender on bottom line
  lcd.setCursor(0, 1);
  lcd.print("G:");
  if (gestation >= 1 && gestation <= 4) {
    lcd.print(gestationLabels[gestation - 1]);
  } else {
    lcd.print("--");
  }
  lcd.print(" ");
  if (gender != "") {
    lcd.print(gender);
  } else {
    lcd.print("--");
  }
  lcd.print("    ");
}

void handleButtonPress(int buttonNum) {
  switch (buttonNum) {
    case 1:
    case 2:
    case 3:
    case 4:
      gestation = buttonNum;
      Serial.print("Gestation set to: ");
      Serial.println(gestationLabels[gestation - 1]);
      break;

    case 5:
      gender = "Male";
      Serial.println("Gender set to Male");
      break;

    case 6:
      gender = "Female";
      Serial.println("Gender set to Female");
      break;

    case 7: // Reset
      gestation = 0;
      gender = "";
      LoadCell.tare();
      Serial.println("Reset pressed: cleared gender and gestation, tared scale");
      break;

    case 8: // Send
      sendData();
      break;

    default:
      break;
  }
}

void connectWiFi() {
  if (WiFi.status() == WL_CONNECTED) return;

  Serial.print("Connecting to WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println(" connected");
}

String reverseGeocode(double lat, double lon) {
  if (lat == 0.0 && lon == 0.0) return "Unknown";

  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  HTTPClient http;
  String url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" + String(lat,6) + "&lon=" + String(lon,6) + "&zoom=10&addressdetails=1";

  http.begin(url);
  http.addHeader("User-Agent", "ESP32-Newborn-Project");  // Required by Nominatim usage policy
  int httpCode = http.GET();

  String locationInfo = "Unknown";

  if (httpCode == 200) {
    String payload = http.getString();

    StaticJsonDocument<1024> doc;
    DeserializationError error = deserializeJson(doc, payload);
    if (!error) {
      JsonObject address = doc["address"];

      const char* county = address["county"] | "N/A";
      const char* city = address["city"] | "N/A";
      const char* state = address["state"] | "N/A";

      locationInfo = String(county) + ", " + String(city) + ", " + String(state);
    } else {
      locationInfo = "Parse error";
    }
  } else {
    locationInfo = "HTTP error " + String(httpCode);
  }
  http.end();
  return locationInfo;
}

String sendDataToServer(String jsonPayload) {
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  HTTPClient http;
  http.begin(serverURL);
  http.addHeader("Content-Type", "application/json");
  int httpResponseCode = http.POST(jsonPayload);

  String response = "";
  if (httpResponseCode > 0) {
    response = http.getString();
    Serial.print("Server response: ");
    Serial.println(response);
  } else {
    Serial.print("Error sending POST: ");
    Serial.println(httpResponseCode);
  }
  http.end();
  return response;
}

void sendData() {
  if (weight <= 0 || gestation == 0 || gender == "") {
    Serial.println("Incomplete data! Cannot send.");
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Incomplete data");
    delay(1500);
    lcd.clear();
    return;
  }

  String location = reverseGeocode(latitude, longitude);

  String jsonPayload = "{";
  jsonPayload += "\"weight\":";
  jsonPayload += weight;
  jsonPayload += ",\"gestation\":\"";
  jsonPayload += gestationLabels[gestation - 1];
  jsonPayload += "\",\"gender\":\"";
  jsonPayload += gender;
  jsonPayload += "\",\"location\":\"";
  jsonPayload += location;
  jsonPayload += "\"}";

  Serial.print("Sending data: ");
  Serial.println(jsonPayload);

  String response = sendDataToServer(jsonPayload);

  // Parse reg_number from JSON response (if your server returns it)
  int start = response.indexOf("reg_number");
  if (start != -1) {
    int colon = response.indexOf(":", start);
    int quote1 = response.indexOf("\"", colon);
    int quote2 = response.indexOf("\"", quote1 + 1);
    String regNumber = response.substring(quote1 + 1, quote2);

    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("REG Number");
    lcd.setCursor(0,1);
    lcd.print(regNumber);
    delay(5000);
    lcd.clear();
  } else {
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Send failed");
    delay(3000);
    lcd.clear();
  }
}

