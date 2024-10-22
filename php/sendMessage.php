<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    try {
        $emisorId = $_SESSION['user_id'];
        $receptorId = $_POST['contactId'];
        $mensaje = $_POST['mensaje'];
       

        // Insertar el mensaje en la base de datos
        $query = "INSERT INTO mensaje (idEmisor, idReceptor, Contenido) VALUES (:emisor, :receptor, :contenido)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'emisor' => $emisorId,
            'receptor' => $receptorId,
            'contenido' => $mensaje
        ]);


    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar el mensaje: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
?>