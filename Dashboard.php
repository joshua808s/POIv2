<?php
require_once 'php/conexion.php';
session_start(); // Asegúrate de que la sesión se inicie aquí

if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE idUsuario = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    $username= $user['Nombreusu'];
    $usercel= $user['Celularusu'];
    $usercontra= $user['Contrasenausu'];
    $userImg= $user['Imgusu'];
    $userstatus= $user['Estado_conexionusu'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Mensajería - Versión Clara con Videollamadas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="user-profile">
                <img src="<?php echo htmlspecialchars($userImg);?>" alt="Mi Usuario" class="user-image" onclick="toggleUserMenu()">
                <span><?php echo htmlspecialchars($username);?></span>
                <button class="action-button" onclick="toggleActionMenu()" aria-label="Acciones">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 14c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-7c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                </button>
                <div class="user-menu" id="userMenu">
                    <ul>
                        <li onclick="openProfileModal()">Perfil</li>
                        <li>Configuración</li>
                        <li>Cerrar sesión</li>
                    </ul>
                </div>
                <div class="action-menu" id="actionMenu">
                    <ul>
                        <li onclick="openCreateGroupModal()">Crear grupo</li>
                        <li onclick="openAddUserModal()">Agregar usuario por teléfono</li>
                    </ul>
                </div>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar chat..." aria-label="Buscar chat">
            </div>
            <div class="contacts">
                <?php
                    include_once "php/data.php";
                    echo $output;
                ?>
            </div>
        </div>
        <div class="main-content">
            <div class="header">
                <div class="contact" id="contact">
                    
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Videollamada" onclick="startCall()"><path d="M23 7l-7 5 7 5V7z"></path>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                </div>
            </div>
            <div class="chat-area" id="chat-area">
    
            </div>
            <div class="input-area">
                <form id="messageForm">
                <label for="file-input" class="file-label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Adjuntar archivo"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                </label>
                <input type="file" id="file-input" class="file-input" aria-label="Adjuntar archivo">
                <input type="text" name="msj" placeholder="Escribe un mensaje..." aria-label="Escribe un mensaje">
                <input class="button" type="submit" value="Enviar">
                </form>
            </div>
        </div>
    </div>
    
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeProfileModal()">&times;</span>
            <h2>Perfil de Usuario</h2>
            <form class="profile-form" onsubmit="updateProfile(event)">
                <div class="profile-photo-container" onclick="document.getElementById('profile-photo-input').click()">
                    <img src="/placeholder.svg?height=100&width=100" alt="Foto de perfil" class="profile-photo" id="profile-photo">
                    <div class="profile-photo-overlay">
                        <span class="profile-photo-text">Cambiar foto</span>
                    </div>
                </div>
                <input type="file" id="profile-photo-input" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <label for="profile-name">Nombre:</label>
                <input type="text" id="profile-name" required>
                <label for="profile-phone">Número de teléfono:</label>
                <input type="tel" id="profile-phone" required>
                <label for="profile-password">Contraseña:</label>
                <input type="password" id="profile-password" required>
                <button type="submit">Guardar cambios</button>
            </form>
        </div>
    </div>
    
    <div id="createGroupModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCreateGroupModal()">&times;</span>
            <h2>Crear Grupo</h2>
            <form class="create-group-form"  method="POST" action="php/RegistrarGrupo.php">
                <label for="group-name">Nombre del grupo:</label>
                <input type="text" id="group-name" name="group-name" required>
                <label>Seleccionar miembros:</label>
                <div class="group-members">
                    <?php
                    echo $output2;
                    ?>
                </div>
                <button type="submit">Crear Grupo</button>
            </form>
        </div>
    </div>
    
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddUserModal()">&times;</span>
            <h2>Buscar Usuario por Teléfono</h2>
            <form class="add-user-form" onsubmit="addUser(event)">
                <label for="user-phone">Número de teléfono:</label>
                <input type="tel" id="user-phone" required>
                <button type="submit">Buscar</button>
            </form>
            <div id="user-result" style="margin-top: 20px;"></div>
        </div>
    </div>

    <div id="callModal" class="call-modal">
        <div class="call-content">
            <div class="call-header">Videollamada con Juan Pérez</div>
            <div class="video-container">
                <video class="video-large" id="remoteVideo" autoplay playsinline></video>
                <video class="video-small" id="localVideo" autoplay playsinline muted></video>
            </div>
            <div class="call-controls">
                <button class="call-button mute-call" onclick="toggleMute()" aria-label="Silenciar llamada">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                </button>
                <button class="call-button end-call" onclick="endCall()" aria-label="Finalizar llamada">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"></path><line x1="23" y1="1" x2="1" y2="23"></line></svg>
                </button>
                <button class="call-button video-call" onclick="toggleVideo()" aria-label="Activar/Desactivar video">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                </button>
                <!-- <button class="call-button minimize-call" onclick="toggleMinimize()" aria-label="Minimizar llamada">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 14 10 14 10 20"></polyline><polyline points="20 10 14 10 14 4"></polyline><line x1="14" y1="10" x2="21" y2="3"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>
                </button> -->
            </div>
        </div>
    </div>
    <script src="main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       
       $('#messageForm').on('submit', function(e) {
    e.preventDefault(); // Evitar la recarga de la página

    var contactId = $('input[name="contactId"]').val(); // Obtener el ID del contacto
    var mensaje = $('input[name="msj"]').val(); // Obtener el mensaje

    console.log("Enviando mensaje a contacto ID: " + contactId); // Debugging

    $.ajax({
        url: 'php/sendMessage.php', // Archivo PHP que maneja el envío
        type: 'POST',
        data: {
            contactId: contactId,
            mensaje: mensaje
        },
        success: function(response) {
            console.log("Mensaje enviado: ", response); // Manejo de éxito

            // Limpiar el campo de texto del mensaje
            $('input[name="msj"]').val('');

            // Mostrar el nuevo mensaje en la interfaz en tiempo real
            loadUserInfo(contactId);
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX: ", error); // Manejo de errores
            // Aquí puedes mostrar un mensaje de error al usuario
        }
    });
});


    function loadUserInfo(contactId) {
        $('input[name="contactId"]').val(contactId);
        contactId2 = contactId; 
        fetch('php/getMessages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'contactId=' + encodeURIComponent(contactId)
        })
        .then(response => response.text())
        .then(data => {
            // Aquí puedes procesar los mensajes recibidos desde PHP
            document.querySelector('.chat-area').innerHTML = data; // Supón que tienes un área para mostrar los mensajes
        })
        .catch(error => console.error('Error:', error));

        const userId = <?php echo $userId; ?>; // ID del usuario actual desde PHP
        const chatArea = document.getElementById('chat-area'); // Donde se mostrarán los mensajes

        // Hacer la petición AJAX para obtener los mensajes
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/getMessages.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xhr.onload = function() {
            if (this.status === 200) {
                chatArea.innerHTML = this.responseText;
            }
        };

        xhr.send("userId=" + userId + "&contactId=" + contactId);
    }
    setInterval(function() {
        loadUserInfo(contactId2);
    }, 1000);
</script>
</body>
</html>