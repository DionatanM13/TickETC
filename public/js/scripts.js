document.getElementById('private').addEventListener('change', function() {
    var campoDominio = document.getElementById('campoDominio');
    console.log("oi");
    if (this.value === '1') {
        campoDominio.style.display = 'block'; // Mostra o campo
    } else {
        campoDominio.style.display = 'none'; // Esconde o campo
    }
});


function toggleDashboard() {
    const container = document.getElementById('subevents-container');
    if (container.style.display === 'none') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}