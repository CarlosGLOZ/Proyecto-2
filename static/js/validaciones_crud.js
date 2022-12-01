const form_crear_recurso = document.getElementById("form-crear-recurso");
const form_crear_error = document.getElementById("error-val-form");
const form_crear_boton = document.getElementById("boton-form-crear-recurso");

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