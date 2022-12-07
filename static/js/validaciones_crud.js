const form_crear_recurso = document.getElementById("form-crear-recurso");
const form_crear_boton = document.getElementById("boton-form-crear-recurso");
const form_crear_error = document.getElementById("error-val-form");

function validarFormularioCrearRecurso() {
    const form_crear_recurso_comensales = document.getElementById('form_crear_' + CAPACIDAD_BD_VARNAME);
    if (form_crear_recurso_comensales.value < 1 || form_crear_recurso_comensales.value > 10) {
        form_crear_error.style.display = 'inherit';
        form_crear_boton.classList.add('disabled');
        return false;
    } else {
        form_crear_error.style.display = 'none';
        form_crear_boton.classList.remove('disabled');
    }

    return true;
}

const form_crear_empleado = document.getElementById("form-crear-empleado");
const form_crear_empleado_boton = document.getElementById("boton-form-crear-empleado");

function validarFormularioCrearEmpleado() {
    let val = true;
    for (let i = 0; i < form_crear_empleado.length; i++) {
        // Si el elemento tiene la clase form-dni
        if (Object.values(form_crear_empleado[i].classList).includes("form-dni")) {
            let value = form_crear_empleado[i].value;
            if (!validarDNI(value)) {
                val = false;
            }
        } else if (Object.values(form_crear_empleado[i].classList).includes("form-email")) {
            let expresion_regular_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

            if (!expresion_regular_email.test(form_crear_empleado[i].value)) {
                val = false;
            }
        }
    }

    if (val) {
        form_crear_error.style.display = 'none';
        form_crear_empleado_boton.classList.remove('disabled');
        return true;
    } else {
        form_crear_error.style.display = 'inherit';
        // form_crear_empleado_boton.classList.add('disabled');
        return false;

    }
}

function validarDNI(dni) {
    var numero
    var letr
    var letra
    var expresion_regular_dni

    expresion_regular_dni = /^\d{8}[a-zA-Z]$/;

    if (expresion_regular_dni.test(dni) == true) {
        numero = dni.substr(0, dni.length - 1);
        letr = dni.substr(dni.length - 1, 1);
        numero = numero % 23;
        letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
        letra = letra.substring(numero, numero + 1);
        if (letra != letr.toUpperCase()) {
            return false;
        } else {
            return true;;
        }
    } else {
        return false;
    }
}

const form_modificar_empleado = document.getElementById('modal-form-ocupar');
const boton_guardar_modificar = document.getElementById('modal-form-boton-mod-guardar');

function validarFormularioModificarEmpleado() {
    console.log(form_modificar_empleado);
    console.log(boton_guardar_modificar)

    let val = true;
    for (let i = 0; i < form_modificar_empleado.length; i++) {
        // Si el elemento tiene la clase form-dni
        if (form_modificar_empleado[i].name == "dni_empleado") {
            let value = form_modificar_empleado[i].value;
            if (!validarDNI(value)) {
                val = false;
            }
        } else if (form_modificar_empleado[i].name == "email_empleado") {
            let expresion_regular_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

            if (!expresion_regular_email.test(form_modificar_empleado[i].value)) {
                val = false;
            }
        }
    }

    if (val) {
        boton_guardar_modificar.classList.remove('disabled');
        return true;
    } else {
        boton_guardar_modificar.classList.add('disabled');
        return false;
    }
}