if (!empty($_SESSION['success'])){
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 2000); 
}
