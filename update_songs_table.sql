-- Script para verificar la estructura de la tabla 'songs'
-- Ejecutar este script en phpMyAdmin o mediante la línea de comandos de MySQL

-- Verificar que la tabla tenga los campos necesarios
SELECT 
    CASE 
        WHEN COUNT(*) = 1 THEN 'La tabla songs tiene la estructura correcta' 
        ELSE 'La tabla songs necesita ser revisada' 
    END AS 'Estado'
FROM information_schema.TABLES 
WHERE 
    TABLE_SCHEMA = 'songs_database' AND 
    TABLE_NAME = 'songs';

-- Verificar que los campos necesarios existan
SELECT 
    CASE 
        WHEN COUNT(*) = 4 THEN 'Todos los parámetros de análisis están presentes' 
        ELSE 'Faltan algunos parámetros de análisis' 
    END AS 'Parámetros'
FROM information_schema.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'songs_database' AND 
    TABLE_NAME = 'songs' AND 
    COLUMN_NAME IN ('energy', 'danceability', 'happiness', 'instrumentalness');

-- Mensaje de confirmación
SELECT 'Verificación de la tabla songs completada correctamente' AS 'Mensaje';
