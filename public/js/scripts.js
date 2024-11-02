document.getElementById('private').addEventListener('change', function() {
    var campoDominio = document.getElementById('campoDominio');
    console.log("oi");
    if (this.value === '1') {
        campoDominio.style.display = 'block'; // Mostra o campo
    } else {
        campoDominio.style.display = 'none'; // Esconde o campo
    }
});