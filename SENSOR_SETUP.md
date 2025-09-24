# Configuración del Sensor MAX30100

Este documento explica cómo integrar el sensor MAX30100 con el reproductor inteligente para reemplazar la simulación de PPM con datos reales de frecuencia cardíaca.

## Hardware Requerido

- **Arduino ESP32** o **ESP8266** (recomendado ESP32)
- **Sensor MAX30100** (pulsioxímetro)
- **Cables de conexión** (jumper wires)
- **Breadboard** (opcional)

## Conexiones del Sensor

### Para ESP32:
```
MAX30100    ESP32
VCC    ->   3.3V
GND    ->   GND
SDA    ->   GPIO 21
SCL    ->   GPIO 22
```

### Para ESP8266:
```
MAX30100    ESP8266
VCC    ->   3.3V
GND    ->   GND
SDA    ->   GPIO 4
SCL    ->   GPIO 5
```

## Instalación de Software

### 1. Instalar librerías de Arduino

Abre el Arduino IDE y instala las siguientes librerías:

1. **MAX30100lib** por OXullo Intersecans
   - Ve a `Herramientas` > `Administrar librerías`
   - Busca "MAX30100lib"
   - Instala la librería

2. **ArduinoJson** por Benoit Blanchon
   - Busca "ArduinoJson"
   - Instala la versión más reciente

### 2. Configurar el código Arduino

1. Abre el archivo `arduino_sensor_config.ino` en Arduino IDE
2. Modifica las siguientes variables según tu configuración:

```cpp
const char* ssid = "TU_WIFI_SSID";           // Nombre de tu red WiFi
const char* password = "TU_WIFI_PASSWORD";   // Contraseña de tu WiFi
const char* serverIP = "192.168.1.100";     // IP de tu servidor web
```

3. Sube el código a tu Arduino

### 3. Configurar la base de datos

1. Ejecuta el script SQL para crear la tabla del sensor:

```sql
-- Ejecutar en tu base de datos MySQL
source create_sensor_table.sql;
```

O ejecuta manualmente:

```sql
CREATE TABLE IF NOT EXISTS sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heart_rate DECIMAL(5,2) NOT NULL,
    spo2 DECIMAL(5,2) NOT NULL,
    timestamp BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_timestamp (timestamp)
);
```

### 4. Configurar el servidor web

1. Asegúrate de que el archivo `sensor_data.php` esté en la raíz de tu proyecto web
2. Verifica que la configuración de la base de datos en `sensor_data.php` sea correcta:

```php
$host = 'localhost';
$dbname = 'music_player';
$username = 'root';
$password = '';
```

## Uso del Sistema

### 1. Iniciar el sensor

1. Conecta el Arduino a la alimentación
2. Abre el monitor serie (115200 baudios) para ver el estado
3. Coloca tu dedo en el sensor MAX30100
4. El sensor comenzará a enviar datos automáticamente

### 2. Usar en el reproductor web

1. Abre el reproductor web en tu navegador
2. El sistema detectará automáticamente si el sensor está disponible
3. Si el sensor está conectado, verás el botón "Iniciar Sensor"
4. Si no está disponible, se usará el modo simulación manual

## Estados del Sistema

### Sensor Conectado
- ✅ Botón muestra "Iniciar Sensor"
- ✅ Datos reales de frecuencia cardíaca
- ✅ SpO2 en tiempo real
- ✅ Reproducción automática basada en datos reales

### Sensor No Disponible
- ⚠️ Botón muestra "Iniciar Simulación"
- ⚠️ Usa simulación manual de PPM
- ⚠️ Funcionalidad limitada pero operativa

## Solución de Problemas

### El sensor no se inicializa
- Verifica las conexiones I2C (SDA/SCL)
- Asegúrate de que el sensor esté alimentado con 3.3V
- Revisa que las librerías estén instaladas correctamente

### No se conecta a WiFi
- Verifica el SSID y contraseña
- Asegúrate de que la red WiFi esté en 2.4GHz
- Revisa la señal WiFi en la ubicación del Arduino

### No se envían datos al servidor
- Verifica la IP del servidor en el código Arduino
- Asegúrate de que el servidor web esté funcionando
- Revisa que el archivo `sensor_data.php` esté accesible
- Verifica la configuración de la base de datos

### Datos incorrectos del sensor
- Asegúrate de que el dedo esté bien colocado
- Espera unos segundos para que el sensor se estabilice
- Verifica que no haya luz ambiental interfiriendo

## Características Técnicas

### Rango de medición
- **Frecuencia cardíaca**: 30-300 BPM
- **SpO2**: 70-100%
- **Precisión**: ±2 BPM, ±2% SpO2

### Frecuencia de actualización
- **Sensor**: 100 Hz (internamente)
- **Envío de datos**: 1 Hz (cada segundo)
- **Actualización web**: 1 Hz

### Consumo de energía
- **ESP32**: ~80mA (activo), ~10mA (sleep)
- **MAX30100**: ~4.4mA (activo)
- **Total**: ~85mA (con sensor activo)

## Personalización

### Modificar frecuencia de envío
Cambia el valor en el código Arduino:

```cpp
const uint32_t REPORTING_PERIOD_MS = 2000; // Cambiar a 2 segundos
```

### Ajustar rango de PPM
Modifica en `js/sensor-integration.js`:

```javascript
// Mantener PPM dentro del rango personalizado
currentPPM = Math.min(Math.max(currentPPM, 60), 200);
```

### Cambiar endpoint del servidor
Modifica en el código Arduino:

```cpp
const char* endpoint = "/mi_endpoint_personalizado.php";
```

## Soporte

Si encuentras problemas:

1. Revisa el monitor serie del Arduino para mensajes de error
2. Verifica la consola del navegador (F12) para errores JavaScript
3. Revisa los logs del servidor web
4. Asegúrate de que todas las dependencias estén instaladas

## Licencia

Este proyecto está bajo la misma licencia que el reproductor inteligente principal.
