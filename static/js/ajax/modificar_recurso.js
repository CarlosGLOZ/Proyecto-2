function modRecurso() {
    const form = document.getElementById('modal-form-ocupar');

    let formData = new FormData();
    let num_mesa;
    for (let i = 0; i < form.length; i++) {
        if (form[i].name == NUMERO_BD_VARNAME) {
            num_mesa = form[i].value;
        }
        formData.append(form[i].name, form[i].value);
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/mod_recurso.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'OK') {
                Swal.fire({
                    title: 'Mesa ' + num_mesa + ' modificada',
                    icon: 'success',
                }).then((result) => {
                    listarRecursos();
                    cerrarModales();
                })
            } else {
                Swal.fire({
                    title: 'No se ha podido modificar el recurso',
                    icon: 'error',
                }).then((result) => {
                    listarRecursos();
                })
            }
        } else {
            Swal.fire({
                title: 'No se ha podido modificar el recurso',
                icon: 'error',
            }).then((result) => {
                listarRecursos();
            })
        }
    }

    ajax.send(formData);
}

function modEmpleado() {

    const form = document.getElementById('modal-form-ocupar');

    let formData = new FormData();
    let nombre_emp;
    for (let i = 0; i < form.length; i++) {
        if (form[i].name == NOMBRE_BD_VARNAME) {
            nombre_emp = form[i].value;
        }
        formData.append(form[i].name, form[i].value);
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/mod_empleado.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'OK') {
                Swal.fire({
                    title: 'Empleado ' + nombre_emp + ' modificado',
                    icon: 'success',
                }).then((result) => {
                    listarEmpleados();
                    cerrarModales();
                })
            } else {
                Swal.fire({
                    title: 'No se ha modificar crear el empleado',
                    icon: 'error',
                }).then((result) => {
                    listarEmpleados();
                })
            }
        } else {
            Swal.fire({
                title: 'No se ha modificar crear el empleado',
                icon: 'error',
            }).then((result) => {
                listarEmpleados();
            })
        }
    }

    ajax.send(formData);
}