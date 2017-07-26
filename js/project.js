// MAIN
var addInfo = { "tothour" : "00:00", "totmoney"  : "0", "enddate"  : "0000-00-00" };
var rowCounter = loadElement.length;
var rowElement = new Array();
var saved = true;
var basePay = $("#basePay").val();
var startDate = new Date($("#startDate").val());
var us_id = null;
var p_id = null;

//   BUTTONS
//-----------------------------------------------------------------------------
jQuery('button.saveButton').click(function(event){
    event.preventDefault();
    Save();
    updateSaveStatus(saved);
});
jQuery('button.add-row').click(function(event){
    event.preventDefault();
    saved = false;
    addRow();
    updateBottom();
    updateSaveStatus();
});
jQuery('button.remove-row').click(function(event){
    event.preventDefault();
    saved = false;
    if (rowCounter != 0){
        rowCounter--;
        $('#r'+rowCounter).remove();
        rowElement.pop();
    }
    updateBottom();
    updateSaveStatus();
});
jQuery('button.refreshButton').click(function(event){
    saved=false;
    for (var i = 0; i < rowElement.length; i++) {
        updateRow(i);
    }
    updateBottom();
    updateSaveStatus();
});

// changed elements ------------------------------------------------------------
$( "#workhours" ).change(function() {
    saved = false;
    var currentField = event.target.name.substring(0, 4);
    var currentNumber = event.target.name.substring(4);
    if (currentField == "star") {
        rowElement[currentNumber].start = event.target.value;
    } else if (currentField == "ende") {
        rowElement[currentNumber].end = event.target.value;
    } else if (currentField == "brea"){
        rowElement[currentNumber].break = event.target.value;
    } else if (currentField == "date") {
        rowElement[currentNumber].date = event.target.value;
    } else if (currentField == "base") {
        rowElement[currentNumber].manualBase = true;
        rowElement[currentNumber].base = event.target.value;
    } else if (currentField == "cark") {
        rowElement[currentNumber].car = event.target.value;
    } else if (currentField == "lunc") {
        rowElement[currentNumber].lunch ^= true;
    } else if (currentField == "work") {
        rowElement[currentNumber].work = event.target.value;
    }
    rowElement[currentNumber].date = $('#date'+currentNumber).val();
    updateRow(currentNumber);
    updateBottom();
    updateSaveStatus();
});
$( "#comment" ).change(function() {
    saved = false;
    updateSaveStatus();
});

//HELPERS
//------------------------------------------------------------------------------
function addRow(){
    //event.preventDefault();
    var currentDate = startDate;
    if (rowCounter > 0){
        var currentCounter = rowCounter-1;
        var inputDate = $('[name="date'+currentCounter+'"]').val();
        currentDate = new Date(inputDate);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    rowElement.push(new Row(rowCounter));
    var newRow = jQuery(`
        <tr id="r`+rowCounter+`">
        <td><input type="date" id="date`+rowCounter+`" name="date`+rowCounter+`" value="`+formatDate(currentDate)+`"></td>
        <td><input type="text" name="work`+rowCounter+`" size=10 list="work"></td>
        <td><input type="time" name="star`+rowCounter+`" min=0 value="00:00"></td>
        <td><input type="time" name="ende`+rowCounter+`" min=0 value="00:00"></td>
        <td><input type="time" name="brea`+rowCounter+`" min=0  value="00:00"></td>
        <td id="wtim`+rowCounter+`">0</td>
        <td><input type="number" id="base`+rowCounter+`" name="base`+rowCounter+`" min=0 Math.max=2></td>
        <td id="tent`+rowCounter+`">0</td>
        <td id="elev`+rowCounter+`">0</td>
        <td id="twel`+rowCounter+`">0</td>
        <td id="thir`+rowCounter+`">0</td>
        <td id="four`+rowCounter+`">0</td>
        <td id="fift`+rowCounter+`">0</td>
        <td id="sixt`+rowCounter+`">0</td>
        <td id="nigh`+rowCounter+`">0</td>
        <td><input type="checkbox" name="lunc`+rowCounter+`"></td>
        <td><input type="number" name="cark`+rowCounter+`" min=0 value=0></td>
        </tr>`);
    jQuery('#workhours').append(newRow);
    rowElement[rowCounter].date = formatDate(currentDate);
    saved = false;
    updateRow(rowCounter);
    updateSaveStatus();
    rowCounter++;
}
function loadRow(currentRow){
    //rowElement.push(new Row(rowCounter));
    var newRow = jQuery(`
        <tr id="r`+currentRow+`">
        <td><input type="date" id="date`+currentRow+`" name="date`+currentRow+`" value="`+rowElement[currentRow].date+`"></td>
        <td><input type="text" name="work`+currentRow+`" size=10 list="work" value="`+rowElement[currentRow].work+`"></td>
        <td><input type="time" name="star`+currentRow+`" min=0 value="`+rowElement[currentRow].start+`"></td>
        <td><input type="time" name="ende`+currentRow+`" min=0 value="`+rowElement[currentRow].end+`"></td>
        <td><input type="time" name="brea`+currentRow+`" min=0 value="`+rowElement[currentRow].break+`"></td>
        <td id="wtim`+currentRow+`">0</td>
        <td><input type="number" id="base`+currentRow+`" name="base`+currentRow+`" min=0 Math.max=2 value="`+rowElement[currentRow].base+`"></td>
        <td id="tent`+currentRow+`">0</td>
        <td id="elev`+currentRow+`">0</td>
        <td id="twel`+currentRow+`">0</td>
        <td id="thir`+currentRow+`">0</td>
        <td id="four`+currentRow+`">0</td>
        <td id="fift`+currentRow+`">0</td>
        <td id="sixt`+currentRow+`">0</td>
        <td id="nigh`+currentRow+`">0</td>
        <td><input type="checkbox" name="lunc`+currentRow+`" value="`+rowElement[currentRow].lunch+`"></td>
        <td><input type="number" name="cark`+currentRow+`" min=0 value="`+rowElement[currentRow].car+`"></td>
        </tr>`);
        jQuery('#workhours').append(newRow);
        //rowElement[rowCounter].date = formatDate(currentDate);
        //rowCounter++;
        //saved = false;
}
function Save() {
    var rows = JSON.stringify(rowElement);
    var additional = JSON.stringify(addInfo);
    var projectId =  $("#projectId").val();
    var comment = $("#comment").val();;
    $("#saveButton").hide();
    $("#saveButtonDisabled").show();

    $.ajax({
        url: 'h_project.php',
        dataType: 'json',
        data : {'action':'save','id':projectId, 'data':rows, 'add':additional, 'comment':comment},
        type: 'POST',
        success: function(data){
            if (data.message=="SUCCESS:") {
                saved = true;
                $("#saveButton").show();
                $("#saveButtonDisabled").hide();
                updateSaveStatus();
            }else{
                saved = false;
                $("#saveButton").show();
                $("#saveButtonDisabled").hide();
                updateSaveStatus();
            }
        }
    });

    $("#saveButton").show();
    $("#saveButtonDisabled").hide();

}
function updateRow(row){
    $('#wtim'+row).html(rowElement[row].getWorkHours());

    if (rowElement[row].getOvertime(10)>0) {
        $('#tent'+row).html(rowElement[row].getOvertime(10));
    }else{ $('#tent'+row).html('0');}
    if (rowElement[row].getOvertime(11)>0) {
        $('#elev'+row).html(rowElement[row].getOvertime(11));
    }else{ $('#elev'+row).html('0');}
    if (rowElement[row].getOvertime(12)>0) {
        $('#twel'+row).html(rowElement[row].getOvertime(12));
    }else{ $('#twel'+row).html('0');}
    if (rowElement[row].getOvertime(13)>0) {
        $('#thir'+row).html(rowElement[row].getOvertime(13));
    }else{ $('#thir'+row).html('0');}
    if (rowElement[row].getOvertime(14)>0) {
        $('#four'+row).html(rowElement[row].getOvertime(14));
    }else{ $('#four'+row).html('0');}
    if (rowElement[row].getOvertime(15)>0) {
        $('#fift'+row).html(rowElement[row].getOvertime(15));
    }else{ $('#fift'+row).html('0');}
    if (rowElement[row].getOvertime(16)>0) {
        $('#sixt'+row).html(rowElement[row].getOvertime(16));
    }else{ $('#sixt'+row).html('0');}
    $('#nigh'+row).html(rowElement[row].getNightHours());
    $('#base'+row).val(rowElement[row].getBase());
}
function updateBottom(){
    var totalKilometers = 0;
    for(index = 0; index < rowElement.length; ++index){
        totalKilometers += parseInt(rowElement[index].car);
    }
        var totalWorkHours = 0;
        for(index = 0; index < rowElement.length; ++index){
            if (rowCounter > 0 && $('#wtim'+index).html()>"00:00"){
            var currentWorkHours = timeToMins(rowElement[index].getWorkHours());
            totalWorkHours = totalWorkHours + currentWorkHours;
        }
        }


    var lunches = 0;
    for(index = 0; index < rowElement.length; ++index){
        if (rowElement[index].lunch){
            lunches += 1;
        }
    }

    var totalBase = 0;
    for(index = 0; index < rowElement.length; ++index){
        totalBase += parseFloat(rowElement[index].base);
    }
    var hours125 = 0;
    for(index = 0; index < rowElement.length; ++index){
        hours125 += parseFloat(rowElement[index].getOvertime(10));
        hours125 += parseFloat(rowElement[index].getOvertime(11));
    }

                console.log(rowElement[0].getOvertime(10));
    var hours150 = 0;
    for(index = 0; index < rowElement.length; ++index){
        hours150 += parseFloat(rowElement[index].getOvertime(12));
        hours150 += parseFloat(rowElement[index].getOvertime(13));
    }
    var hours200 = 0;
    for(index = 0; index < rowElement.length; ++index){
        hours200 += parseFloat(rowElement[index].getOvertime(14));
        hours200 += parseFloat(rowElement[index].getOvertime(15));
    }
    var hours250 = 0;
    for(index = 0; index < rowElement.length; ++index){
        hours250 += parseFloat(rowElement[index].getOvertime(16));
    }
    var hours25 = 0;
    for(index = 0; index < rowElement.length; ++index){
        hours25 += parseFloat(rowElement[index].getNightHours());
    }

    var total125 = roundToTwo(hours125 * getRate(125));
    var total150 = roundToTwo(hours150 * getRate(150));
    var total200 = roundToTwo(hours200 * getRate(200));
    var total250 = roundToTwo(hours250 * getRate(250));
    var total25 = roundToTwo(hours25 * getRate(25));
    var totalLunch = lunches*32;
    var totalCar = roundToTwo(totalKilometers * 0.7);
    var totalDay = roundToTwo(totalBase*basePay);
    var totalAdditional = roundToTwo(totalLunch+totalCar);
    var totalOvertime = roundToTwo(total25 + total125 + total150 + total200 +total250)

    $('#payRateDay').html(basePay);
    $('#payRate125').html(getRate(125));
    $('#payRate150').html(getRate(150));
    $('#payRate200').html(getRate(200));
    $('#payRate250').html(getRate(250));
    $('#payRate25').html(getRate(25));

    $('#totalKilometers').html(totalKilometers);
    $('#lunches').html(lunches);
    $('#totalWorkHours').html(minsToHours(totalWorkHours));

    $('#hoursDay').html(roundToTwo(totalBase));
    $('#hours125').html(roundToTwo(hours125));
    $('#hours150').html(roundToTwo(hours150));
    $('#hours200').html(roundToTwo(hours200));
    $('#hours250').html(roundToTwo(hours250));
    $('#hours25').html(roundToTwo(hours25));

    $('#totalDay').html(totalDay);
    $('#total125').html(total125);
    $('#total150').html(total150);
    $('#total200').html(total200);
    $('#total250').html(total250);
    $('#total25').html(total25);
    $('#totalLunch').html(totalLunch);
    $('#totalCar').html(totalCar);

    $('#salaryBase').html(totalDay);
    $('#salaryOvertime').html(totalOvertime);
    $('#salaryAdditional').html(totalAdditional);

    addInfo.enddate = rowElement[rowElement.length-1].date;
    //console.log(addInfo.enddate);
    addInfo.tothour = minsToHours(totalWorkHours);
    addInfo.totmoney= roundToTwo(totalDay+totalOvertime+totalAdditional);
}
function formatDate(d = new Date) {
    let month = String(d.getMonth() + 1);
    let day = String(d.getDate());
    const year = String(d.getFullYear());
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return `${year}-${month}-${day}`;
}
function timeToMins(time) {
    var b = time.split(':');
    return b[0]*60 + +b[1];
}
function timeFromMins(mins) {
    function z(n){return (n<10? '0':'') + n;}
    var h = (mins/60 |0) % 24;
    var m = mins % 60;
    return z(h) + ':' + z(m);
}
function minsToHours(mins){
    function z(n){return (n<10? '0':'') + n;}
    var h = (mins/60 |0);
    var m = mins % 60;
    return z(h) + ':' + z(m);
}
function subTimes(t0, t1) {
    return timeFromMins(timeToMins(t0) - timeToMins(t1));
}
function addTimes(t0, t1){
    return timeFromMins(timeToMins(t0) + timeToMins(t1));
}
function roundToTwo(num) {
    return +(Math.round(num + "e+2")  + "e-2");
}
function getRate(num){
    return roundToTwo(basePay/9*num/100)
}
function updateSaveStatus() {
    if (saved) {
        $("#saveInfo").show();
        $("#saveNone").hide();
        $("#saveWarning").hide();
    } else {
        $("#saveInfo").hide();
        $("#saveNone").hide();
        $("#saveWarning").show();
    }
}
function loadJSON(data){
    rowElement = new Array();
    for (var i = 0; i < data.length; i++) {
        rowElement.push(new Row(i));
        rowElement[i].loadFromJSON(data[i]);
    }
}
function updateSuccess(data){
    if (data.message=="SUCCESS:") {
        updateProjectInfo()
        $('#updateProjectModal').modal('hide');
    }else{
        alert(data.message);
    }
}
function updateProjectInfo(){
    $.post( "h_project.php", { action: "getinfo", us_id: us_id, p_id: p_id }).done(function( data ) {
    data = jQuery.parseJSON(data);
    $( "#projectName" ).html( data.name );
    $( "#title" ).html( data.name );
    $( "#projectJob" ).html( data.job );
    $( "#projectPay" ).html( data.pay );
    $( "#projectCompany" ).html( data.company );
});
}

function updatePersonalInfo(){
    $.post( "h_user.php", { action: "get", us_id}).done(function( data ) {
    data = jQuery.parseJSON(data);
    $( "#userName" ).html( data.name );
    $( "#userAddress" ).html( data.address1+'<br>'+data.address2 );
    $( "#userTel" ).html( data.tel );
    $( "#userMail" ).html( data.mail );
    $( "#userAHV" ).html( data.ahv );
    $( "#userDob" ).html( data.dob );
    $( "#userKonto" ).html( data.konto );
    $( "#userBVG" ).html( data.bvg );
});
}

function updateAll(){
    for (var i = 0; i < rowElement.length; i++) {
        loadRow(i);
        updateRow(i);
    }
    updateBottom();
}

//OBJECTS -----------------------------------------------------------------------
function Row(idNr) {
    var obj = {};
    obj.id = idNr;
    obj.date = null;
    obj.start = '00:00';
    obj.end = '00:00';
    obj.work = '';
    obj.break = '00:00';
    obj.base = 0.0;
    obj.manualBase = false;
    obj.car= 0;
    obj.lunch = false;
    obj.workhours = '00:00';
    obj.tent=0;
    obj.elev=0;
    obj.twel=0;
    obj.thir=0;
    obj.four=0;
    obj.fift=0;
    obj.sixt=0;
    obj.night=0;
    obj.loadFromJSON = function(json){
        obj.id=json.id;
        obj.date =json.date;
        obj.start = json.start;
        obj.end = json.end;
        obj.work =json.work;
        obj.break =json.break;
        obj.base =json.base;
        obj.manualBase =json.manualBase;
        obj.car= json.car;
        obj.lunch =json.lunch;
    }
    obj.getWorkHours = function() {
        if (obj.base==0.6) {
            obj.workhours="05:00";
            return obj.workhours;
        }
        var brk = obj.break;
        var pause=[];
        pause[0] = brk.split(':')[0];
        pause[1] = brk.split(':')[1];
        var difference = moment.utc(moment(obj.end,"HH:mm").diff(moment(obj.start,"HH:mm"))).format("HH:mm");
        var duration = moment.duration(difference);
        duration.subtract(pause[0] + ':00', 'hours');
        duration.subtract('00:' + pause[1], 'minutes');
        obj.workhours = moment.utc(+duration).format('H:mm');
        return obj.workhours;
    };
    obj.getBase = function() {
        if(obj.work != null && obj.work != ''){
            if (obj.manualBase == false){
                switch(obj.work){
                    case "Dreh":
                    obj.base = 1.0;
                    break;
                    case "Laden":
                    obj.base = 0.6;
                    break;
                    case "Vorbereitung":
                    obj.base = 0.6;
                    break;
                    case "Reisetag":
                    obj.base = 0.6;
                    break;
                    default:
                    obj.base = 0.6;
                }
                return obj.base;
            }
        }else{
            return 0;
        }
    };
    obj.getOvertime = function(hour) {
        var ret=0;
        var workhours = obj.getWorkHours()
        var currentHour = timeFromMins((hour-1)*60);
        if (workhours > currentHour){
            if(subTimes(workhours,currentHour) > "01:00"){
                if (workhours>"16:00" && currentHour == "15:00"){
                    var mins = timeToMins(subTimes(workhours,currentHour));
                    ret= roundToTwo(mins/60);
                } else{
                    ret=1;
                }
            } else {
                var mins = timeToMins(subTimes(workhours,currentHour));
                if (isNaN(mins) ||typeof ret == 'undefined') {
                    ret=0;
                }else{
                ret= roundToTwo(mins/60);
            }
            }
        }
        if (hour==10) {obj.tent=ret;}
        if (hour==11) {obj.elev=ret;}
        if (hour==12) {obj.twel=ret;}
        if (hour==13) {obj.thir=ret;}
        if (hour==14) {obj.four=ret;}
        if (hour==15) {obj.fift=ret;}
        if (hour==16) {obj.sixt=ret;}
        return ret;
    };

    obj.getNightHours = function() {
        let hours=0;
        let nightStart = moment("23:00","HH:mm");
        let nightEnd = moment("05:00","HH:mm").add(1, 'd');
        let start = moment(obj.start,"HH:mm");
        let end = moment(obj.end,"HH:mm");
        if(timeToMins(obj.start)>timeToMins(obj.end)){
            end.add(1,'d');
        }
        let nighttime = nightStart.twix(nightEnd);
        let worktime = start.twix(end);
        let differ = worktime.difference(nighttime);
        if (differ.length) {
            let difference = moment.utc(moment(obj.end,"HH:mm").diff(moment(obj.start,"HH:mm"))).format("HH:mm");
            let duration = moment.duration(difference);
            duration.subtract(differ[0].length("minutes"),'m');
            obj.night = roundToTwo(moment.utc(+duration).format('m')/60);
        }else{
            let difference = moment.utc(moment(obj.end,"HH:mm").diff(moment(obj.start,"HH:mm"))).format("HH:mm");
            let duration = moment.duration(difference);
            obj.night = roundToTwo(moment.utc(+duration).format('m')/60);;
        }
        return obj.night
    };

    return obj;
};
function Project() {
    var obj = {};
    obj.name=null;
    obj.work=null;
    obj.pay=null;
    obj.company=null;
    return obj;
}
