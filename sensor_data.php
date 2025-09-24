<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'songs_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos del sensor
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos JSON inválidos']);
        exit;
    }
    
    $heartRate = isset($input['heartRate']) ? floatval($input['heartRate']) : 0;
    $spO2 = isset($input['spO2']) ? floatval($input['spO2']) : 0;
    $timestamp = isset($input['timestamp']) ? intval($input['timestamp']) : time() * 1000;
    
    // Validar datos
    if ($heartRate < 0 || $heartRate > 300 || $spO2 < 0 || $spO2 > 100) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos del sensor fuera de rango válido']);
        exit;
    }
    
    try {
        // Insertar datos en la base de datos
        $stmt = $pdo->prepare("INSERT INTO sensor_data (heart_rate, spo2, timestamp) VALUES (?, ?, ?)");
        $stmt->execute([$heartRate, $spO2, $timestamp]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Datos guardados correctamente',
            'data' => [
                'heartRate' => $heartRate,
                'spO2' => $spO2,
                'timestamp' => $timestamp
            ]
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar datos: ' . $e->getMessage()]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener los últimos datos del sensor
    try {
        $stmt = $pdo->prepare("SELECT heart_rate, spo2, timestamp FROM sensor_data ORDER BY timestamp DESC LIMIT 1");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'heartRate' => floatval($data['heart_rate']),
                    'spO2' => floatval($data['spo2']),
                    'timestamp' => intval($data['timestamp'])
                ]
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => [
                    'heartRate' => 0,
                    'spO2' => 0,
                    'timestamp' => time() * 1000
                ]
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener datos: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
