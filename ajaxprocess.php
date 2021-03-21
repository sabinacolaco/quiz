<?php
include('config/Quiz.class.php');
$quiz = new Quiz();
if (isset($_POST) && !empty($_POST)) {
    echo $quiz->ajaxSendResult($_POST);
} else if (!empty($_GET)) {
    if (!empty($_GET['settimer']) && ($_GET['settimer'] == 'yes')) {
       echo $quiz->setTimer();
    }
    else {
        echo json_encode($quiz->getQuestionsOptions());
    }
}
?>