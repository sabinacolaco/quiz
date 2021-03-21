<?php
include('config/Quiz.class.php');
$quiz = new Quiz();
$questsArr = [];
if (!isset($_SESSION["test_time"])) {
    $_SESSION["test_time"] = TEST_TIME_LIMIT;
}
if (isset($_SESSION["test_time"]) && !empty($_SESSION["test_time"])) {
    date_default_timezone_set('Europe/London');
    $date = date("Y-m-d H:i:s");
    $_SESSION["end_time"] = date("Y-m-d H:i:s", strtotime($date . "+$_SESSION[test_time] minutes"));
    $_SESSION["test_start"] = "yes";
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
                        <div class="panel-heading panel-heading-custom"> <h4><u>Instructions</u></h4>
                            <p>Number of questions : <?=NUMBER_OF_QUESTIONS;?> | Time Limit : <?=TEST_TIME_LIMIT;?> mins</p>
                            <h5><small>*Note: Each question carry 1 mark each, no negative marking and don't refresh the page.</small></h5>
                            <div id="countdowntimer" style="display: block;" class="pull-right"></div><div class="pull-right"><strong>Time Remaining:</strong>&nbsp;</div> 
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="panel-group" id="accordion" role="tablist">
                                <div class="question vh-center">
                                    <div class="question-container">
                                        <form role="form" id='formQuiz' action="<?=SITE_URL;?>single-page" method="post">
                                            <div class="error-messages" id="error"></div> 
                                            <?php 
                                            $i=1;
                                            $questions = $quiz->showQuestions($questsArr);
                                            if(!empty($questions)) {
                                            
                                                foreach ($questions as $key => $result) {
                                                    ?>                    
                                                    <div id='question<?php echo $i;?>' class='Qcontainer'>
                                                        <div class="well">
                                                            <p><strong>Question <?=$i . ': ' . $result['question'];?></strong></p>
                                                            <input type='hidden' name='optradio[<?=$result['quest_id'];?>]' value='0' />
                                                            <?php
                                                            $options = $quiz->showOptions($result['quest_id']);
                                                            if (!empty($options)) {
                                                                foreach ($options as $kopt=>$vopt) {
                                                                    $qid = $result['quest_id'];
                                                                    $answerclass = '';
                                                                    $wrongclass = '';
                                                                    $checked = '';
                                                                        
                                                                    if (!empty($_POST['optradio'])) {
                                                                        if ($vopt['ans_id'] == $questsArr[$qid]) {
                                                                            $answerclass = 'correctanswer';
                                                                        }
                                                                        else if ($vopt['ans_id'] == $_POST['optradio'][$qid]) {
                                                                            $answerclass = 'wronganswer';
                                                                            $checked = 'checked';
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <div class="radio <?=$answerclass;?> <?=$wrongclass;?>">
                                                                            <label class="radiocontainer">
                                                                                <input id="radio1" type="radio" name="optradio[<?=$result['quest_id'];?>]" value="<?=$vopt['ans_id'];?>" <?=$checked;?>><?=$vopt['answer'];?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
    
                                                            ?>
                                                        </div>
                                                        <div class="form-group text-center">
                                                            <?php
                                                            if ($i == 1) {
                                                                ?>
                                                                <button id='<?php echo $i;?>' class='btnNext btn btn-success' type='button'>Next Question</button>
                                                                <?php
                                                            }
                                                            elseif ($i<1 || $i<NUMBER_OF_QUESTIONS) {
                                                                ?>
                                                                <button id='<?php echo $i;?>' class='btnPrevious btn btn-success' type='button'>Previous Question</button>&nbsp;                    
                                                                <button id='<?php echo $i;?>' class='btnNext btn btn-success' type='button' >Next Question</button>
                                                                <?php
                                                            } elseif($i == NUMBER_OF_QUESTIONS) {
                                                                ?>
                                                                <button id='<?php echo $i;?>' class='btnPrevious btn btn-success' type='button'>Previous Question</button>&nbsp;                    
                                                                <button id='<?php echo $i;?>' class='btnNext btn btn-success' type='submit'>Finish</button>
                                                                <?php
                                                            }
                                                            $i++;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }                                            
                                            ?>
                                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                                            <input type='hidden' name='timerValue' id='timerValue' />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="clearfix"></div>
            <footer class="footer">
                <div class="container text-center">
                    <p class="text-muted">Copyright &copy; <?=date('Y');?> - All Rights Reserved.</p>
                </div>
            </footer>
        </div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    var SITE_URL = "<?=SITE_URL;?>";
    $('label input[type=radio]').click(function() {
        $('input[name="' + this.name + '"]').each(function(){
            $(this.parentNode).toggleClass('checkedlabel', this.checked);
        });
    });    

    $('.Qcontainer').addClass('hide');
    var count = $('.questions').length;
    $('#question'+1).removeClass('hide');

    $(document).on('click','.btnNext',function(){
        var last = parseInt($(this).attr('id'));     
        var nex = last+1;
        var chkradio = $("input[type='radio']:checked").val();
        if (chkradio !== undefined) {
            $("#error").html('');
            $('#question'+last).addClass('hide');
            $('#question'+nex).removeClass('hide');
        }
        else {
            $("#error").html('<?=ERROR_NO_SELECT_OPTIONS;?>');
        }
    });

    $(document).on('click','.btnPrevious',function(){
        last=parseInt($(this).attr('id'));     
        pre = last-1;
        $('#question'+last).addClass('hide');
        $('#question'+pre).removeClass('hide');
    });
    
    setInterval(function(){
        timer();
    },1000);
    function timer()
    {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                if (xmlhttp.responseText=="00:00:01") {
                    window.location="<?=SITE_URL;?>multi-page";
                }

                document.getElementById("countdowntimer").innerHTML=xmlhttp.responseText;
                document.getElementById("timerValue").value=xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","ajaxprocess.php?settimer=yes",true);
        xmlhttp.send(null);
    }
    </script>
    </body>
</html>