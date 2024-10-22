<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $contrasena = $_POST['contrasena'];
    $imgUsuario = $_FILES['imagen']['name'];
    $temporal= $_FILES['imagen']['tmp_name'];
    $carpeta='../Imagenesperfil';
    $ruta= $carpeta.'/'.$imgUsuario;
    move_uploaded_file($temporal,$carpeta.'/'. $imgUsuario);
    $carpeta='Imagenesperfil';
    $ruta= $carpeta.'/'.$imgUsuario;
   
    $stmt = $pdo->prepare("INSERT INTO Usuario (Nombreusu, Celularusu, Contrasenausu, Imgusu) VALUES (?, ?, ?, ?)");
    
    try {
        $stmt->execute([$nombre, $celular, $contrasena, $ruta]);
        echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito']);
        header("Location: ../index.html");
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . $e->getMessage()]);
    }
}
?>