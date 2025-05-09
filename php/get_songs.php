<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT artist, name, year, PPM as ppm, audioUrl FROM songs");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($songs);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
