const formulario_reserva = document.getElementById('form-reserva');
const form_items = formulario_reserva.children;
const boton_atras = document.getElementById('boton-cambio-atras');
const boton_siguiente = document.getElementById('boton-cambio-siguiente');
const mensaje_error = document.getElementById('mensaje-error');

function getFechaHoy() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;

    return today;
}

function setUpForm(form) {
    for (let i = 0; i < form.length; i++) {
        if (i > 0) {
            form[i].style.display = 'none';
        }
    }

    for (let i = 0; i < formulario_reserva.length; i++) {
        if (formulario_reserva[i].name == BD['FECHA']) {
            const today = getFechaHoy();

            formulario_reserva[i].min = today;
            // formulario_reserva[i].value = today;
        }
    }

    boton_atras.style.display = 'none';
}
setUpForm(formulario_reserva.children);

function validarForm() {
    for (let i = 0; i < formulario_reserva.length; i++) {
        if (getComputedStyle(formulario_reserva[i].parentElement).display != 'none') {
            if (formulario_reserva[i].name == BD['COMENSALES']) {
                // Comprobar si hay mesas disponibles con esa cantidad de comensales

                if (formulario_reserva[i].value < 1 || formulario_reserva[i] > 10) {
                    mensaje_error.innerText = 'No hay mesas para estos comensales';
                    return false;
                }

                let formData = new FormData;
                formData.append('INSTRUCCION', formulario_reserva[i].name);
                formData.append(formulario_reserva[i].name, formulario_reserva[i].value);

                const ajax = new XMLHttpRequest();

                ajax.open('POST', '../proc/ajax/validar_reserva.php');

                ajax.onload = function() {
                    if (ajax.status === 200) {
                        if (ajax.responseText == 'LIBRES') {
                            // Hay mesas libres
                            mensaje_error.innerText = '';
                        } else {
                            // No hay mesas libres
                            mensaje_error.innerText = 'No hay mesas para estos comensales';
                        }
                    }
                }

                ajax.send(formData);

                if (mensaje_error.innerText == '') {
                    return true;
                } else {
                    return false;
                }
            } else if (formulario_reserva[i].name == BD['FECHA']) {

            }
        }
    }

}

function cambiarSiguiente() {
    if (validarForm()) {
        for (let i = 0; i < formulario_reserva.length; i++) {
            if (getComputedStyle(formulario_reserva[i].parentElement).display != 'none') {
                let next_item = i + 1;
                // console.log(formulario_reserva[next_item])

                // Validar si el item al que estamos pasando es el último
                if (next_item == formulario_reserva.length) {
                    boton_siguiente.style.display = 'none';
                }

                // Validar que el usuario no esté intentando pasar a un item que no  existe
                if (next_item > formulario_reserva.length) {
                    return;
                }

                // Ejecutar funcion especifica para cada item cuando se muestra
                setUpItem(formulario_reserva[next_item].parentElement);

                // Esconder item
                formulario_reserva[i].parentElement.style.display = 'none';

                // Mostrar siguiente item
                formulario_reserva[next_item].parentElement.style.display = 'flex';

                // Mostrar boton Atrás
                boton_atras.style.display = 'block';

                return true;

            }
        }
    }
}

function validarFecha(e) {
    const fecha_picker = e.target;
    const horas_divs = fecha_picker.parentElement.getElementsByTagName('div');
    const comensales = document.getElementsByName(BD['COMENSALES'])[0];

    // Validar hora
    const fecha = fecha_picker.value;
    const fechaHoy = getFechaHoy();
    if (fecha < fechaHoy) {
        // No hay mesas libres
        mensaje_error.innerText = 'Fecha inválida';
        return false;
    }

    let formData = new FormData;
    formData.append('INSTRUCCION', fecha_picker.name);
    formData.append(fecha_picker.name, fecha_picker.value);
    formData.append(comensales.name, comensales.value);

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/validar_reserva.php');

    const select_hora_inicio = document.getElementsByName(BD['HORA_INICIO'])[0];
    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'NINGUNA') {
                // No hay mesas libres
                mensaje_error.innerText = 'No hay mesas disponibles para esta fecha';
                select_hora_inicio.parentElement.style.display = 'none'
            } else {
                // Hay mesas libres
                const horas = JSON.parse(ajax.responseText);
                let opcion_default = document.createElement('option');
                opcion_default.value = '';
                opcion_default.innerText = 'Hora';
                opcion_default.id = 'opcion_hora_default';
                select_hora_inicio.append(opcion_default)
                for (let i = 0; i < horas.length; i++) {
                    let opcion = document.createElement('option');
                    opcion.value = horas[i];
                    // Quitar los segundos de la hora
                    let hora = horas[i].split(':');
                    hora.pop();
                    hora = hora.join(':');
                    opcion.innerText = hora;
                    select_hora_inicio.append(opcion);
                }
                select_hora_inicio.addEventListener('input', validarHora);
                select_hora_inicio.parentElement.style.display = 'flex'
            }
        }
    }

    ajax.send(formData);
}

function validarHora(e) {
    const hora = e.target;

    // Quitar la opción por defecto
    const opcion_default = document.getElementById('opcion_hora_default');
    if (opcion_default != null) {
        opcion_default.remove();
    }

    // Recoger el select de horas y crear un array de opciones que contenga
    // las horas superiores a la hora introducida
    // hasta que se acaben las horas o 
    // haya un salto de hora mayor a 30mins, lo que significa
    // que no habrá mesas disponibles a esa hora
    const horas = document.getElementsByName(BD['HORA_INICIO'])[0];
    let horas_finales = [];

    loop1:
        for (let i = 0; i < horas.length; i++) {
            // hora[i] = 20:30:00 -> hora_int = 2030
            let horas_int = parseHourValue(horas[i].value);

            let hora_int = parseHourValue(hora.value);

            // Si las horas son mayores que la hora que he puesto el usuario
            if (horas_int > hora_int) {
                // Añadir horas al array hasta que nos quedemos sin horas o 
                // hasta que la siguiente hora no sea la hora i + 30 mins

                // Si la diferencia de horas es mayor de 30
                // o la siguiente hora no existe
                if (parseHourValue(horas[(i)].value) - parseHourValue(horas[i - 1].value) <= 70) {
                    // console.log(horas[i].value + ': ' + (parseHourValue(horas[(i)].value) - parseHourValue(horas[i - 1].value)))
                    horas_finales.push(horas[i].value);
                } else {
                    break loop1;
                }
            }
        }

    const select_horas_finales = document.getElementsByName(BD['HORA_FINAL'])[0];
    // Eliminar los valores ya existentes
    select_horas_finales.innerHTML = '';

    if (horas_finales.length > 0) {
        mensaje_error.innerText = '';

        for (let i = 0; i < horas_finales.length; i++) {
            let hora_texto = horas_finales[i].split(':');
            hora_texto.pop()
            hora_texto = hora_texto.join(':');

            let opcion_hora_final = document.createElement('option');
            opcion_hora_final.value = horas_finales[i];
            opcion_hora_final.innerText = hora_texto;

            select_horas_finales.append(opcion_hora_final);

            if (i == 0) {
                select_horas_finales.value = opcion_hora_final.value;
            }
        }

        boton_siguiente.classList.remove('disabled');

        select_horas_finales.parentElement.style.display = 'flex';
    } else {
        mensaje_error.innerText = 'No se puede reservar a esta hora';

        if (!boton_siguiente.classList.contains('disabled')) {
            boton_siguiente.classList.add('disabled');
        }

        select_horas_finales.parentElement.style.display = 'none';
    }
}

function parseHourValue(hour) {
    hour = hour.split(':');
    hour = parseInt(hour[0] + hour[1]);
    return hour;
}

function setUpItem(item) {
    if (item.id == "reserva-input-fecha_inicio") {
        // Esconder los pickers de las horas
        const horas_divs = item.getElementsByTagName('div');

        for (let i = 0; i < horas_divs.length; i++) {
            horas_divs[i].style.display = 'none';
        }

        // Deshabilitar botón de siguiente
        boton_siguiente.classList.add('disabled')
    }
}


// boton_atras.addEventListener('click', cambiarAnterior);
boton_siguiente.addEventListener('click', cambiarSiguiente);