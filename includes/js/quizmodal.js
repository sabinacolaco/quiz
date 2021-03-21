$("#btnReset").click(function(){
    $("#formQuiz").trigger("reset");
    $('div.radio > label').removeClass('checkedlabel');
});

$('label input[type=radio]').click(function() {
    $('input[name="' + this.name + '"]').each(function(){
        $(this.parentNode).toggleClass('checkedlabel', this.checked);
    });
});    
$("#quizModal").on( "click", ".radiocontainer", function(e) {
    $('input[name="' + this.name + '"]').each(function(){
        $(this.parentNode).toggleClass('checkedlabel', this.checked);
    });
 });  

var jsonData;    
var resultArray = [];

$('#quizModal').on('show.bs.modal', function(e){
   $.ajax
    ({
        type: "GET",
        url: SITE_URL+'ajaxprocess.php',
        cache: false,
        success: function(result)
        {
            jsonData = JSON.parse(result);
            var content = '<div class="error-messages" id="error"></div><div class="well"><strong>'+jsonData[0].questions.question+'</strong><br>';
            var questoptions = jsonData[0].options;
            for (var j=0;j<questoptions.length;j++) {
                content += "<div class='radio'><label class='radiocontainer'><input type='radio' class='radio_button' name='optradio["+jsonData[0].questions.quest_id+"]' value='"+jsonData[0].options[j].ans_id+"'>"+jsonData[0].options[j].answer+"</label></div>";
            }
            content += "</div>";
            $(".modal-body").html(content);
            $(".modal-footer").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-success btnNext">Next</button>')
            $('.modal-title .count').text(1);   
        } 
    });
});

$("#quizModal").on( "click", ".btnCancel", function(e) {
    $("#quizModal").modal('hide');
});

/* When the user clicks on the Next button*/
$("#quizModal").on( "click", ".btnNext", function(e) {
    e.preventDefault();
    var currQuestion = $('.modal-title .count').text();
    var currentVal = resultArray[parseInt(currQuestion)];
    
    if (!$('.radio_button').is(':checked')) {    
        $("#error").html(ERROR_NO_SELECT_OPTIONS);
    }
    else {
        $(".radio_button:checked").each(function() {
            var chkVal = $(this).val();
            if (resultArray === undefined || resultArray.length == 0) {
                resultArray.push(chkVal);
            }
            else {
                if (currentVal != chkVal) {
                    resultArray[parseInt(currQuestion)-1] = chkVal;           
                }
            }
        });
        var content = '<div class="error-messages" id="error"></div><div class="well"><strong>'+jsonData[currQuestion].questions.question+'</strong><br>';
        var questoptions = jsonData[currQuestion].options;
        var checkedradio;
        for (var j=0;j<questoptions.length;j++) {
            checkedradio = '';
            if (currentVal == jsonData[currQuestion].options[j].ans_id) {
                checkedradio = 'checked';
            }
            content += "<div class='radio'><label class='radiocontainer'><input type='radio' class='radio_button' name='optradio["+jsonData[currQuestion].questions.quest_id+"]' value='"+jsonData[currQuestion].options[j].ans_id+"' "+checkedradio+">"+jsonData[currQuestion].options[j].answer+"</label></div>";
        }
        content += "</div>";
        $(".modal-body").html(content);
        $('.modal-title .count').text(parseInt(currQuestion)+1);
        if (currQuestion == (NUMBER_OF_QUESTIONS-1)) {
            $(".modal-footer").html('<button type="button" class="btn btn-danger btnPrevious">Previous</button><button type="button" class="btn btn-success btnFinish">Finish</button>');
        }
        else {
            $(".modal-footer").html('<button type="button" class="btn btn-danger btnPrevious">Previous</button><button type="button" class="btn btn-success btnNext">Next</button>');
        }
    }
});  

/* When the user clicks on the Previous button*/
$("#quizModal").on( "click", ".btnPrevious", function(e) {
    e.preventDefault();
    var currQuestCount = $('.modal-title .count').text();
    var prevQuestCount = parseInt(currQuestCount)-1;
    var currQuest = parseInt(currQuestCount)-2;
    var currentVal = resultArray[currQuest];
    
    $(".radio_button:checked").each(function() {
        var chkVal = $(this).val();
    });

    var content = '<div class="well" ><strong>'+jsonData[currQuest].questions.question+'</strong><br>';
    var questoptions = jsonData[currQuest].options;
    var checkedradio = '';
    for (var j=0;j<questoptions.length;j++) {
        checkedradio = '';
        if (currentVal == jsonData[currQuest].options[j].ans_id) {
            checkedradio = 'checked';
        }
        content += "<div class='radio'><label class='radiocontainer'><input type='radio' class='radio_button' name='optradio["+jsonData[currQuest].questions.quest_id+"]' value='"+jsonData[currQuest].options[j].ans_id+"' "+checkedradio+">"+jsonData[currQuest].options[j].answer+"</label></div>";
    }
 
    content += "</div><div class='error-messages' id='error'></div>";
    $(".modal-body").html(content);
    if (currQuest == 0) {
        $(".modal-footer").html('<button type="button" class="btn btn-danger btnCancel">Cancel</button><button type="button" class="btn btn-success btnNext">Next</button>');
    }
    else {
        $(".modal-footer").html('<button type="button" class="btn btn-danger btnPrevious">Previous</button><button type="button" class="btn btn-success btnNext">Next</button>');
    }
    $('.modal-title .count').text(prevQuestCount);
});            

/* When the user clicks on the Finish button*/
$("#quizModal").on( "click", ".btnFinish", function(e) {
    e.preventDefault();
    var currQuestCount = $('.modal-title .count').text();
    var currQuest = parseInt(currQuestCount)-1;
    var currentVal = resultArray[currQuest];

    if (!$('.radio_button').is(':checked')) {   
        $("#error").html(ERROR_NO_SELECT_OPTIONS);
    } 
    else {
        $(".radio_button:checked").each(function() {
            var chkVal = $(this).val();
            if (resultArray === undefined || resultArray.length == 0) {
                resultArray.push(chkVal);
            }
            else {
                if (currentVal === undefined) {
                    resultArray.push(chkVal);
                } else  if (currentVal != chkVal) {
                    resultArray[parseInt(currentVal)] = chkVal;    
                }
            }
        });
        var content = '<div class="error-messages" id="error"></div><div class="well"><strong>'+jsonData[currQuest].questions.question+'</strong><br>';
        var questoptions = jsonData[currQuest].options;
        var checkedradio;
        for (var j=0;j<questoptions.length;j++) {
            checkedradio = '';
            if (currentVal == jsonData[currQuest].options[j].ans_id) {
                checkedradio = 'checked';
            }
            content += "<div class='radio'><label class='radiocontainer'><input type='radio' class='radio_button' name='optradio["+jsonData[currQuest].questions.quest_id+"]' value='"+jsonData[currQuest].options[j].ans_id+"' "+checkedradio+">"+jsonData[currQuest].options[j].answer+"</label></div>";
        }
        content += "</div>";
        $(".modal-body").html(content);
        if (resultArray.length == NUMBER_OF_QUESTIONS) {
            $.ajax
            ({
                type: "POST",
                url: SITE_URL + 'ajaxprocess.php',
                data: {resultArray:resultArray},
                cache: false,
                beforeSend: function() {
                    var content = '<img src="' + SITE_URL + 'includes/images/ajax-loader.gif" style="display: block; margin-left: auto; margin-right: auto;">';
                    $(".modal-content").html(content);
                },
                success: function(result) {
                    if (result == 1) {            
                        window.location = SITE_URL + "php-test-result";
                    }
                },
                error: function(result) {
                },
                complete: function () {
                    setTimeout(function() {
                    $("#quizModal").modal('hide');
                    }, 3000)
                }
            });
        }
        else {
            var content = ERROR_SELECT_OPTIONS;
            $(".modal-body").html(content);
            $(".modal-footer").html('<button type="button" class="btn btn-danger btnPrevious" data-dismiss="modal">Cancel</button>');            
        }
    }
});