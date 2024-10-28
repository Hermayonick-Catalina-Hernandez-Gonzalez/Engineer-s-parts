document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const fechaNacimientoInput = document.getElementById('fecha-nacimiento');
    const errorLabel = document.getElementById('error-label');

    form.addEventListener('submit', function (event) {
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        if (!esMayorDeEdad(fechaNacimiento)) {
            event.preventDefault(); // Detiene el envío del formulario
            errorLabel.textContent = "Debes tener al menos 18 años para registrarte.";
        }
    });

    function esMayorDeEdad(fechaNacimiento) {
        const hoy = new Date();
        const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        const mes = hoy.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            return edad - 1;
        }
        return edad >= 18;
    }
});
