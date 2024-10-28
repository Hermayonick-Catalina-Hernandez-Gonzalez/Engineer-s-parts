function verificar() {
    var contra = document.getElementById("password");
    var confirm_contra = document.getElementById("conf_password");
    confirm_contra.style.background = (contra.value != confirm_contra.value) ? "#c41616" : "#ededed99";
}