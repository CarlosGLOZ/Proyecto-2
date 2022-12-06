if (form_crear_recurso == null || form_crear_recurso == undefined) {
    const form_crear_recurso = document.getElementById("form-crear-recurso");
}


if (form_crear_recurso != undefined) {
    form_crear_recurso.addEventListener('submit', (e) => {
        e.preventDefault();

        if (validarFormularioCrearRecurso()) {

            const inputs = form_crear_recurso.getElementsByTagName('input');
            const selects = form_crear_recurso.getElementsByTagName('select');

            let formData = new FormData();
            // Añadir datos de los inputs
            for (let i = 0; i < inputs.length; i++) {
                formData.append(inputs[i].name, inputs[i].value);
            }
            // Añadir datos de los selects
            for (let i = 0; i < selects.length; i++) {
                formData.append(selects[i].name, selects[i].value);
            }

            const ajax = new XMLHttpRequest();

            ajax.open('POST', '../proc/ajax/add_recurso.php');

            ajax.onload = function() {
                if (ajax.status === 200) {
                    if (ajax.responseText == 'OK') {
                        listarRecursos();
                    }
                }
            }

            ajax.send(formData);
        }
    });
}


if (form_crear_empleado == null || form_crear_empleado == undefined) {
    const form_crear_empleado = document.getElementById("form-crear-empleado");
}

if (form_crear_empleado != undefined) {

    form_crear_empleado.addEventListener('submit', (e) => {
        e.preventDefault();

        if (validarFormularioCrearEmpleado()) {

            const inputs = form_crear_empleado.getElementsByTagName('input');
            const selects = form_crear_empleado.getElementsByTagName('select');

            let formData = new FormData();
            // Añadir datos de los inputs
            for (let i = 0; i < inputs.length; i++) {
                formData.append(inputs[i].id, inputs[i].value);
            }
            // Añadir datos de los selects
            for (let i = 0; i < selects.length; i++) {
                formData.append(selects[i].id, selects[i].value);
            }

            const ajax = new XMLHttpRequest();

            ajax.open('POST', '../proc/ajax/add_empleado.php');

            ajax.onload = function() {
                if (ajax.status === 200) {
                    // console.log(ajax.responseText)
                    if (ajax.responseText == 'OK') {
                        alert('Empleado creado')
                        listarEmpleados();
                    }
                }
            }

            ajax.send(formData);
        }
    });
}