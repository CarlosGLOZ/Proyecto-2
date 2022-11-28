const botones_salas = document.getElementsByClassName('link-filtro-sala');
const elemento_filtro_sala = document.getElementsByName('filtro_sala')[0];
const boton_limpiar_filtros = document.getElementById('boton-limpiar-filtros');

for (let i = 0; i < botones_salas.length; i++) {
    botones_salas[i].addEventListener('click', function() {
        elemento_filtro_sala.value = botones_salas[i].value;
        listarMesas();
    });
}

boton_limpiar_filtros.addEventListener('click', (e) => {
    e.preventDefault();
    limpiarFiltros();
})