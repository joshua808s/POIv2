let localStream;
let remoteStream;

function toggleUserMenu() {
    var menu = document.getElementById('userMenu');
    menu.classList.toggle('active');
}

function toggleActionMenu() {
    var menu = document.getElementById('actionMenu');
    menu.classList.toggle('active');
}

function openProfileModal() {
    var modal = document.getElementById('profileModal');
    modal.style.display = "block";
}

function closeProfileModal() {
    var modal = document.getElementById('profileModal');
    modal.style.display = "none";
}

function openCreateGroupModal() {
    var modal = document.getElementById('createGroupModal');
    modal.style.display = "block";
}

function closeCreateGroupModal() {
    var modal = document.getElementById('createGroupModal');
    modal.style.display = "none";
}

function openAddUserModal() {
    var modal = document.getElementById('addUserModal');
    modal.style.display = "block";
}

function closeAddUserModal() {
    var modal = document.getElementById('addUserModal');
    modal.style.display = "none";
}

function updateProfile(event) {
    event.preventDefault();
    // Aquí iría la lógica para actualizar el perfil
    console.log('Perfil actualizado');
    closeProfileModal();
}

function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('profile-photo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

async function startCall() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        document.getElementById('localVideo').srcObject = localStream;
        
        // Aquí iría la lógica para iniciar la videollamada con un servicio de WebRTC
        console.log('Iniciando videollamada');
        
        var modal = document.getElementById('callModal');
        modal.style.display = "block";
    } catch (err) {
        console.error('Error al acceder a la cámara y el micrófono:', err);
        alert('No se pudo acceder a la cámara y el micrófono. Por favor, asegúrate de que tienes los permisos necesarios.');
    }
}

function endCall() {
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    document.getElementById('localVideo').srcObject = null;
    document.getElementById('remoteVideo').srcObject = null;
    
    var modal = document.getElementById('callModal');
    modal.style.display = "none";
    modal.classList.remove('minimized');
    
    console.log('Videollamada finalizada');
}

function toggleMute() {
    if (localStream) {
        localStream.getAudioTracks().forEach(track => {
            track.enabled = !track.enabled;
        });
        console.log('Audio ' + (localStream.getAudioTracks()[0].enabled ? 'activado' : 'silenciado'));
    }
}

function toggleVideo() {
    if (localStream) {
        localStream.getVideoTracks().forEach(track => {
            track.enabled = !track.enabled;
        });
        console.log('Video ' + (localStream.getVideoTracks()[0].enabled ? 'activado' : 'desactivado'));
    }
}

function toggleMinimize() {
    var modal = document.getElementById('callModal');
    modal.classList.toggle('minimized');
    console.log('Minimizar/Maximizar videollamada');
}

// Evitar que se cierre el modal de videollamada al hacer clic fuera
window.onclick = function(event) {
    var profileModal = document.getElementById('profileModal');
    var callModal = document.getElementById('callModal');
    if (event.target == profileModal) {
        profileModal.style.display = "none";
    }
    if (event.target == callModal && !callModal.classList.contains('minimized')) {
        toggleMinimize();
    }
}

function createGroup(event) {
    event.preventDefault();
    var groupName = document.getElementById('group-name').value;
    var selectedMembers = Array.from(document.querySelectorAll('input[name="group-members"]:checked')).map(el => el.value);
    // Aquí iría la lógica para crear el grupo
    console.log('Grupo creado:', groupName, 'con miembros:', selectedMembers);
    closeCreateGroupModal();
}

function addUser(event) {
    event.preventDefault();
    var phoneNumber = document.getElementById('user-phone').value;
    // Aquí iría la lógica para agregar el usuario
    console.log('Usuario agregado con número:', phoneNumber);
    closeAddUserModal();
}

