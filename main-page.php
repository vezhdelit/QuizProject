<?php
ini_set("display_errors", 1);
error_reporting(-1);
require_once 'validation/config.php';
require_once 'validation/functions.php';

if( isset($_POST['test']) ){
    $test = (int)$_POST['test'];
    unset($_POST['test']);
    $result = get_correct_answers($test);
    if( !is_array($result) ) exit('Error!');
    // отримуємо дані про тест
    $test_all_data = get_test_data($test);
    // 1 - масив питань/відповідей, 2 - правильні відповіді, 3 - відповіді користувача
    $test_all_data_result = get_test_data_result($test_all_data, $result, $_POST);
    // print_r($_POST);
    // print_r($result);
    // print_r($test_all_data_result);
    echo print_result($test_all_data_result);
    die;
}

// отримуємо список тестів
$tests = get_tests();

if( isset($_GET['test']) ){
    setcookie('test_id',(int)$_GET['test'], time() + 3600 * 2, "/");

    $test_id = (int)$_GET['test'];
    $test_data = get_test_data($test_id);
    if( is_array($test_data) ){
        $count_questions = count($test_data);
        $pagination = pagination($count_questions, $test_data);
    }
}
if( isset($_GET['leaderboard']) ){
    $leaderboard_id = (int)$_GET['leaderboard'];
    $leaderboard_data = get_leaderboard_data($leaderboard_id);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="scripts.js"></script>
</head>
<body>

<div class="wrap">
    <?php require_once 'blocks/header.php'; ?>
    <?php if( $tests ): ?>
        <h3>Tests:</h3>
        <?php foreach($tests as $test): ?>
            <a href="?leaderboard=<?=$test['id']?>" class="link-warning">Leaderboard</a>
            <p><a href="?test=<?=$test['id']?>"><?=$test['test_name']?></a></p>
        <?php endforeach; ?>

        <br><hr><br>
        <div class="content">
            <?php if( isset($test_data) ): ?>

                <p>Total questions in this test: <?=$count_questions?></p>
                <?=$pagination?>
                <span class="none" id="test-id"><?=$test_id?></span>

                <div class="test-data">

                    <?php foreach($test_data as $id_question => $item): // отримуємо кожне конкретне питання + відповіді ?>

                        <div class="question" data-id="<?=$id_question?>" id="question-<?=$id_question?>">

                            <?php foreach($item as $id_answer => $answer): // проходимосс по масиву питання/відповіді ?>

                                <?php if( !$id_answer ): // виводимо питання ?>
                                    <p class="q"><?=$answer?></p>
                                <?php else: // виводимо варіанти відповідей ?>

                                    <p class="a">
                                        <input type="radio" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
                                        <label for="answer-<?=$id_answer?>"><?=$answer?></label>
                                    </p>

                                <?php endif; // $id_answer ?>

                            <?php endforeach; // $item ?>

                        </div> <!-- .question -->

                    <?php endforeach; // $test_data ?>

                </div> <!-- .test-data -->

                <div class="buttons">
                    <button class="center btn btn-primary" id="btn">Finish test</button>
                </div>

            <?php elseif( isset($leaderboard_data) ): // isset($test_data) ?>
                <?= $leaderboard_data?>
            <?php else: // isset($test_data) ?>
                Firstly, please choose the test
            <?php endif; // isset($test_data) ?>


        </div> <!-- .content -->

    <?php else: // $tests ?>
        <h3>No tests enabled</h3>
    <?php endif; // $tests ?>

</div> <!-- .wrap -->

</body>
</html>