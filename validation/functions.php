<?php
/**
 * розгортка масива
 **/
function print_arr($arr){
    echo '<pre>'  . print_r($arr, true) . '</pre>';
}

/**
 * отримання списка тестів
 **/
function get_tests(){
    global $db;
    $query = "SELECT * FROM test WHERE enable = '1'";
    $res = mysqli_query($db, $query);
    if(!$res) return false;
    $data = array();
    while($row = mysqli_fetch_assoc($res)){
        $data[] = $row;
    }
    return $data;
}

/**
 * отримання данних теста
 **/
function get_test_data($test_id){
    if( !$test_id ) return;
    global $db;
    $query = "SELECT q.question, q.parent_test, a.id, a.answer, a.parent_question
		FROM questions q
		LEFT JOIN answers a
			ON q.id = a.parent_question
		LEFT JOIN test
			ON test.id = q.parent_test
				WHERE q.parent_test = $test_id AND test.enable = '1'";
    $res = mysqli_query($db, $query);
    $data = null;
    while($row = mysqli_fetch_assoc($res)){
        if( !$row['parent_question'] ) return false;
        $data[$row['parent_question']][0] = $row['question'];
        $data[$row['parent_question']][$row['id']] = $row['answer'];
    }
    return $data;
}

/**
 * отримання і вивід рейтингу
 **/

function get_leaderboard_data($test_id){
    global $db;
    $query = "SELECT * FROM leaderboard
                WHERE leaderboard.test_id = $test_id 
                ORDER BY `score` DESC";
    $res = mysqli_query($db, $query);
    $data = null;
    while($row = mysqli_fetch_assoc($res)){
        $data[] = $row;
    }
    $print_res = '<div>';
    $print_res .= '<h2>Leaderboard:</h2>';
    foreach ($data as $row) {
        $print_res .= "<p>Test: {$row['test_id']} | User: {$row['user']} | Score: {$row['score']}</p>";
    }
    $print_res .= '</div>';

    return $print_res;
}

/**
 * отримання id питання/відповідь
 **/
function get_correct_answers($test){
    if( !$test ) return false;
    global $db;
    $query = "SELECT q.id AS question_id, a.id AS answer_id
		FROM questions q
		LEFT JOIN answers a
			ON q.id = a.parent_question
		LEFT JOIN test
			ON test.id = q.parent_test
				WHERE q.parent_test = $test AND a.correct_answer = '1' AND test.enable = '1'";
    $res = mysqli_query($db, $query);
    $data = null;
    while($row = mysqli_fetch_assoc($res)){
        $data[$row['question_id']] = $row['answer_id'];
    }
    return $data;
}

/**
 * будуємо пагінацію
 **/
function pagination($count_questions, $test_data){
    $keys = array_keys($test_data);
    $pagination = '<div class="pagination">';
    for($i = 1; $i <= $count_questions; $i++){
        $key = array_shift($keys);
        if( $i == 1 ){
            $pagination .= '<a class="nav-active" href="#question-' . $key . '">' . $i . '</a>';
        }else{
            $pagination .= '<a href="#question-' . $key . '">' . $i . '</a>';
        }
    }
    $pagination .= '</div>';
    return $pagination;
}

/**
 * результат
 * 1 - масив питання/відповіді
 * 2 - правильні питання
 * 3 - відповіді користувача
 **/
function get_test_data_result($test_all_data, $result, $POST){
    // заповнюємо масив $test_all_data правильными відповідями и данимипро питання без даної відповіді
    foreach($result as $q => $a){
        $test_all_data[$q]['correct_answer'] = $a;
        // додаємо в масив дані про питання без даної відповіді
        if( !isset($POST[$q]) ){
            $test_all_data[$q]['incorrect_answer'] = 0;
        }
    }

    // додаємо неправильну відповідь, якщо такий був
    foreach($POST as $q => $a){
        // видалимо "недоречні" значення відповідей
        if( !isset($test_all_data[$q]) ){
            unset($POST[$q]);
            continue;
        }

        // якщо є "недоречні" значення відповідей
        if( !isset($test_all_data[$q][$a]) ){
            $test_all_data[$q]['incorrect_answer'] = 0;
            continue;
        }

        // додаємо неправильну відповідь
        if( $test_all_data[$q]['correct_answer'] != $a ){
            $test_all_data[$q]['incorrect_answer'] = $a;
        }
    }
    return $test_all_data;
}

/**
 * вивід результату
 **/
function print_result($test_all_data_result){
    // змінні результатів:
    $all_count = count($test_all_data_result); // загальна кількість питань
    $correct_answer_count = 0; // кількість правильних питань
    $incorrect_answer_count = 0; // кількість неправильних питань
    $percent = 0; // відсоток правильних

    // підрахунок результатів
    foreach($test_all_data_result as $item){
        if( isset($item['incorrect_answer']) ) $incorrect_answer_count++;
    }
    $correct_answer_count = $all_count - $incorrect_answer_count;
    $percent = round( ($correct_answer_count / $all_count * 100), 2);

    // вивід результатів
    $print_res = '<div class="questions">';
    $print_res .= '<div class="count-res">';
    $print_res .= "<p>Total questions: <b>{$all_count}</b></p>";
    $print_res .= "<p>Correct answers: <b>{$correct_answer_count}</b></p>";
    $print_res .= "<p>Incorrect answers: <b>{$incorrect_answer_count}</b></p>";
    $print_res .= "<p>Success percenatage: <b>{$percent} %</b></p>";
    $print_res .= '</div>';	// .count-res

    // вивід кожного теста, з маркуванням результату...
    foreach($test_all_data_result as $id_question => $item){ // отримуємо питання + відрповіді
        $correct_answer = $item['correct_answer'];
        $incorrect_answer = null;
        if( isset($item['incorrect_answer']) ){
            $incorrect_answer = $item['incorrect_answer'];
            $class = 'question-res error';
        }else{
            $class = 'question-res ok';
        }
        $print_res .= "<div class='$class'>";
        foreach($item as $id_answer => $answer){ // проходимось по масиву відповідей, і відповідно маркуємо відповіді
            if( $id_answer === 0 ){
                // питання
                $print_res .= "<p class='q'>$answer</p>";
            }elseif( is_numeric($id_answer) ){
                // відповідь
                if( $id_answer == $correct_answer ){
                    // якщо правильно
                    $class = 'a ok2';
                }elseif( $id_answer == $incorrect_answer ){
                    // якщо неправильно
                    $class = 'a error2';
                }else{
                    $class = 'a';
                }
                $print_res .= "<p class='$class'>$answer</p>";
            }
        }
        $print_res .= '</div>'; // .question-res
    }

    $print_res .= '</div>'; // .questions

    global $db;
    $test_id = $_COOKIE['test_id'];
    $user = $_COOKIE['user'];
    $query = "INSERT INTO `leaderboard` (`test_id`, `user`, `score`) VALUES('$test_id','$user','$percent')";
    mysqli_query($db, $query);

    return $print_res;
}