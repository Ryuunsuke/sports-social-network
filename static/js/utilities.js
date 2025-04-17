window.onclick = function(event) {
    if (event.target.classList.contains('loginmodal')) {
        document.getElementById('id01').style.display = "none";
    }
    if (event.target.classList.contains('registermodal')) {
        document.getElementById('id02').style.display = "none";
    }
}

function closemodals(){
    document.getElementById('id01').style.display='none';
    document.getElementById('id02').style.display='none';
}

function showRegister() {
    document.getElementById('id01').style.display = 'none'; // Hide login modal
    document.getElementById('id02').style.display = 'block'; // Show register modal
}

function showLogin() {
    document.getElementById('id02').style.display = 'none'; // Hide register modal
    document.getElementById('id01').style.display = 'block'; // Show login modal
}
