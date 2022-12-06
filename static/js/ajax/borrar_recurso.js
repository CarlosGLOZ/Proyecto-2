function borrarRecurso(numero) {
    let formData = new FormData();
    formData.append(NUMERO_BD_VARNAME, numero);

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/delete_recurso.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            if (ajax.responseText == 'OK') {
                alert('Recurso Eliminado');
                listarRecursos();
            } else {
                console.log(ajax.responseText)
            }
        } else {
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
                alert('Recurso Eliminado');
                listarEmpleados();
            } else {
                console.log(ajax.responseText)
            }
        } else {
            console.log('ajax status: ' + ajax.status)
        }
    }

    ajax.send(formData);
}