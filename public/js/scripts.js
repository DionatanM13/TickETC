document.getElementById('private').addEventListener('change', function() {
    var campoDominio = document.getElementById('campoDominio');
    console.log("oi");
    if (this.value === '1') {
        campoDominio.style.display = 'block'; // Mostra o campo
    } else {
        campoDominio.style.display = 'none'; // Esconde o campo
    }
});


function toggleDashboardOwner(){
    const owner = document.getElementById('event-owner-container');
    const participant = document.getElementById('event-participant-container');

        owner.style.display = 'block';
        participant.style.display = 'none';
}

function toggleDashboardParticipant(){
    const owner = document.getElementById('event-owner-container');
    const participant = document.getElementById('event-participant-container');

    owner.style.display = 'none';
    participant.style.display = 'block';
}