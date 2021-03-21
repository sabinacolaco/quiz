<?php
include('config/Quiz.class.php');
$quiz = new Quiz();
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
            <p class="text-center"></p>
            <div class="col-md-2 col-sm-12"></div>
            <div class="col-md-8 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-custom">
                        <h4><u>There are 3 layouts:</u></h4>
                        <h5>Single-Page Layout : All the questions are listed on one single page</h5>
                        <h5>Multi-Page Layout : Questions comes one after the other</h5>
                        <h5>Pop-Up Layout : Questions open in an pop-up</h5>
                    </div>
                    <div class="panel-body">
                        <div class="panel-group" id="accordion" role="tablist">
                            <div class="col-lg-4 col-md-6 online-test-box">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row text-center">
                                            <div class="font">Single-Page Layout</div>
                                        </div>
                                    </div>
                                    <a href="<?=SITE_URL;?>single-page">
                                        <div class="panel-footer">
                                            <span>Start</span>
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 online-test-box">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row text-center">
                                            <div class="font">Multi-Page Layout</div>
                                        </div>
                                    </div>
                                    <a href="<?=SITE_URL;?>multi-page">
                                        <div class="panel-footer">
                                            <span>Start</span>
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 online-test-box">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row text-center">
                                            <div class="font">Pop-Up Layout</div>
                                        </div>
                                    </div>
                                    <a href="#" data-toggle="modal" data-target="#quizModal" target="_blank">
                                        <div class="panel-footer">
                                            <span>Start</span>
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
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

        <!-- Modal to answer the Quiz-->
        <div id="quizModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Question <span class="count"></span> out of <?=NUMBER_OF_QUESTIONS;?></h4>
                    </div>
                    <form id ="addform">
                        <div class="modal-body">
        
                        </div>
                        <div class="modal-footer">
        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script type="text/javascript">
    var SITE_URL = "<?=SITE_URL;?>";
    var NUMBER_OF_QUESTIONS = <?=NUMBER_OF_QUESTIONS;?>;
    var ERROR_SELECT_OPTIONS = "<?=ERROR_SELECT_OPTIONS;?>";
    var ERROR_NO_SELECT_OPTIONS = "<?=ERROR_NO_SELECT_OPTIONS;?>";
    </script>
    <script type="text/javascript" src="<?=SITE_URL;?>includes/js/quizmodal.js"></script>
    </body>
</html>