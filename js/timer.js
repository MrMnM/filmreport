// MAIN
var mode = 9;
var counter = null;
var t = {};
t.id = null;
t.name = null;
t.data = new Array();


$(document).ready(function() {
    $('#newTimerForm').ajaxForm({
        dataType:  'json',
        success:  newCreated
    });
});

jQuery('#addTimer').click(function(event){

});

jQuery('#shoot').click(function(event){
    mode=0;
    event.preventDefault();
    var time = new Date();
    startStamp = time;
    var dat = "sh_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

jQuery('#load').click(function(event){
    mode=1;
    event.preventDefault();
    var time = new Date();
        startStamp = time;
    var dat = "lo_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

jQuery('#drive').click(function(event){
    mode=2;
    event.preventDefault();
    var time = new Date();
        startStamp = time;
    var dat = "dr_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

jQuery('#pause').click(function(event){
    mode=3;
    event.preventDefault();
    var time = new Date();
        startStamp = time;
    var dat = "pa_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

jQuery('#stop').click(function(event){
    mode=9;
    event.preventDefault();
    var time = new Date();
    var dat = "st_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

setInterval( function(){
if (mode!=9) {
        updateTimer();
}
    updateDisplay();
}, 1000);


function updateDisplay()
{
switch (mode) {
    case 0:
    $("#timer").html('<i class="fa fa-video-camera"></i> '+counter);
    break;
    case 1:
    $("#timer").html('<i class="fa fa-truck"></i> '+counter);
    break;
    case 2:
    $("#timer").html('<i class="fa fa-car"></i> '+counter);
    break;
    case 3:
    $("#timer").html('<i class="fa fa-pause"></i> '+counter);
    break;
    default:
    $("#timer").html('');
}
}

function updateTimer() {
    newDate = new Date();
    newStamp = newDate.getTime();
    var diff = Math.round((newStamp-startStamp)/1000);
    var d = Math.floor(diff/(24*60*60));
    diff = diff-(d*24*60*60);
    var h = Math.floor(diff/(60*60));
    diff = diff-(h*60*60);
    var m = Math.floor(diff/(60));
    diff = diff-(m*60);
    var s = diff;
    counter =  pad(h)+':'+pad(m)+':'+pad(s);
}

function saveTimer(){
    var data = JSON.stringify(t);
    $.ajax({
        url: 'h_save_timer.php',
        dataType: 'json',
        data : {'id':projectId, 'data':data},
        type: 'POST',
        success: function(data){
            if (data.message=="SUCCESS:") {
                saved = true;
            }else{
                saved = false;
            }
        }
    });
}

function newCreated(data) {
    if (data.message=="SUCCESS") {
        $('#modalContent').html('<div class="alert alert-success">Account wurde erstellt. Bitte den Link im Best&auml;tigungsemail klicken um die Registrierung abzuschliessen.</div>');
        $('#submitbutton').hide();
        $('#closebutton').show();
    }else{
        alert(data.message);
    }
}

function pad ( val ) { return val > 9 ? val : "0" + val; }
