// MAIN
var mode = 9;
var t = {};
t.id = null;
t.name = null;
t.data = new Array();

function SetActive(id,name) {
    t.id     = id;
    t.name = name;
    $("#activetimer").show();
    $("#selector").hide();
    $("#projectTitle").html(t.name);
    console.log(t);
}

jQuery('#shoot').click(function(event){
    t.mode=0;
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
    t.mode=1;
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
    t.mode=2;
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
    t.mode=3;
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
    t.mode=9;
    event.preventDefault();
    var time = new Date();
    var dat = "st_"+time;
    t.data.push(dat);
        console.log(t);
        saveTimer();
        updateDisplay();
});

$('#deleteTimer').click(function(){
    alert('perform action here');
});


function updateDisplay()
{
switch (t.mode) {
    case 0:
    $("#timerCount").html('<i class="fa fa-video-camera"></i> '+updateTimer());
    break;
    case 1:
    $("#timerCount").html('<i class="fa fa-truck"></i> '+updateTimer());
    break;
    case 2:
    $("#timerCount").html('<i class="fa fa-car"></i> '+updateTimer());
    break;
    case 3:
    $("#timerCount").html('<i class="fa fa-pause"></i> '+updateTimer());
    break;
    default:
    $("#timerCount").html('');
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
    console.log(counter);
    return counter;
}

function saveTimer(){
/*    $.ajax({
        url: 'h_timer.php',
        dataType: 'json',
        data : {'action':'save','id':projectId, 'data':data},
        type: 'POST',
        success: function(data){
            if (data.message=="SUCCESS:") {
                saved = true;
            }else{
                saved = false;
            }
        }
    });
    */
}

function newCreated(data) {
    if (data.message=="SUCCESS") {
        $('#newTimerModal').modal('hide');
    }else{
        alert(data.message);
    }
}

function pad ( val ) { return val > 9 ? val : "0" + val; }
