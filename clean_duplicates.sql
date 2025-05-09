-- Script para eliminar canciones duplicadas en la tabla songs
-- Prioriza mantener las versiones con más información (menos campos NULL)

-- Crear una tabla temporal para almacenar los IDs de las canciones a eliminar
CREATE TEMPORARY TABLE IF NOT EXISTS songs_to_delete AS (
    -- Seleccionar IDs de canciones duplicadas (basado en nombre de archivo de audio)
    SELECT s1.id
    FROM songs s1
    JOIN songs s2 ON s1.audioUrl = s2.audioUrl 
                  AND s1.id > s2.id
    
    UNION
    
    -- Seleccionar IDs de canciones con NULL que tienen una versión completa
    SELECT s1.id
    FROM songs s1
    JOIN songs s2 ON LOWER(s1.name) = LOWER(s2.name) 
                  AND LOWER(COALESCE(s1.artist, '')) = LOWER(COALESCE(s2.artist, ''))
                  AND (
                      (s1.year IS NULL AND s2.year IS NOT NULL) OR
                      (s1.energy IS NULL AND s2.energy IS NOT NULL) OR
                      (s1.danceability IS NULL AND s2.danceability IS NOT NULL) OR
                      (s1.happiness IS NULL AND s2.happiness IS NOT NULL) OR
                      (s1.instrumentalness IS NULL AND s2.instrumentalness IS NOT NULL)
                  )
    
    UNION
    
    -- Seleccionar IDs de canciones con artista 'Unknown' que tienen una versión con artista conocido
    SELECT s1.id
    FROM songs s1
    JOIN songs s2 ON LOWER(s1.name) = LOWER(s2.name)
                  AND s1.artist = 'Unknown'
                  AND s2.artist != 'Unknown'
);

-- Mostrar las canciones que se eliminarán
SELECT * FROM songs WHERE id IN (SELECT id FROM songs_to_delete);

-- Eliminar las canciones duplicadas
DELETE FROM songs WHERE id IN (SELECT id FROM songs_to_delete);

-- Mostrar las canciones restantes después de la limpieza
SELECT COUNT(*) AS remaining_songs FROM songs;

-- Eliminar la tabla temporal
DROP TEMPORARY TABLE IF EXISTS songs_to_delete;
