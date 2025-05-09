<?php
header('Content-Type: application/json');

// Obtener datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || empty($data['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID de canción no proporcionado'
    ]);
    exit;
}

$songId = $data['id'];

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Primero, obtener la información de la canción para eliminar el archivo
    $stmt = $pdo->prepare("SELECT audioUrl FROM songs WHERE id = ?");
    $stmt->execute([$songId]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$song) {
        throw new Exception('Canción no encontrada');
    }
    
    // Eliminar el archivo físico si existe
    $audioUrl = $song['audioUrl'];
    if (file_exists($audioUrl) && strpos($audioUrl, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/') === 0) {
        unlink($audioUrl);
    }
    
    // Eliminar la canción de la base de datos
    $stmt = $pdo->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->execute([$songId]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('No se pudo eliminar la canción');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Canción eliminada correctamente'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
