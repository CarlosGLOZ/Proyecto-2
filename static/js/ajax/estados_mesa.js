function ocuparMesa() {
    const modal_form_ocupar = document.getElementById('modal-form-ocupar');
    const ocupar_inputs = modal_form_ocupar.getElementsByTagName('input');

    let formData = new FormData();
    // for (const key in filtros) {
    //     if (filtros[key] != '') {
    //         formData.append(key, filtros[key]);
    //     }
    // }

    for (let i = 0; i < ocupar_inputs.length; i++) {
        // console.log(ocupar_inputs[i])
        formData.append(ocupar_inputs[i].name, ocupar_inputs[i].value)
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/cambiar_estado_mesa.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            // console.log(ajax.responseText)
            if (ajax.responseText == 'OK') {
                listarMesas();
                cerrarModales();
            };
        }
    }

    ajax.send(formData);
}


function liberarMesa() {
    const modal_form_liberar = document.getElementById('modal-form-liberar');
    const liberar_inputs = modal_form_liberar.getElementsByTagName('input')

    let formData = new FormData();
    // for (const key in filtros) {
    //     if (filtros[key] != '') {
    //         formData.append(key, filtros[key]);
    //     }
    // }

    for (let i = 0; i < liberar_inputs.length; i++) {
        // console.log(ocupar_inputs[i])
        formData.append(liberar_inputs[i].name, liberar_inputs[i].value)
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/cambiar_estado_mesa.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            // console.log(ajax.responseText)
            if (ajax.responseText == 'OK') {
                listarMesas();
                cerrarModales();
            };
        }
    }

    ajax.send(formData);
}


function mantenimientoMesa() {
    const modal_form_mantenimiento = document.getElementById('modal-form-mantenimiento');
    const mantenimiento_inputs = modal_form_mantenimiento.getElementsByTagName('input')

    let formData = new FormData();
    // for (const key in filtros) {
    //     if (filtros[key] != '') {
    //         formData.append(key, filtros[key]);
    //     }
    // }

    for (let i = 0; i < mantenimiento_inputs.length; i++) {
        // console.log("inputs: " + ocupar_inputs[i].name + " - " + ocupar_inputs[i].value)
        formData.append(mantenimiento_inputs[i].name, mantenimiento_inputs[i].value)
    }

    const ajax = new XMLHttpRequest();

    ajax.open('POST', '../proc/ajax/cambiar_estado_mesa.php');

    ajax.onload = function() {
        if (ajax.status === 200) {
            // console.log(ajax.responseText)
            if (ajax.responseText == 'OK') {
                listarMesas();
                cerrarModales();
            };
        }
    }

    ajax.send(formData);
}