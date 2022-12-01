if (form_crear_recurso == null) {
    const form_crear_recurso = document.getElementById("form-crear-recurso");
}

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