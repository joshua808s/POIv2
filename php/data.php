<?php
    $output = "";
    $output2 = "";
    // Consulta para obtener todos los usuarios excepto el usuario actual
    $query = "SELECT * FROM Usuario WHERE idUsuario != :userId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId]);

    // Verificar si hay usuarios
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            // Aquí puedes acceder a los datos de cada usuario
            $contactId = $row['idUsuario'];
            $contactName = htmlspecialchars($row['Nombreusu']); // Cambia 'nombre' según tu estructura de base de datos
            $contactImage = htmlspecialchars($row['Imgusu']); // Cambia 'imagen' según tu estructura de base de datos
            $contactStatus = $row['Estado_conexionusu']; // Cambia 'status' según tu estructura de base de datos

            // Imprimir cada contacto
            $output .= '<div class="contact"onclick="loadUserInfo(' . $contactId . ')">
            <input type="hidden" name="contactId" value="' . $contactId . '">
            <img src="'. $contactImage .'" alt="' . $contactName . '" class="contact-image">
            <span style="background-color: ' . ($contactStatus ? '#21ff51' : '#fe3f3f') . '" class="status-indicator"></span>
            <span class="contact-name"> '. $contactName .' </span>
            <span class="unread-indicator" aria-label="Mensaje no leído"></span>
            </div>';

            $output2 .= '<div class="group-member"> 
            <input type="" name="contactId2" value="' . $contactId . '">
            <input type="checkbox" id="member-' . $contactId . '" name="group-members[]" value="' . $contactId . '">
            <label for="member-' . $contactId . '">' . $contactName . '</label>
            </div>';
        }
    } else {
        echo "No hay usuarios disponibles.";
    }
?>

