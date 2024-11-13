document.getElementById('private').addEventListener('change', function() {
    var campoDominio = document.getElementById('campoDominio');
    if (this.value === '1') {
        console.log(2)
        campoDominio.style.display = 'block'; // Mostra o campo
    } else {
        campoDominio.style.display = 'none'; // Esconde o campo
    }
});

document.getElementById('days').addEventListener('change', function() {
    var campoFinalDate = document.getElementById('finalDate');
    if (this.value === '1') {
        console.log(1)
        campoFinalDate.style.display = 'block'; // Mostra o campo
    } else {
        campoFinalDate.style.display = 'none'; // Esconde o campo
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