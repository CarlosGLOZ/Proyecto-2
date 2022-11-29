const botones_salas = document.getElementsByClassName('link-filtro-sala');
const elemento_filtro_sala = document.getElementsByName('filtro_sala')[0];

const boton_limpiar_filtros = document.getElementById('boton-limpiar-filtros');

const botones_formularios_modales = document.getElementsByClassName('modal-form-submit-button');

// Al clickar en una sala, el valor del input escondido para el filtro de sala se canvia a la sala correspondiente
for (let i = 0; i < botones_salas.length; i++) {
    botones_salas[i].addEventListener('click', function() {
        elemento_filtro_sala.value = botones_salas[i].value;
        listarMesas();
    });
}

// Reestablecer los valored del formulario de filtros
boton_limpiar_filtros.addEventListener('click', (e) => {
    e.preventDefault();
    limpiarFiltros();
});

// Prevenir envios de formulario de los modales
for (let i = 0; i < botones_formularios_modales.length; i++) {
    botones_formularios_modales[i].addEventListener('click', (e) => {
        e.preventDefault();
    });
}