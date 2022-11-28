// const filtro_sala = document.getElementById(FILTROS['FILTRO_SALA_VARNAME']);
// const filtro_capacidad = document.getElementById(FILTROS['FILTRO_CAPACIDAD_VARNAME']);
// const filtro_disponibilidad = document.getElementById(FILTROS['FILTRO_DISPONIBILIDAD_VARNAME']);

// function enviarFiltros(currentURL, filtros) {
//     var filtros_finales = currentURL;

//     for (let i = 0; i < filtros.length; i++) {
//         if (i == 0) {
//             filtros_finales = filtros_finales + '?' + filtros[i].id + '=' + filtros[i].value;
//         } else {
//             filtros_finales = filtros_finales + '&' + filtros[i].id + '=' + filtros[i].value;
//         }
//     }

//     // console.log(filtros_finales)
//     window.location.href = filtros_finales;
// }

// function limpiarFiltros(currentURL) {
//     window.location.href = currentURL;
// }