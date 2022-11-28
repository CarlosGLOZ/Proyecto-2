const filtro_sala = document.getElementsByName(FILTROS['FILTRO_SALA_VARNAME'])[0];
const filtro_capacidad = document.getElementById(FILTROS['FILTRO_CAPACIDAD_VARNAME']);
const filtro_disponibilidad = document.getElementById(FILTROS['FILTRO_DISPONIBILIDAD_VARNAME']);

// filtro_sala.addEventListener('change', () => {
//     console.log('changed')
//     listarMesas();
// })
// filtro_capacidad.addEventListener('change', () => {
//     console.log('changed')
//     listarMesas();
// })
// filtro_disponibilidad.addEventListener('change', () => {
//     console.log('changed')
//     listarMesas();
// })

function listarMesas() {
    // console.log('listando')
    let filtros = {}

    filtros[FILTROS['FILTRO_SALA_VARNAME']] = filtro_sala.value;
    filtros[FILTROS['FILTRO_CAPACIDAD_VARNAME']] = filtro_capacidad.value;
    filtros[FILTROS['FILTRO_DISPONIBILIDAD_VARNAME']] = filtro_disponibilidad.value;


    const contenedor_mesas = document.getElementById('cont-mesas');

    if (!filtros.hasOwnProperty(FILTROS['FILTRO_SALA_VARNAME'])) {
        filtros[FILTROS['FILTRO_SALA_VARNAME']] = 1;
    }

    // for (const key in filtros) {
    //     console.log(key + ' - ' + filtros[key])
    // }

    let formData = new FormData();
    for (const key in filtros) {
        if (filtros[key] != '') {
            formData.append(key, filtros[key]);
        }
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/get_mesas.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            contenedor_mesas.innerHTML = ajax.responseText;
        }
    }

    ajax.send(formData);
}

function limpiarFiltros() {
    filtro_capacidad.value = filtro_capacidad[0];
    filtro_disponibilidad.value = filtro_disponibilidad[0];
    listarMesas();
}

listarMesas()