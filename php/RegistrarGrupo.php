<?php
session_start();
if (isset($_POST['group-members'])) {
    // Obtener los IDs de los usuarios seleccionados
    $selectedUsers = $_POST['group-members'];

    // Conexión a la base de datos
    include_once 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

    // Variables para la inserción
    $nombreGrupo = $_POST['group-name'];  // Cambia esto a un nombre dinámico si lo deseas
    $idUsuarioCreador = $_SESSION['user_id'];        // ID del usuario que está creando el grupo

    try {
        // Iniciar una transacción
        $pdo->beginTransaction();

        // 1. Insertar un nuevo grupo en la tabla Grupo
        $queryGrupo = "INSERT INTO Grupo (Nombregrupo, idUsuario_creador) VALUES (:nombreGrupo, :idUsuarioCreador)";
        $stmtGrupo = $pdo->prepare($queryGrupo);
        $stmtGrupo->execute(['nombreGrupo' => $nombreGrupo, 'idUsuarioCreador' => $idUsuarioCreador]);

        // Obtener el ID del grupo recién creado
        $idGrupo = $pdo->lastInsertId();

        // 2. Insertar los usuarios seleccionados en la tabla Miembro_Grupo
        $queryMiembroGrupo = "INSERT INTO Miembro_Grupo (idGrupo, idUsuario) VALUES (:idGrupo, :idUsuario)";
        $stmtMiembroGrupo = $pdo->prepare($queryMiembroGrupo);

        foreach ($selectedUsers as $userId) {
            // Insertar cada usuario como miembro del grupo
            $stmtMiembroGrupo->execute(['idGrupo' => $idGrupo, 'idUsuario' => $userId]);
        }

        // Confirmar la transacción
        $pdo->commit();
        header("Location: ../Dashboard.php");
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $pdo->rollBack();
        echo "Error al crear el grupo: " . $e->getMessage();
    }
} else {
    echo "No se seleccionó ningún usuario.";
}
?>