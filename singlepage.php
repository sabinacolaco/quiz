<?php
include('config/Quiz.class.php');
$quiz = new Quiz();
$questsArr = [];
$showResult = 0;
$attemptedCount = 0;
$wrongCount = 0;
$correctCount = 0;
$percentage = 0;
$performance= '';
$time = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['token']) && isset($_SESSION['token'])) {
    if ($_POST['token'] != $_SESSION['token'] || !isset($_SESSION['token'])) {
        die("Unauthorized source!");
    }
    else {
        if (!empty($_POST['optradio'])) {
            
            $optsPostRadio = $_POST['optradio'];
            $questsArr = $quiz->checkAnswers($optsPostRadio);
            
            if (in_array(0, $optsPostRadio)) {
                $counts = array_count_values($optsPostRadio);
                $attemptedCount =  (!empty($counts)) ? (NUMBER_OF_QUESTIONS - $counts['0']) : 0;
            }
            else
                $attemptedCount = NUMBER_OF_QUESTIONS;
            
            if (!empty ($questsArr)) {
                $diffArr = array_diff($questsArr, $optsPostRadio);
                $wrongCount = count($diffArr);
                $correctCount = NUMBER_OF_QUESTIONS - $wrongCount;
            }

            $showResult = 1;
            if (isset($_SESSION["questions"]) && !empty($_SESSION["questions"])) {
                unset($_SESSION['questions']);
                unset($_SESSION['answers']);
                unset($_SESSION['correctCount']);
                unset($_SESSION['token']);
                session_destroy();
            }
        }
        if (isset($_SESSION["test_time"]) && !empty($_SESSION["test_time"])) {
            if (!empty($_POST['timerValue'])) {
                $time = gmdate("H:i:s", strtotime("0:1:0") - strtotime($_POST['timerValue']));

            }
            $endtime = $_SESSION['test_time'];
            unset($_SESSION['test_time']);
            unset($_SESSION['end_time']);
            unset($_SESSION['test_start']);
            unset($_SESSION['token']);
            session_destroy();
        }
    }
    }
}
else {
    $_SESSION['token'] = md5(uniqid(mt_rand(), true));
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
            <p class="text-center">All the best :)</p>
            <div class="col-md-2 col-sm-12"></div>
            <div class="col-md-8 col-sm-12">
                <div class="panel panel-default">
                    <?php
                    if ($showResult == 1) {
                        ?>
                        <div class="panel-heading panel-heading-custom">
                            <div class="col-md-6">
                                <h4><u>Result</u></h4>
                                <p>Attempted Questions : <strong><?=$attemptedCount;?></strong></p>
                                <p>Total Correct Answers : <strong><?=$correctCount;?></strong></p>
                                <p>Total Wrong Answers : <strong><?=$wrongCount;?></strong></p>
                                <p>Result : <strong><?=$percentage = (isset($correctCount)) ? $quiz->getPercentage($correctCount) : 0;?>%</strong></p>
                                <p>Performance : <strong><?=$quiz->showPerformance($percentage);?></strong></p>
                            </div>
                            <div class="col-md-6 pull-right">
                                <?php
                                if ($time) {
                                    ?>
                                    <h5>Time Taken : <?=$time;?></h5>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>                            
                        <?php
                    }
                    else {
                        ?>
                        <div class="panel-heading panel-heading-custom"> <h4><u>Instructions</u></h4>
                            <p>Number of questions : <?=NUMBER_OF_QUESTIONS;?> | No Time Limit</p>
                            <h5><small>*Note: Each question carry 1 mark each, no negative marking and don't refresh the page.</small></h5>
                        </div>
                        <?php
                    }
                    ?>                       
                    <div class="panel-body">
                        <div class="panel-group" id="accordion" role="tablist">
                            <form id="formQuiz" method="post" class="needs-validation" novalidate>
                                <?php
                                $i = 1;
                                $disabled = '';
                                $questions = $quiz->showQuestions($questsArr);
                                if (!empty($questions)) {
                                    foreach ($questions as $question=>$qRow) {
                                        ?>
                                        <div class="well" ><strong><?='Question '.$i.': '.$qRow['question'];?></strong><br>
                                            <input type='hidden' name='optradio[<?=$qRow['quest_id'];?>]' value='0' />
                                            <?php
                                            $options = $quiz->showOptions($qRow['quest_id']);
                                            if (!empty($options)) {
                                                foreach ($options as $option=>$optRow) {
                                                    $qid = $qRow['quest_id'];
                                                    $rowColor = '';
                                                    $checked = '';
                                                    $mark = '';
                                                    if (!empty($optsPostRadio)) {
                                                        $disabled = 'disabled';
                                                        if ($optRow['ans_id'] == $questsArr[$qid]) {
                                                            if ($optsPostRadio[$qid] == 0 || ($questsArr[$qid] != $optsPostRadio[$qid])) {
                                                                $rowColor = 'unanswered';
                                                            }
                                                            else {
                                                                $rowColor = 'correctanswer';
                                                                $checked = 'checked';
                                                            }
                                                            $mark = "<span class='glyphicon glyphicon-ok text-success pull-right'></span>";
                                                        }
                                                        else if ($optRow['ans_id'] == $optsPostRadio[$qid]) {
                                                            $rowColor = 'wronganswer';
                                                            $checked = 'checked';
                                                            $mark = "<span class='glyphicon glyphicon-remove text-danger pull-right'></span>";
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <div class="radio <?=$rowColor;?>">
                                                            <label class="radiocontainer" <?=$disabled;?>>
                                                            <input type="radio" name="optradio[<?=$qRow['quest_id'];?>]" value="<?=$optRow['ans_id'];?>" <?=$checked;?> <?=$disabled;?>><?=$optRow['answer'];?>
                                                            <?=$mark;?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <div class="form-group text-center">
                                        <?php
                                        if ($showResult == 0) {
                                            ?>
                                            <button type="reset" id="btnReset" class="btn btn-danger" >Reset</button>&nbsp;
                                            <button type="submit" id="btnSubmit" class="btn btn-success" value="submit">Submit</button>
                                            <?php
                                        }
                                        else {
                                            $redirectUrl = !empty($time) ? 'multi-page' : 'single-page';
                                            ?>
                                            <a href="<?=SITE_URL . $redirectUrl;?>" class="btn btn-success" role="button">Start Again</a>&nbsp;
                                            <a href="<?=SITE_URL;?>" class="btn btn-success" role="button">Go to Home page</a>
                                            <?php
                                        }?>
                                    </div>
                                    <?php
                                }
                                else {
                                    ?>
                                    <div class="well">
                                        <?=NO_RESULT;?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                            </form>
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
    <script type="text/javascript">
    $("#btnReset").click(function(){
        $("#formQuiz").trigger("reset");
        $('div.radio > label').removeClass('checkedlabel');
    });
    
    $('label input[type=radio]').click(function() {
        $('input[name="' + this.name + '"]').each(function(){
            $(this.parentNode).toggleClass('checkedlabel', this.checked);
        });
    });
    </script>
    </body>
</html>