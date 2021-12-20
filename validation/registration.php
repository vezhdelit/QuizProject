<?php
    require_once 'config.php';

    $login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_EMAIL);
    $pass = filter_var(trim($_POST['pass']),FILTER_SANITIZE_STRING);

    global $db;
    $query = "INSERT INTO `users` (`login`, `email`, `pass`) VALUES('$login','$email','$pass')";
    mysqli_query($db, $query);
    header("Location: /");
?>