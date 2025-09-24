-- Crear tabla para almacenar datos del sensor MAX30100
-- Usar la base de datos existente
USE songs_database;

CREATE TABLE IF NOT EXISTS sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heart_rate DECIMAL(5,2) NOT NULL,
    spo2 DECIMAL(5,2) NOT NULL,
    timestamp BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_timestamp (timestamp)
);

-- Insertar algunos datos de ejemplo (opcional)
INSERT INTO sensor_data (heart_rate, spo2, timestamp) VALUES 
(72.5, 98.2, UNIX_TIMESTAMP() * 1000),
(75.3, 97.8, UNIX_TIMESTAMP() * 1000 + 1000),
(78.1, 98.5, UNIX_TIMESTAMP() * 1000 + 2000);
