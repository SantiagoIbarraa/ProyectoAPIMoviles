<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

// Directorio donde se guardarán los archivos de audio
$uploadDir = 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/';

// Crear el directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

try {
    // Verificar que se haya enviado un archivo
    if (!isset($_FILES['songFile']) || $_FILES['songFile']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo de audio');
    }

    // Verificar que se hayan enviado los datos requeridos
    if (empty($_POST['songTitle']) || empty($_POST['songArtist'])) {
        throw new Exception('El título y el artista son obligatorios');
    }

    // Validar el archivo
    $fileInfo = pathinfo($_FILES['songFile']['name']);
    $extension = strtolower($fileInfo['extension']);
    
    // Verificar extensión
    $allowedExtensions = ['mp3', 'wav', 'ogg'];
    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception('Formato de archivo no válido. Solo se permiten archivos MP3, WAV y OGG');
    }
    
    // Verificar tamaño (20MB máximo)
    $maxSize = 20 * 1024 * 1024; // 20MB en bytes
    if ($_FILES['songFile']['size'] > $maxSize) {
        throw new Exception('El archivo es demasiado grande. El tamaño máximo permitido es 20MB');
    }
    
    // Generar un nombre para el archivo que siga el formato de las canciones existentes
    $fileName = $_POST['songTitle'] . '.' . $extension;
    // Mantener espacios para consistencia con archivos existentes
    
    // Ruta completa del archivo
    $filePath = $uploadDir . $fileName;
    
    // Mover el archivo subido al directorio de destino
    if (!move_uploaded_file($_FILES['songFile']['tmp_name'], $filePath)) {
        throw new Exception('Error al guardar el archivo');
    }
    
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Preparar los datos para la inserción
    $title = $_POST['songTitle'];
    $artist = $_POST['songArtist'];
    $year = !empty($_POST['songYear']) ? $_POST['songYear'] : null;
    $ppm = !empty($_POST['songPPM']) ? $_POST['songPPM'] : null;
    $energy = !empty($_POST['songEnergy']) ? $_POST['songEnergy'] : null;
    $danceability = !empty($_POST['songDanceability']) ? $_POST['songDanceability'] : null;
    $happiness = !empty($_POST['songHappiness']) ? $_POST['songHappiness'] : null;
    $instrumentalness = !empty($_POST['songInstrumentalness']) ? $_POST['songInstrumentalness'] : null;
    // Guardar la ruta en el mismo formato que las canciones existentes
    $audioUrl = 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/' . $fileName;
    
    // Insertar la canción en la base de datos
    $stmt = $pdo->prepare("
        INSERT INTO songs (artist, name, year, energy, danceability, happiness, instrumentalness, PPM, audioUrl)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $artist,
        $title,
        $year,
        $energy,
        $danceability,
        $happiness,
        $instrumentalness,
        $ppm,
        $audioUrl
    ]);
    
    // Obtener el ID de la canción insertada
    $songId = $pdo->lastInsertId();
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Canción agregada correctamente',
        'songId' => $songId
    ]);
    
} catch (Exception $e) {
    // Si hay un error, eliminar el archivo si se subió
    if (isset($filePath) && file_exists($filePath)) {
        unlink($filePath);
    }
    
    // Respuesta de error
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
