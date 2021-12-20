<?php
    require_once 'config.php';

    $login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']),FILTER_SANITIZE_STRING);

    global $db;
    $query = "SELECT * FROM `users`
     WHERE `login` = '$login' AND `pass` = '$pass'";
    $res = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($res);
    if(count($user) == 0){
        echo "No user found";
        die;
    }
    setcookie('user',$user['login'], time() + 3600 * 2, "/");
    header("Location: /");
?>