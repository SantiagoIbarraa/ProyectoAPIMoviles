/*
 * Configuración del sensor MAX30100 para el proyecto de reproductor inteligente
 * Este código debe ser cargado en el Arduino con el sensor MAX30100
 * 
 * Hardware requerido:
 * - Arduino ESP32 o ESP8266
 * - Sensor MAX30100
 * - Conexión WiFi
 * 
 * Conexiones del sensor MAX30100:
 * - VCC -> 3.3V
 * - GND -> GND
 * - SDA -> GPIO 21 (ESP32) o GPIO 4 (ESP8266)
 * - SCL -> GPIO 22 (ESP32) o GPIO 5 (ESP8266)
 */

#include <WiFi.h>
#include <WebServer.h>
#include <ArduinoJson.h>
#include <Wire.h>
#include "MAX30100_PulseOximeter.h"

// ========================================
// CONFIGURACIÓN - MODIFICA ESTOS VALORES
// ========================================
const char* ssid = "Fibertel WiFi843 2.4GHz";       // Cambia por tu SSID
const char* password = "1020304050";                 // Cambia por tu contraseña
const char* serverIP = "192.168.1.100";             // IP de tu servidor web
const int serverPort = 80;                          // Puerto del servidor (80 para HTTP)
const char* endpoint = "/sensor_data.php";          // Endpoint para enviar datos

// ========================================
// CONFIGURACIÓN DEL SENSOR
// ========================================
PulseOximeter pox;
uint32_t tsLastReport = 0;
const uint32_t REPORTING_PERIOD_MS = 1000; // Enviar datos cada 1 segundo

// Variables para datos del sensor
float heartRate = 0.0;
float spO2 = 0.0;

// Variables de control
bool wifiConnected = false;
bool sensorInitialized = false;
unsigned long lastDataSent = 0;
int failedAttempts = 0;
const int maxFailedAttempts = 5;

// ========================================
// FUNCIONES DE CALLBACK
// ========================================
void onBeatDetected() {
    Serial.println("¡Latido detectado!");
}

// ========================================
// CONFIGURACIÓN INICIAL
// ========================================
void setup() {
    Serial.begin(115200);
    Serial.println("=== INICIANDO SENSOR MAX30100 ===");
    
    // Inicializar I2C
    Wire.begin();
    
    // Inicializar sensor
    Serial.print("Inicializando sensor MAX30100...");
    if (!pox.begin()) {
        Serial.println("FALLO");
        Serial.println("Verifica las conexiones del sensor:");
        Serial.println("- VCC -> 3.3V");
        Serial.println("- GND -> GND");
        Serial.println("- SDA -> GPIO 21");
        Serial.println("- SCL -> GPIO 22");
        while(1);
    } else {
        Serial.println("ÉXITO");
        sensorInitialized = true;
    }
    
    pox.setOnBeatDetectedCallback(onBeatDetected);
    
    // Conectar a WiFi
    connectToWiFi();
    
    Serial.println("=== SISTEMA LISTO ===");
    Serial.println("Coloca tu dedo en el sensor...");
    Serial.println("Los datos se enviarán automáticamente al servidor web.");
}

// ========================================
// BUCLE PRINCIPAL
// ========================================
void loop() {
    // Actualizar sensor
    pox.update();
    
    // Enviar datos cada segundo
    if (millis() - tsLastReport > REPORTING_PERIOD_MS) {
        heartRate = pox.getHeartRate();
        spO2 = pox.getSpO2();
        
        // Mostrar datos en consola
        Serial.print("Frecuencia cardíaca: ");
        Serial.print(heartRate);
        Serial.print(" BPM, SpO2: ");
        Serial.print(spO2);
        Serial.println("%");
        
        // Enviar datos al servidor si hay valores válidos
        if (heartRate > 0 && spO2 > 0) {
            sendDataToServer(heartRate, spO2);
        } else {
            Serial.println("Esperando datos válidos del sensor...");
        }
        
        tsLastReport = millis();
    }
    
    // Verificar conexión WiFi periódicamente
    if (millis() - lastDataSent > 30000) { // Cada 30 segundos
        if (WiFi.status() != WL_CONNECTED) {
            Serial.println("WiFi desconectado. Reintentando...");
            connectToWiFi();
        }
        lastDataSent = millis();
    }
}

// ========================================
// FUNCIONES DE CONEXIÓN
// ========================================
void connectToWiFi() {
    Serial.print("Conectando a WiFi: ");
    Serial.println(ssid);
    
    WiFi.begin(ssid, password);
    
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 20) {
        delay(1000);
        Serial.print(".");
        attempts++;
    }
    
    if (WiFi.status() == WL_CONNECTED) {
        wifiConnected = true;
        Serial.println();
        Serial.println("WiFi conectado exitosamente!");
        Serial.print("IP local: ");
        Serial.println(WiFi.localIP());
        Serial.print("Enviando datos a: http://");
        Serial.print(serverIP);
        Serial.println(endpoint);
    } else {
        wifiConnected = false;
        Serial.println();
        Serial.println("Error: No se pudo conectar a WiFi");
        Serial.println("Verifica las credenciales y la señal WiFi");
    }
}

// ========================================
// FUNCIÓN PARA ENVIAR DATOS AL SERVIDOR
// ========================================
void sendDataToServer(float hr, float spo2) {
    if (!wifiConnected) {
        Serial.println("WiFi no conectado. No se pueden enviar datos.");
        return;
    }
    
    // Crear cliente HTTP
    WiFiClient client;
    
    if (!client.connect(serverIP, serverPort)) {
        Serial.println("Error: No se pudo conectar al servidor");
        failedAttempts++;
        if (failedAttempts >= maxFailedAttempts) {
            Serial.println("Máximo número de intentos fallidos alcanzado.");
            Serial.println("Verifica la IP del servidor y la conexión de red.");
            failedAttempts = 0; // Reset para evitar bloqueo permanente
        }
        return;
    }
    
    // Crear JSON con los datos
    StaticJsonDocument<200> doc;
    doc["heartRate"] = hr;
    doc["spO2"] = spo2;
    doc["timestamp"] = millis();
    
    String jsonString;
    serializeJson(doc, jsonString);
    
    // Enviar petición HTTP POST
    client.print("POST ");
    client.print(endpoint);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(serverIP);
    client.println("Content-Type: application/json");
    client.print("Content-Length: ");
    client.println(jsonString.length());
    client.println("Connection: close");
    client.println();
    client.println(jsonString);
    
    // Leer respuesta
    unsigned long timeout = millis();
    while (client.available() == 0) {
        if (millis() - timeout > 5000) {
            Serial.println("Timeout: No se recibió respuesta del servidor");
            client.stop();
            return;
        }
    }
    
    // Mostrar respuesta
    while (client.available()) {
        String line = client.readStringUntil('\r');
        Serial.print("Respuesta del servidor: ");
        Serial.println(line);
    }
    
    client.stop();
    failedAttempts = 0; // Reset contador de fallos
    Serial.println("Datos enviados exitosamente al servidor");
}

// ========================================
// FUNCIONES DE UTILIDAD
// ========================================
void printSensorStatus() {
    Serial.println("=== ESTADO DEL SENSOR ===");
    Serial.print("Sensor inicializado: ");
    Serial.println(sensorInitialized ? "SÍ" : "NO");
    Serial.print("WiFi conectado: ");
    Serial.println(wifiConnected ? "SÍ" : "NO");
    if (wifiConnected) {
        Serial.print("IP: ");
        Serial.println(WiFi.localIP());
    }
    Serial.print("Última frecuencia cardíaca: ");
    Serial.print(heartRate);
    Serial.println(" BPM");
    Serial.print("Último SpO2: ");
    Serial.print(spO2);
    Serial.println("%");
    Serial.println("========================");
}
