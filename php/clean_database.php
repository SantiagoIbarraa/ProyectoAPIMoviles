<?php
header('Content-Type: text/html; charset=UTF-8');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<html><head>
        <title>Limpieza de Base de Datos</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <style>
            body { background: #222; color: #fff; padding: 20px; }
            .container { max-width: 900px; margin: 0 auto; }
            .card { background: #333; border: none; border-radius: 10px; margin-bottom: 20px; }
            .card-header { background: #444; color: #ff4444; font-weight: bold; }
            .table { color: #fff; }
            .table th { color: #ff4444; }
            .alert-success { background-color: #2a5934; color: #fff; border: none; }
            h1 { margin-bottom: 30px; color: #ff4444; }
        </style>
    </head><body>
    <div class='container'>
        <h1>Limpieza de Base de Datos de Canciones</h1>";
    
    // Contar canciones antes de la limpieza
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM songs");
    $totalBefore = $stmtCount->fetchColumn();
    
    echo "<div class='alert alert-info'>Total de canciones antes de la limpieza: <strong>{$totalBefore}</strong></div>";
    
    // Crear tabla temporal para almacenar IDs a eliminar
    $pdo->exec("CREATE TEMPORARY TABLE IF NOT EXISTS songs_to_delete AS (
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
    )");
    
    // Mostrar las canciones que se eliminarán
    $stmtToDelete = $pdo->query("SELECT * FROM songs WHERE id IN (SELECT id FROM songs_to_delete)");
    $songsToDelete = $stmtToDelete->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='card'>
        <div class='card-header'>Canciones que serán eliminadas (" . count($songsToDelete) . ")</div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-dark'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Artista</th>
                            <th>Nombre</th>
                            <th>Año</th>
                            <th>PPM</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    foreach ($songsToDelete as $song) {
        echo "<tr>
            <td>{$song['id']}</td>
            <td>{$song['artist']}</td>
            <td>{$song['name']}</td>
            <td>" . ($song['year'] ? $song['year'] : 'NULL') . "</td>
            <td>" . ($song['PPM'] ? $song['PPM'] : 'NULL') . "</td>
        </tr>";
    }
    
    echo "</tbody></table></div></div></div>";
    
    // Eliminar las canciones duplicadas
    $stmtDelete = $pdo->prepare("DELETE FROM songs WHERE id IN (SELECT id FROM songs_to_delete)");
    $stmtDelete->execute();
    $deletedCount = $stmtDelete->rowCount();
    
    // Contar canciones después de la limpieza
    $stmtCountAfter = $pdo->query("SELECT COUNT(*) FROM songs");
    $totalAfter = $stmtCountAfter->fetchColumn();
    
    echo "<div class='alert alert-success'>
        <h4>Limpieza completada</h4>
        <p>Se eliminaron <strong>{$deletedCount}</strong> canciones duplicadas.</p>
        <p>Total de canciones después de la limpieza: <strong>{$totalAfter}</strong></p>
    </div>";
    
    // Mostrar algunas de las canciones restantes
    $stmtRemaining = $pdo->query("SELECT * FROM songs ORDER BY artist, name LIMIT 10");
    $remainingSongs = $stmtRemaining->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='card'>
        <div class='card-header'>Muestra de canciones restantes</div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-dark'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Artista</th>
                            <th>Nombre</th>
                            <th>Año</th>
                            <th>PPM</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    foreach ($remainingSongs as $song) {
        echo "<tr>
            <td>{$song['id']}</td>
            <td>{$song['artist']}</td>
            <td>{$song['name']}</td>
            <td>" . ($song['year'] ? $song['year'] : 'NULL') . "</td>
            <td>" . ($song['PPM'] ? $song['PPM'] : 'NULL') . "</td>
        </tr>";
    }
    
    echo "</tbody></table></div></div></div>";
    
    // Eliminar la tabla temporal
    $pdo->exec("DROP TEMPORARY TABLE IF EXISTS songs_to_delete");
    
    echo "<div class='mt-4'>
        <a href='list_songs.php' class='btn btn-danger'>Volver al listado de canciones</a>
    </div>";
    
    echo "</div></body></html>";
    
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
}
?>
