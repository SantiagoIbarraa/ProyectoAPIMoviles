<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener todas las canciones con sus IDs para administración
    $stmt = $pdo->query("SELECT id, artist, name, year, PPM as ppm, audioUrl FROM songs ORDER BY name ASC");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Procesar las rutas de audio para asegurar que sean accesibles desde el navegador
    foreach ($songs as &$song) {
        // Asegurarse de que la ruta sea relativa para que el navegador pueda acceder a ella
        if (strpos($song['audioUrl'], 'SpotiDownloader.com') === 0) {
            // La ruta ya está en formato correcto (relativa)
        } else if (file_exists($song['audioUrl'])) {
            // Es una ruta absoluta local, convertirla a relativa si es necesario
            // No hacemos nada aquí porque ya debería estar en formato correcto
        }
    }
    
    echo json_encode($songs);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>