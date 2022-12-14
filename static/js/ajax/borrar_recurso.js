function borrarRecurso(numero) {
    let formData = new FormData();
    formData.append(NUMERO_BD_VARNAME, numero);

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/delete_recurso.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'OK') {
                Swal.fire({
                    title: 'Recurso Borrado',
                    icon: 'success',
                }).then((result) => {
                    listarRecursos();
                })
            } else {
                Swal.fire({
                    title: 'No se ha podido borrar el recurso',
                    icon: 'error',
                }).then((result) => {
                    listarRecursos();
                })
                console.log(ajax.responseText)
            }
        } else {
            Swal.fire({
                title: 'No se ha podido borrar el recurso',
                icon: 'error',
            }).then((result) => {
                listarRecursos();
            })
            console.log('ajax status: ' + ajax.status)
        }
    }

    ajax.send(formData);
}

function borrarEmpleado(DNI) {
    let formData = new FormData();
    formData.append(DNI_BD_VARNAME, DNI);

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/delete_empleado.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'OK') {
                Swal.fire({
                    title: 'Empleado Borrado',
                    icon: 'success',
                }).then((result) => {
                    listarEmpleados();
                })
            } else {
                Swal.fire({
                    title: 'No se ha podido borrar el empleado',
                    icon: 'error',
                }).then((result) => {
                    listarEmpleados();
                })
                console.log(ajax.responseText)
            }
        } else {
            Swal.fire({
                title: 'No se ha podido borrar el empleado',
                icon: 'error',
            }).then((result) => {
                listarEmpleados();
            })
            console.log('ajax status: ' + ajax.status)
        }
    }

    ajax.send(formData);
}