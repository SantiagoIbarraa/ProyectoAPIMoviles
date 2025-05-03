<?php
// Script para actualizar el campo audioUrl en la tabla songs
// Recorre la carpeta de canciones y actualiza la base de datos con la ruta relativa de cada archivo mp3

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

$musicDir = __DIR__ . DIRECTORY_SEPARATOR . 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock';
$relativeMusicDir = 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$updated = 0;
$notFound = [];

// Obtener todas las canciones de la base de datos
$stmt = $pdo->query("SELECT id, artist, name FROM songs");
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los archivos mp3 del directorio
$mp3Files = glob($musicDir . DIRECTORY_SEPARATOR . '*.mp3');

foreach ($songs as $song) {
    $found = false;
    foreach ($mp3Files as $filePath) {
        $fileName = basename($filePath, '.mp3');
        // Normalizar nombres para comparar sin mayúsculas/minúsculas y sin caracteres especiales
        $normalizedSong = strtolower(preg_replace('/[^a-z0-9]/i', '', $song['name']));
        $normalizedFile = strtolower(preg_replace('/[^a-z0-9]/i', '', $fileName));
        if ($normalizedSong === $normalizedFile) {
            // Formar la ruta relativa para la base de datos
            $audioUrl = $relativeMusicDir . '/' . basename($filePath);
            // Actualizar la base de datos
            $upd = $pdo->prepare("UPDATE songs SET audioUrl = ? WHERE id = ?");
            $upd->execute([$audioUrl, $song['id']]);
            $updated++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $notFound[] = $song['name'];
    }
}

echo "Canciones actualizadas: $updated\n";
if (!empty($notFound)) {
    echo "No se encontró archivo mp3 para: " . implode(', ', $notFound) . "\n";
}
?>
