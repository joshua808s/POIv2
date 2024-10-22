<?php
require_once 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $celular = $_POST['celular'];
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE Celularusu = ?");
    $stmt->execute([$celular]);
    $user = $stmt->fetch();

    // Verificar si se encontró un usuario
    if ($user) {
        // Comparar la contraseña
        if ($contrasena == $user['Contrasenausu']) {
            // Actualizar estado de conexión
            $stmt = $pdo->prepare("UPDATE Usuario SET Estado_conexionusu = 1 WHERE idUsuario = ?");
            $stmt->execute([$user['idUsuario']]);
            // Almacenar datos en la sesión
            $_SESSION['user_id'] = $user['idUsuario'];
            $_SESSION['user_name'] = $user['Nombreusu'];
            $_SESSION['user_cel'] = $user['Celularusu'];
            $_SESSION['user_contra'] = $user['Contrasenausu'];
            $_SESSION['user_Img'] = $user['Imgusu'];
            $_SESSION['user_status'] = $user['Estado_conexionusu'];

            // Redirigir a Dashboard
            header("Location: ../Dashboard.php");
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    }
}
?>