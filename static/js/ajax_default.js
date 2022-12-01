const botones_salas = document.getElementsByClassName('link-filtro-sala');
const elemento_filtro_sala = document.getElementsByName('filtro_sala')[0];

const boton_limpiar_filtros = document.getElementById('boton-limpiar-filtros');

const botones_formularios_modales = document.getElementsByClassName('modal-form-submit-button');

const form_filtros = document.getElementById('form-filtros');

// Al clickar en una sala, el valor del input escondido para el filtro de sala se canvia a la sala correspondiente
for (let i = 0; i < botones_salas.length; i++) {
    botones_salas[i].addEventListener('click', function() {
        elemento_filtro_sala.value = botones_salas[i].value;
        listarMesas();
    });
}

// Reestablecer los valores del formulario de filtros
boton_limpiar_filtros.addEventListener('click', (e) => {
    e.preventDefault();

    // Devolver cada filtro a su valor por defecto
    for (let i = 0; i < form_filtros.length; i++) {
        if (form_filtros[i].id.split('_')[0] == 'filtro') {
            if (form_filtros[i].type == 'select') {
                form_filtros[i].value = form_filtros[i][0].value;
            } else {
                form_filtros[i].value = '';
            }
        }
    }
    const titulo = document.getElementsByTagName('title')[0].innerText
    if (titulo == 'Mesas') {
        listarMesas();
    } else if (titulo == 'Recursos') {
        listarRecursos()
    } else if (titulo == 'Empleados') {
        listarEmpleados();
    }
});

// Prevenir envios de formulario de los modales
for (let i = 0; i < botones_formularios_modales.length; i++) {
    botones_formularios_modales[i].addEventListener('click', (e) => {
        e.preventDefault();
    });
}

// Convertir fila de una tabla en formulario de modificar
function mostrarModalModificar(row) {
    const modal = document.getElementById('modal-comensales-container');
    modal.style.display = 'flex';

    const form = document.getElementById('modal-form-ocupar');

    const boton_guardar = document.getElementById('modal-form-boton-mod-guardar');
    boton_guardar.addEventListener('click', (e) => {
        e.preventDefault();
        modRecurso();
    });

    // Iterar sobre cada elemento del formulario y, si su nombre coincide con los nombres que hemos guardado en el controller, 
    // cambiar su valor al valor correspondiente
    for (let i = 0; i < form.length; i++) {
        if (form[i].name == NUMERO_BD_VARNAME) {
            form[i].value = row.getElementsByClassName('valor-' + NUMERO_VARNAME)[0].innerText;
        } else if (form[i].name == CAPACIDAD_BD_VARNAME) {
            form[i].value = row.getElementsByClassName('valor-' + CAPACIDAD_VARNAME)[0].innerText;
        } else if (form[i].name == SALA_BD_VARNAME) {
            for (let j = 0; j < form[i].length; j++) {
                if (form[i][j].innerText == row.getElementsByClassName('valor-' + SALA_VARNAME)[0].innerText) {
                    form[i].value = form[i][j].value;
                }
            }
        }
    }
}