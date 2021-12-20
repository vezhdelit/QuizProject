<?php
    setcookie('user',$user['login'], time() - 3600 * 2, "/");
    header("Location: /");
?>