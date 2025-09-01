#include <HX711_ADC.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <TinyGPS++.h>          // still included if you later add GPS
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>        // for parsing server JSON

// WiFi credentials
const char* ssid = "Jb";
const char* password = "17429146";

// Server URL
const char* serverURL = "http://192.168.0.104/esp32/add_newborn.php";

// HX711 pins
#define HX711_DOUT 19
#define HX711_SCK  18

// Buttons: 1-4 gestation, 5 male, 6 female, 7 reset, 8 send
const int buttonPins[8] = {14, 27, 26, 25, 12, 32, 4, 13};

// Debounce
const unsigned long debounceDelay = 50;

// (Optional) GPS pins (not used now but kept for later)
#define GPS_RX 16
#define GPS_TX 17

// Hardware
HX711_ADC LoadCell(HX711_DOUT, HX711_SCK);
LiquidCrystal_I2C lcd(0x27, 16, 2);
TinyGPSPlus gps;
HardwareSerial SerialGPS(2);

// Debounce state
bool buttonState[8];
bool lastButtonState[8];
unsigned long lastDebounceTime[8];

// Data
float weight = 0.0;
int gestation = 0;
String gender = "";

// Gestation labels shown on LCD: 7,8,9,10
const char* gestationLabels[4] = {"7", "8", "9", "10"};

// Forward decl
void sendData();
String sendDataToServer(const String& jsonPayload);
void connectWiFi();
void handleButtonPress(int buttonNum);
String getDummyLocation() { return "Bashundhara, Dhaka, Bangladesh"; }

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
  LoadCell.setCalFactor(98.0);  // your calibration

  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0,0); lcd.print("Starting...");
  delay(1500);
  lcd.clear();

  connectWiFi();
  lcd.setCursor(0,0); lcd.print("WiFi Connected");
  delay(1200);
  lcd.clear();
}

void loop() {
  // Update weight
  LoadCell.update();
  weight = LoadCell.getData();
  if (weight < 0) weight = 0;

  // Buttons (debounced)
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

  // LCD: weight on top
  lcd.setCursor(0, 0);
  lcd.print("Wt:            ");
  if (weight < 1000) {
    lcd.setCursor(4, 0);
    lcd.print(weight, 0); lcd.print(" g");
  } else {
    float kg = weight / 1000.0;
    lcd.setCursor(4, 0);
    lcd.print(kg, 2); lcd.print(" kg");
  }

  // LCD: gestation + gender on bottom
  lcd.setCursor(0, 1);
  lcd.print("G:");
  if (gestation >= 1 && gestation <= 4) lcd.print(gestationLabels[gestation - 1]);
  else lcd.print("--");
  lcd.print(" ");
  if (gender.length()) lcd.print(gender);
  else lcd.print("--");
  lcd.print("    ");
}

void handleButtonPress(int buttonNum) {
  switch (buttonNum) {
    case 1: case 2: case 3: case 4:
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
      Serial.println("Reset: cleared selections & tared");
      break;
    case 8: // Send
      sendData();
      break;
  }
}

void connectWiFi() {
  if (WiFi.status() == WL_CONNECTED) return;
  Serial.print("Connecting to WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(400);
    Serial.print(".");
  }
  Serial.println(" connected");
}

String sendDataToServer(const String& jsonPayload) {
  if (WiFi.status() != WL_CONNECTED) connectWiFi();

  HTTPClient http;
  http.begin(serverURL);
  http.addHeader("Content-Type", "application/json");
  int code = http.POST(jsonPayload);

  String resp = "";
  if (code > 0) {
    resp = http.getString();
    Serial.print("Server response: ");
    Serial.println(resp);
  } else {
    Serial.print("HTTP error: ");
    Serial.println(code);
  }
  http.end();
  return resp;
}

void sendData() {
  if (weight <= 0 || gestation == 0 || gender == "") {
    Serial.println("Incomplete data! Cannot send.");
    lcd.clear();
    lcd.setCursor(0,0); lcd.print("Incomplete data");
    delay(1500);
    lcd.clear();
    return;
  }

  String location = getDummyLocation();

  // Build JSON payload
  String json = "{";
  json += "\"weight\":" + String(weight, 2) + ",";
  json += "\"gestation\":\"" + String(gestationLabels[gestation - 1]) + "\",";
  json += "\"gender\":\"" + gender + "\",";
  json += "\"location\":\"" + location + "\"";
  json += "}";

  Serial.print("Sending: "); Serial.println(json);

  String resp = sendDataToServer(json);

  // Parse JSON: {"status":"success","reg_number":"12345678901234567"}
  StaticJsonDocument<256> doc;
  DeserializationError err = deserializeJson(doc, resp);

  lcd.clear();
  if (!err && doc["status"] == "success" && doc["reg_number"].is<const char*>()) {
    String reg = doc["reg_number"].as<String>();
    Serial.print("REG: "); Serial.println(reg);

    lcd.setCursor(0,0); lcd.print("REG Number");
    lcd.setCursor(0,1); lcd.print(reg);
    delay(30000);
  } else {
    lcd.setCursor(0,0); lcd.print("Send failed");
    delay(2000);
  }
  lcd.clear();
}

