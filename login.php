<?php
session_start();

// Credenciales específicas para el administrador
$admin_username = "admin";
$admin_password = "admin123"; // En producción, usar hash seguro

// Verificar si se enviaron credenciales
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Verificar si las credenciales son correctas
    if ($username === $admin_username && $password === $admin_password) {
        // Iniciar sesión
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // Redirigir a la página de administración
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } else {
        // Credenciales incorrectas
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
        exit;
    }
}

// Si se solicita cerrar sesión
if (isset($_GET['logout'])) {
    // Destruir la sesión
    session_unset();
    session_destroy();
    
    // Redirigir a la página principal
    header('Location: index.html');
    exit;
}

// Si no se envió ningún formulario, redirigir a la página principal
header('Location: index.html');
exit;
?>
