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

function getKeyByValue(array, value) {
    for (const key in array) {
        if (array[key] == value) {
            return key;
        }
    }
}

function listarMesas() {
    const filtro_sala = document.getElementsByName(FILTROS['FILTRO_SALA_VARNAME'])[0];
    const filtro_capacidad = document.getElementById(FILTROS['FILTRO_CAPACIDAD_VARNAME']);
    const filtro_disponibilidad = document.getElementById(FILTROS['FILTRO_DISPONIBILIDAD_VARNAME']);
    // console.log('listando')
    let filtros = {}

    filtros[FILTROS['FILTRO_SALA_VARNAME']] = filtro_sala.value;
    filtros[FILTROS['FILTRO_CAPACIDAD_VARNAME']] = filtro_capacidad.value;
    filtros[FILTROS['FILTRO_DISPONIBILIDAD_VARNAME']] = filtro_disponibilidad.value;


    const contenedor_mesas = document.getElementById('cont-mesas');

    if (!filtros.hasOwnProperty(FILTROS['FILTRO_SALA_VARNAME'])) {
        filtros[FILTROS['FILTRO_SALA_VARNAME']] = 1;
    }

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

function listarRecursos() {
    const form_filtros = document.getElementById('form-filtros');

    let formData = new FormData;

    for (let i = 0; i < form_filtros.length; i++) {
        if (form_filtros[i].id.split('_')[0] == 'filtro' && form_filtros[i].value != '') {
            formData.append(form_filtros[i].id, form_filtros[i].value);
        }
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/get_recursos.php')

    ajax.onload = function() {
        if (ajax.status === 200) {
            // no se por qué, si solo lo parseas una vez te devuelve una string
            let recursos = JSON.parse(JSON.parse(ajax.response));
            const tabla_mesas = document.getElementById('cont-mesas').getElementsByTagName('table')[0];
            // Borrar contenidos de la tabla actual
            tabla_mesas.innerHTML = "<thead><tr></tr></thead><tbody></tbody>";

            const thead = tabla_mesas.tHead.getElementsByTagName('tr')[0];
            const tbody = tabla_mesas.getElementsByTagName('tbody')[0];


            // Escribir headers
            const headers = []
            for (const key in recursos[0]) {
                let th = document.createElement('th');
                th.scope = "col";
                th.innerText = key;
                thead.appendChild(th);
                headers.push(key)
            }

            for (let i = 0; i < recursos.length; i++) {
                const tr = document.createElement('tr');
                let cont = 0;

                // Añadir campos mesa
                for (let j = 0; j < headers.length; j++) {
                    if (cont == 0) {
                        let th = document.createElement('th');
                        th.scope = 'row';
                        th.innerText = recursos[i][headers[j]];
                        // Añadir clase para boton modificar
                        th.classList.add('valor-' + headers[j]);
                        tr.appendChild(th)
                        cont++;
                    } else {
                        let td = document.createElement('td');
                        let contenido;
                        if (headers[j] == ESTADO_VARNAME) {
                            contenido = ESTADOS[recursos[i][headers[j]].split(' - ')[0]];
                            tr.classList.add('estado-' + contenido);
                            // console.log(td.parentNode);
                        } else {
                            contenido = recursos[i][headers[j]].split(' - ')[0];
                        }
                        td.innerText = contenido
                            // Añadir clase para boton modificar
                        td.classList.add('valor-' + headers[j]);
                        tr.appendChild(td)
                    }
                }

                // Añadir boton modificar
                const td_boton_modificar = document.createElement('td');
                const boton_modificar = document.createElement('button');
                boton_modificar.classList.add('btn');
                boton_modificar.classList.add('btn-warning');
                boton_modificar.innerText = "Modificar";
                boton_modificar.addEventListener('click', (e) => {
                    mostrarModalModificar(tr);
                });
                td_boton_modificar.appendChild(boton_modificar);
                tr.appendChild(td_boton_modificar);

                // Añadir boton borrar
                const td_boton_borrar = document.createElement('td');
                const boton_borrar = document.createElement('button');
                boton_borrar.classList.add('btn');
                boton_borrar.classList.add('btn-danger');
                boton_borrar.innerText = "Borrar";
                boton_borrar.addEventListener('click', (e) => {
                    console.log('borrando ' + tr.children[getKeyByValue(headers, NUMERO_VARNAME)].innerText)
                    borrarRecurso(tr.children[getKeyByValue(headers, NUMERO_VARNAME)].innerText);
                });
                td_boton_borrar.appendChild(boton_borrar);
                tr.appendChild(td_boton_borrar);


                tbody.appendChild(tr);
            }

        }
    }

    ajax.send(formData)
}

function listarEmpleados() {
    const form_filtros = document.getElementById('form-filtros');
    let formData = new FormData;

    for (let i = 0; i < form_filtros.length; i++) {
        if (form_filtros[i].id.split('_')[0] == 'filtro' && form_filtros[i].value != '') {
            // console.log(form_filtros[i].id + ' - ' + form_filtros[i].value);
            formData.append(form_filtros[i].id, form_filtros[i].value);
        }
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/get_empleados.php')

    ajax.onload = function() {
        if (ajax.status === 200) {
            // no se por qué, si solo lo parseas una vez te devuelve una string
            let recursos = JSON.parse(JSON.parse(ajax.response));
            const tabla_mesas = document.getElementById('cont-mesas').getElementsByTagName('table')[0];
            // Borrar contenidos de la tabla actual
            tabla_mesas.innerHTML = "<thead><tr></tr></thead><tbody></tbody>";

            const thead = tabla_mesas.tHead.getElementsByTagName('tr')[0];
            const tbody = tabla_mesas.getElementsByTagName('tbody')[0];


            // Escribir headers
            const headers = []
            for (const key in recursos[0]) {
                let th = document.createElement('th');
                th.scope = "col";
                th.innerText = key;
                thead.appendChild(th);
                headers.push(key)
            }

            for (let i = 0; i < recursos.length; i++) {
                const tr = document.createElement('tr');
                let cont = 0;

                // Añadir campos mesa
                for (let j = 0; j < headers.length; j++) {
                    if (cont == 0) {
                        let th = document.createElement('th');
                        th.scope = 'row';
                        th.innerText = recursos[i][headers[j]];
                        // Añadir clase para boton modificar
                        th.classList.add('valor-' + headers[j]);
                        tr.appendChild(th)
                        cont++;
                    } else {
                        let td = document.createElement('td');
                        // let contenido;
                        let contenido = recursos[i][headers[j]].split(' - ')[0];

                        td.innerText = contenido
                            // Añadir clase para boton modificar
                        td.classList.add('valor-' + headers[j]);
                        tr.appendChild(td)
                    }
                }

                // Añadir boton modificar
                const td_boton_modificar = document.createElement('td');
                const boton_modificar = document.createElement('button');
                boton_modificar.classList.add('btn');
                boton_modificar.classList.add('btn-warning');
                boton_modificar.innerText = "Modificar";
                boton_modificar.addEventListener('click', (e) => {
                    mostrarModalModificar(tr);
                });
                td_boton_modificar.appendChild(boton_modificar);
                tr.appendChild(td_boton_modificar);

                // Añadir boton borrar
                const td_boton_borrar = document.createElement('td');
                const boton_borrar = document.createElement('button');
                boton_borrar.classList.add('btn');
                boton_borrar.classList.add('btn-danger');
                boton_borrar.innerText = "Borrar";
                boton_borrar.addEventListener('click', (e) => {
                    console.log('borrando ' + tr.children[getKeyByValue(headers, NUMERO_VARNAME)].innerText)
                    borrarRecurso(tr.children[getKeyByValue(headers, NUMERO_VARNAME)].innerText);
                });
                td_boton_borrar.appendChild(boton_borrar);
                tr.appendChild(td_boton_borrar);


                tbody.appendChild(tr);
            }

        }
    }

    ajax.send(formData)
}

const titulo = document.getElementsByTagName('title')[0].innerText
if (titulo == 'Mesas') {
    listarMesas();
} else if (titulo == 'Recursos') {
    listarRecursos()
} else if (titulo == 'Empleados') {
    listarEmpleados();
}