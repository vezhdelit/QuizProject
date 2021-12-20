<?php

define("HOST", "localhost");
define("USER", "mysql");
define("PASS", "");
define("DB", "quiz-platform");

$db = mysqli_connect(HOST, USER, PASS, DB) or die("Error. No connectiont to database");
 mysqli_set_charset($db, 'utf8') or die("Error, wrong charset coding");

// echo "Config works!";