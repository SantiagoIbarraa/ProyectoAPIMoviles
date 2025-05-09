<?php
// Script para verificar la estructura de la base de datos
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbname = 'songs_database';
$user = 'root';
$pass = '';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Verificando la estructura de la base de datos...</h1>";
    
    // Verificar que la tabla songs exista
    $stmt = $pdo->query("SHOW TABLES LIKE 'songs'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "<p class='text-danger'>La tabla 'songs' no existe en la base de datos.</p>";
    } else {
        echo "<p class='text-success'>La tabla 'songs' existe en la base de datos.</p>";
        
        // Verificar que los campos necesarios existan
        $requiredColumns = ['energy', 'danceability', 'happiness', 'instrumentalness'];
        $missingColumns = [];
        
        foreach ($requiredColumns as $column) {
            $stmt = $pdo->query("SHOW COLUMNS FROM songs LIKE '$column'");
            if (!$stmt->fetch()) {
                $missingColumns[] = $column;
            }
        }
        
        if (empty($missingColumns)) {
            echo "<p class='text-success'>Todos los parámetros de análisis están presentes en la tabla.</p>";
        } else {
            echo "<p class='text-danger'>Faltan los siguientes parámetros en la tabla: " . implode(', ', $missingColumns) . "</p>";
        }
        
        // Eliminar columnas de características avanzadas si existen
        $advancedColumns = ['dynamics', 'brightness', 'complexity', 'rhythm'];
        $existingAdvancedColumns = [];
        
        foreach ($advancedColumns as $column) {
            $stmt = $pdo->query("SHOW COLUMNS FROM songs LIKE '$column'");
            if ($stmt->fetch()) {
                $existingAdvancedColumns[] = $column;
            }
        }
        
        if (!empty($existingAdvancedColumns)) {
            // Informar al usuario pero no eliminar las columnas
            echo "<p class='text-warning'>Se han detectado columnas adicionales en la tabla: " . implode(', ', $existingAdvancedColumns) . ". Estas columnas no se utilizan en el sistema actual.</p>";
        }
    }
    
    echo "<h2>¡Verificación completada correctamente!</h2>";
    echo "<p>El sistema está configurado para utilizar solo los parámetros tradicionales: energía, bailabilidad, felicidad e instrumentalidad.</p>";
    echo "<p>Las características avanzadas (dinámica, brillo, complejidad y ritmo) se calculan y muestran en la interfaz pero no se guardan en la base de datos.</p>";
    echo "<p><a href='admin.php' class='btn btn-primary'>Volver al panel de administración</a></p>";
    
} catch (PDOException $e) {
    echo "<h1>Error al verificar la base de datos</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p><a href='admin.php'>Volver al panel de administración</a></p>";
}
?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #121212;
        color: #fff;
        padding: 20px;
    }
    h1, h2 {
        color: #fff;
    }
    p {
        margin-bottom: 10px;
    }
    .text-success {
        color: #4CAF50;
    }
    .text-danger {
        color: #F44336;
    }
    .text-warning {
        color: #FFC107;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #7c4dff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }
    .btn:hover {
        background-color: #6a3aff;
    }
</style>
