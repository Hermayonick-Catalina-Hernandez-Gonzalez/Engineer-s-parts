const password = document.getElementById("password");
const confirmPass = document.getElementById("confirmPass");
const estadoConfirm = document.getElementById("coincidencia");

confirmPass.addEventListener("input", function (e) {
    if (password.value === confirmPass.value) {
        estadoConfirm.style.color = "#59F23A";
        estadoConfirm.innerHTML = "Si es igual";
    } else {
        estadoConfirm.style.color = "red";
        estadoConfirm.innerHTML = "No es igual";
    }
});