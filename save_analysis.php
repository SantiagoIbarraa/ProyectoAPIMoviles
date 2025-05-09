<?php
/**
 * Script para guardar los resultados del análisis de audio con Essentia.js
 * en la base de datos de canciones
 */

// Configuración de la base de datos
$db_host = "localhost";
$db_name = "songs_database";
$db_user = "root";  // Usuario por defecto de XAMPP
$db_pass = "";      // Contraseña por defecto de XAMPP (vacía)

// Encabezados para permitir solicitudes AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Solo se permiten solicitudes POST']);
    exit;
}

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si se recibieron los datos necesarios
if (!isset($data['title']) || !isset($data['artist']) || !isset($data['bpm'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

try {
    // Conectar a la base de datos
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Preparar los datos para insertar
    $title = $data['title'];
    $artist = $data['artist'];
    $year = isset($data['year']) ? intval($data['year']) : null;
    $ppm = isset($data['bpm']) ? intval($data['bpm']) : null;
    
    // Nuevas características de audio
    $dynamics = isset($data['dynamics']) ? floatval($data['dynamics']) : 0.5;
    $brightness = isset($data['brightness']) ? floatval($data['brightness']) : 0.5;
    $complexity = isset($data['complexity']) ? floatval($data['complexity']) : 0.5;
    $rhythm = isset($data['rhythm']) ? floatval($data['rhythm']) : 0.5;
    
    // Valores derivados para mantener compatibilidad con la base de datos existente
    $energy = isset($data['energy']) ? floatval($data['energy']) : $rhythm; // Usar rhythm como sustituto de energy
    $danceability = isset($data['danceability']) ? floatval($data['danceability']) : $dynamics; // Usar dynamics como sustituto
    $happiness = isset($data['happiness']) ? floatval($data['happiness']) : $brightness; // Usar brightness como sustituto
    $instrumentalness = isset($data['instrumentalness']) ? floatval($data['instrumentalness']) : (1 - $complexity); // Inverso de complexity
    
    // Ruta del archivo de audio (si se proporciona)
    $audioUrl = isset($data['audioUrl']) ? $data['audioUrl'] : '';
    
    // Preparar la consulta SQL
    $sql = "INSERT INTO songs (artist, name, year, energy, danceability, happiness, instrumentalness, PPM, audioUrl) 
            VALUES (:artist, :title, :year, :energy, :danceability, :happiness, :instrumentalness, :ppm, :audioUrl)";
    
    $stmt = $conn->prepare($sql);
    
    // Vincular parámetros
    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':energy', $energy);
    $stmt->bindParam(':danceability', $danceability);
    $stmt->bindParam(':happiness', $happiness);
    $stmt->bindParam(':instrumentalness', $instrumentalness);
    $stmt->bindParam(':ppm', $ppm);
    $stmt->bindParam(':audioUrl', $audioUrl);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el ID de la canción insertada
    $songId = $conn->lastInsertId();
    
    // Responder con éxito
    echo json_encode([
        'success' => true, 
        'message' => 'Canción guardada correctamente',
        'songId' => $songId,
        'energy' => $energy,
        'key' => isset($data['key']) ? $data['key'] : 'C',
        'scale' => isset($data['scale']) ? $data['scale'] : 'major'
    ]);
    
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    echo json_encode([
        'success' => false, 
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Manejar otros errores
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
