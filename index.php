<?php
    if(isset($_COOKIE['user'])){
        header("Location: /main-page.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Система тестирования</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="scripts.js"></script>
</head>
<body>

<div class="wrap">
    <div class="container row">
        <div class="col">
            <h3>Registration form</h3><br>
            <form action="validation/registration.php" method="post">
                <input type="text" name="login" class="form-control" placeholder="Enter login" required autocomplete="off"><br>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required autocomplete="off"><br>
                <input type="password" name="pass" class="form-control" placeholder="Enter password" required><br>
                <input type="submit" value="Redistration" class="btn btn-primary">
            </form>
        </div>
        <div class="col">
            <h3>Authorization form</h3><br>
            <form action="validation/auth.php" method="post">
                <input type="text" name="login" class="form-control" placeholder="Enter login" required autocomplete="off"><br>
               <input type="password" name="pass" class="form-control" placeholder="Enter password" required><br>
                <input type="submit" value="Autorization" class="btn btn-primary">
            </form>
        </div>
    </div> <!-- .content -->
</div> <!-- .wrap -->

</body>
</html>