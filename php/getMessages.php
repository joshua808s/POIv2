<?php
session_start();
include 'conexion.php'; // Archivo de conexión a la base de datos

try {
    $userId = $_SESSION['user_id']; // Usuario actual
    $contactId = $_POST['contactId']; // Usuario seleccionado

    // Consulta para obtener los mensajes entre los dos usuarios
    $query = "SELECT * FROM mensaje WHERE (idEmisor = :userId AND idReceptor = :contactId) OR (idEmisor = :contactId2 AND idReceptor = :userId2) ORDER BY Fecha_envio ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId, 'contactId' => $contactId, 'userId2' => $userId, 'contactId2' => $contactId]);

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $isEmisor = ($row['idEmisor'] == $userId);
            $mensaje = htmlspecialchars($row['Contenido']);
            $fecha = new DateTime($row['Fecha_envio']);
            $formattedDate = $fecha->format('H:i'); // Cambia el formato según tu preferencia

            $output .= '<div class="message '. ($isEmisor ? 'sent' : 'received') .'">
                <p>'. $mensaje .'</p>
                <span class="message-time">'. $formattedDate .'</span>
            </div>';
        }
    } else {
        $output = '<p>No hay mensajes.</p>';
    }

} catch (PDOException $e) {
    $output = '<p>Error al obtener los mensajes: ' . htmlspecialchars($e->getMessage()) . '</p>';
    
}

echo $output;
?>