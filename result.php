<?php
include('config/Quiz.class.php');
$quiz = new Quiz();
$questsArr = [];
$wrongPercentage = 0;
if (isset($_SESSION)) {
    if (isset($_SESSION['questions']) && (isset($_SESSION['answers']))) {    
        $questsArr = (isset($_SESSION['questions']) && !empty($_SESSION['questions'])) ? $_SESSION['questions'] : '';    
        $ansArr = (isset($_SESSION['answers']) && !empty($_SESSION['answers'])) ? $_SESSION['answers']['resultArray'] : '';
        $correctCount = (isset($_SESSION['correctCount']) && !empty($_SESSION['correctCount'])) ? $_SESSION['correctCount'] : 0;
        $wrongCount = NUMBER_OF_QUESTIONS - $correctCount;
        $wrongPercentage = ($wrongCount / NUMBER_OF_QUESTIONS) * 100;
        $questsArr = array_combine( $questsArr, $ansArr );
        unset($_SESSION['questions']);
        unset($_SESSION['answers']);
        unset($_SESSION['correctCount']);
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=SITE_NAME;?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?=SITE_URL;?>includes/css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="container-full">
            <header class="header">
                <h3><?=SITE_NAME;?></h3>
            </header>
            <h3 class="text-center">Online Quiz :: PHP Programming Questions</h3>
            <div class="col-md-2 col-sm-12"></div>
            <div class="col-md-8 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-custom">
                        <div class="col-md-6">
                            <h4><u>Result</u></h4>
                            <p>Attempted Questions : <strong><?=NUMBER_OF_QUESTIONS;?></strong></p>
                            <p>Total Correct Answers : <strong><?=(isset($correctCount)) ? $correctCount : 0;?></strong></p>
                            <p>Total Wrong Answers : <strong><?=(isset($wrongCount)) ? $wrongCount : 0;?></strong></p>
                            <p>Result : <strong><?=$percentage = (isset($correctCount)) ? $quiz->getPercentage($correctCount) : 0;?>%</strong></p>
                            <p>Performance : <strong><?=$quiz->showPerformance($percentage);?></strong></p>
                        </div>
                        <div class="col-md-6">
                            <div id="canvas-holder" style="width:80%"><canvas id="myChart" width="400" height="400"></canvas></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>                            
                    <div class="panel-body">
                        <div class="panel-group" id="accordion" role="tablist">
                            <?php
                            if(!empty($questsArr)) {
                                $questions = $quiz->showQuestions($questsArr);
                                $i = 1;
                                if (!empty($questions)) {
                                    foreach ($questions as $question=>$qRow) {
                                        ?>
                                        <div class="well"><strong><?='Question '.$i.': '.$qRow['question'];?></strong><br>
                                            <?php
                                            $options = $quiz->showOptions($qRow['quest_id']);
                                            if (!empty($options)) {
                                                foreach ($options as $option=>$optRow) {
                                                    $qid = $qRow['quest_id'];
                                                    $rowColor = '';
                                                    $mark = '';
                                                    if (!empty($questsArr)) {
                                                        if ($optRow['ans_id'] == $qRow['correct_answer']) {
                                                            if ($qRow['correct_answer'] == $questsArr[$qid]) {
                                                                $rowColor = 'correctanswer';
                                                                $checked = 'checked';
                                                                $mark = "<span class='glyphicon glyphicon-ok text-success pull-right'></span>";
                                                            } else if ($qRow['correct_answer'] != $questsArr[$qid]) {
                                                                $rowColor = 'unanswered';
                                                                $mark = "<span class='glyphicon glyphicon-ok text-success pull-right'></span>";
                                                            }
                                                        }
                                                        else if ($optRow['ans_id'] == $questsArr[$qid]) {
                                                            $rowColor = 'wronganswer';
                                                            $mark = "<span class='glyphicon glyphicon-remove text-danger pull-right'></span>";
                                                        }
                                                    }
                                                    ?>
                                                    <div class="option-border <?=$rowColor;?>">
                                                        <label class="radiocontainer">
                                                            <?=$optRow['answer'];?>
                                                            <?=$mark;?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                }
                            }
                            else {
                                ?>
                                <div class="well">
                                    <?=NO_RESULT;?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="form-group text-center">
                            <a href="<?=SITE_URL;?>" class="btn btn-success" role="button">Go to Home page</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <footer>
                <div class="footer">
                    <p class="text-muted">Copyright &copy; <?=date('Y');?> - All Rights Reserved.</p>
                </div>
            </footer>
        </div>    
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script type="text/javascript">        
        var ctx = document.getElementById('myChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Correct Answers - %", "Wrong Answers - %"],
                datasets: [{
                    label: "PHP Online Test",
                    backgroundColor: ["#82E0AA", "#F1948A"],
                    data: [<?=$percentage;?>,<?=$wrongPercentage;?>]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'PHP Online Test Results'
                },
                responsive: true,
                pointRadius: 0.3 
            }
        });
        </script>
    </body>
</html>