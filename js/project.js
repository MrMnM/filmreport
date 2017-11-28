import Row from './Row.js'
import {timeToMins, roundToTwo, minsToHours, formatDate} from './timeHelpers.js'
import {activateSideMenu} from  './sidemenu.js'

export function getRate(num){
  return roundToTwo(basePay/9*num/100)
}

var addInfo = { 'tothour' : '00:00', 'totmoney'  : 0, 'enddate'  : '0000-00-00', 'calcBase':'SSFV_DAY', 'baseHours':9 }
var rowCounter = loadElement.length
var rowElement = new Array()
var saved = true
const basePay = $('#basePay').val()
const startDate = new Date($('#startDate').val())

//   BUTTONS
//-----------------------------------------------------------------------------
$('#saveButton').click(function(event){
  event.preventDefault()
  Save()
  updateSaveStatus()
})

$('#addRow').click(function(event){
  event.preventDefault()
  saved = false
  addRow()
  updateBottom()
  updateSaveStatus()
})
$('#removeRow').click(function(event){
  event.preventDefault()
  saved = false
  if (rowCounter != 0){
    rowCounter--
    $('#r'+rowCounter).remove()
    rowElement.pop()
  }
  updateBottom()
  updateSaveStatus()
})
$('#refresh').click(function(event){
  event.preventDefault()
  saved=false
  updateAll()
  updateSaveStatus()
})



// changed elements ------------------------------------------------------------
$( '#workhours' ).change(function() {
  saved = false
  let currentField = event.target.name.substring(0, 4)
  let currentNumber = event.target.name.substring(4)
  if (currentField == 'star') {
    rowElement[currentNumber].start = event.target.value
  } else if (currentField == 'ende') {
    rowElement[currentNumber].end = event.target.value
  } else if (currentField == 'brea'){
    rowElement[currentNumber].break = event.target.value
  } else if (currentField == 'date') {
    rowElement[currentNumber].date = event.target.value
  } else if (currentField == 'base') {
    rowElement[currentNumber].manualBase = true
    rowElement[currentNumber].base = event.target.value
  } else if (currentField == 'cark') {
    rowElement[currentNumber].car = event.target.value
  } else if (currentField == 'lunc') {
    rowElement[currentNumber].lunch ^= true
  } else if (currentField == 'work') {
    rowElement[currentNumber].work = event.target.value
  }
  rowElement[currentNumber].date = $('#date'+currentNumber).val()
  updateRow(currentNumber)
  updateBottom()
  updateSaveStatus()
})
$( '#comment' ).change(function() {
  saved = false
  updateSaveStatus()
})

//HELPERS
//------------------------------------------------------------------------------
function addRow(){
  //event.preventDefault();
  let currentDate = startDate
  if (rowCounter > 0){
    let currentCounter = rowCounter-1
    let inputDate = $('[name="date'+currentCounter+'"]').val()
    currentDate = new Date(inputDate)
    currentDate.setDate(currentDate.getDate() + 1)
  }
  rowElement.push(new Row(rowCounter))
  const newRow = $(`
        <tr id="r${rowCounter}">
        <td><input type="date" id="date${rowCounter}" name="date${rowCounter}" value="${formatDate(currentDate)}"></td>
        <td><input type="text" name="work${rowCounter}" size=10 list="work"></td>
        <td><input type="time" name="star${rowCounter}" min=0 value="00:00"></td>
        <td><input type="time" name="ende${rowCounter}" min=0 value="00:00"></td>
        <td><input type="time" name="brea${rowCounter}" min=0  value="00:00"></td>
        <td id="wtim${rowCounter}">0</td>
        <td><input type="number" id="base${rowCounter}" name="base${rowCounter}" min=0 step="0.1"></td>
        <td id="tent${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="elev${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="twel${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="thir${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="four${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="fift${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="sixt${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="nigh${rowCounter}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="checkbox" name="lunc${rowCounter}"></td>
        <td><input type="number" name="cark${rowCounter}" min=0 value=0></td>
        </tr>`)
  $('#workhours').append(newRow)
  rowElement[rowCounter].date = formatDate(currentDate)
  saved = false
  updateRow(rowCounter)
  updateSaveStatus()
  rowCounter++
}
function loadRow(currentRow){
  //rowElement.push(new Row(rowCounter));
  let newRow = $(`
        <tr id="r`+currentRow+`">
        <td><input type="date" id="date`+currentRow+'" name="date'+currentRow+'" value="'+rowElement[currentRow].date+`"></td>
        <td><input type="text" name="work`+currentRow+'" size=10 list="work" value="'+rowElement[currentRow].work+`"></td>
        <td><input type="time" name="star`+currentRow+'" min=0 value="'+rowElement[currentRow].start+`"></td>
        <td><input type="time" name="ende`+currentRow+'" min=0 value="'+rowElement[currentRow].end+`"></td>
        <td><input type="time" name="brea`+currentRow+'" min=0 value="'+rowElement[currentRow].break+`"></td>
        <td id="wtim`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="number" id="base`+currentRow+'" name="base'+currentRow+'" min=0 step="0.1" value="'+rowElement[currentRow].base+`"></td>
        <td id="tent`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="elev`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="twel`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="thir`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="four`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="fift`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="sixt`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="nigh`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="checkbox" name="lunc`+currentRow+'" value="'+rowElement[currentRow].lunch+`"></td>
        <td><input type="number" name="cark`+currentRow+'" min=0 value="'+rowElement[currentRow].car+`"></td>
        </tr>`)
  $('#workhours').append(newRow)
  //rowElement[rowCounter].date = formatDate(currentDate);
  //rowCounter++;
  //saved = false;
}
function Save() {
  let rows = JSON.stringify(rowElement)
  let additional = JSON.stringify(addInfo)
  let projectId =  $('#projectId').val()
  let comment = $('#comment').val()
  $('#saveButton').hide()
  $('#saveButtonDisabled').show()

  $.ajax({
    url: 'h_project.php',
    dataType: 'json',
    data : {'action':'save','id':projectId, 'data':rows, 'add':additional, 'comment':comment},
    type: 'POST',
    success: function(data){
      if (data.message=='SUCCESS') {
        saved = true
        updateSaveStatus()
      }else{
        saved = false
        updateSaveStatus()
      }
    },
    complete: function(){
      $('#saveButton').show()
      $('#saveButtonDisabled').hide()
    }
  })


}
function updateRow(row){
  $('#wtim'+row).html(rowElement[row].getWorkHours())

  if (rowElement[row].getOvertime(10)>0) {
    $('#tent'+row).html(rowElement[row].getOvertime(10))
  }else{ $('#tent'+row).html('0')}
  if (rowElement[row].getOvertime(11)>0) {
    $('#elev'+row).html(rowElement[row].getOvertime(11))
  }else{ $('#elev'+row).html('0')}
  if (rowElement[row].getOvertime(12)>0) {
    $('#twel'+row).html(rowElement[row].getOvertime(12))
  }else{ $('#twel'+row).html('0')}
  if (rowElement[row].getOvertime(13)>0) {
    $('#thir'+row).html(rowElement[row].getOvertime(13))
  }else{ $('#thir'+row).html('0')}
  if (rowElement[row].getOvertime(14)>0) {
    $('#four'+row).html(rowElement[row].getOvertime(14))
  }else{ $('#four'+row).html('0')}
  if (rowElement[row].getOvertime(15)>0) {
    $('#fift'+row).html(rowElement[row].getOvertime(15))
  }else{ $('#fift'+row).html('0')}
  if (rowElement[row].getOvertime(16)>0) {
    $('#sixt'+row).html(rowElement[row].getOvertime(16))
  }else{ $('#sixt'+row).html('0')}
  $('#nigh'+row).html(rowElement[row].getNightHours())
  $('#base'+row).val(rowElement[row].getBase())
}
function updateBottom(){
  let totalKilometers = 0
  for(let i = 0; i < rowElement.length; ++i){
    totalKilometers += parseInt(rowElement[i].car)
  }

  let totalWorkHours = 0
  for(let i = 0; i < rowElement.length; ++i){
    if (rowCounter > 0 && $('#wtim'+i).html()>'00:00'){
      var currentWorkHours = timeToMins(rowElement[i].getWorkHours())
      totalWorkHours = totalWorkHours + currentWorkHours
    }
  }

  let lunches = 0
  for(let i = 0; i < rowElement.length; ++i){
    if (rowElement[i].lunch){
      lunches += 1
    }
  }

  let totalBase = 0
  for(let i = 0; i < rowElement.length; ++i){
    totalBase += parseFloat(rowElement[i].base)
  }

  let hours125 = 0
  for(let i = 0; i < rowElement.length; ++i){
    hours125 += parseFloat(rowElement[i].getOvertime(10))
    hours125 += parseFloat(rowElement[i].getOvertime(11))
  }

  var hours150 = 0
  for(let i = 0; i < rowElement.length; ++i){
    hours150 += parseFloat(rowElement[i].getOvertime(12))
    hours150 += parseFloat(rowElement[i].getOvertime(13))
  }

  let hours200 = 0
  for(let i = 0; i < rowElement.length; ++i){
    hours200 += parseFloat(rowElement[i].getOvertime(14))
    hours200 += parseFloat(rowElement[i].getOvertime(15))
  }

  let hours250 = 0
  for(let i = 0; i < rowElement.length; ++i){
    hours250 += parseFloat(rowElement[i].getOvertime(16))
  }

  let hours25 = 0
  for(let i = 0; i < rowElement.length; ++i){
    hours25 += parseFloat(rowElement[i].getNightHours())
  }

  let total125 = roundToTwo(hours125 * getRate(125))
  let total150 = roundToTwo(hours150 * getRate(150))
  let total200 = roundToTwo(hours200 * getRate(200))
  let total250 = roundToTwo(hours250 * getRate(250))
  let total25 = roundToTwo(hours25 * getRate(25))
  let totalLunch = lunches*32
  let totalCar = roundToTwo(totalKilometers * 0.7)
  let totalDay = roundToTwo(totalBase*basePay)
  let totalAdditional = roundToTwo(totalLunch+totalCar)
  let totalOvertime = roundToTwo(total25 + total125 + total150 + total200 +total250)

  $('#payRateDay').html(basePay)
  $('#payRate125').html(getRate(125))
  $('#payRate150').html(getRate(150))
  $('#payRate200').html(getRate(200))
  $('#payRate250').html(getRate(250))
  $('#payRate25').html(getRate(25))
  $('#totalKilometers').html(totalKilometers)
  $('#lunches').html(lunches)
  $('#totalWorkHours').html(minsToHours(totalWorkHours))
  $('#hoursDay').html(roundToTwo(totalBase))
  $('#hours125').html(roundToTwo(hours125))
  $('#hours150').html(roundToTwo(hours150))
  $('#hours200').html(roundToTwo(hours200))
  $('#hours250').html(roundToTwo(hours250))
  $('#hours25').html(roundToTwo(hours25))
  $('#totalDay').html(totalDay)
  $('#total125').html(total125)
  $('#total150').html(total150)
  $('#total200').html(total200)
  $('#total250').html(total250)
  $('#total25').html(total25)
  $('#totalLunch').html(totalLunch)
  $('#totalCar').html(totalCar)
  $('#salaryBase').html(totalDay)
  $('#salaryOvertime').html(totalOvertime)
  $('#salaryAdditional').html(totalAdditional)

  addInfo.enddate = rowElement[rowElement.length-1].date
  addInfo.tothour = minsToHours(totalWorkHours)
  addInfo.totmoney= roundToTwo(totalDay+totalOvertime+totalAdditional)
}

function updateSaveStatus() {
  if (saved) {
    $('#saveInfo').show()
    $('#saveNone').hide()
    $('#saveWarning').hide()
  } else {
    $('#saveInfo').hide()
    $('#saveNone').hide()
    $('#saveWarning').show()
  }
}

function loadJSON(data){
  rowElement = new Array()
  for (var i = 0; i < data.length; i++) {
    rowElement.push(new Row(i))
    rowElement[i].loadFromJSON(data[i])
  }
}

function updateSuccess(data){
  if (data.message=='SUCCESS') {
    loadProjectInfo()
    $('#updateProjectModal').modal('hide')
  }else{
    alert(data.message)
  }
}

function loadProjectInfo(){
  $.post( 'h_project.php', { action: 'getinfo', us_id: us_id, p_id: p_id }).done(function( data ) {
    data = JSON.parse(data)
    $( '#projectName' ).html( data.name )
    $( '#title' ).html( data.name )
    $( '#projectJob' ).html( data.job )
    $( '#projectPay' ).html( data.pay )
    $( '#projectCompany' ).html( data.company )
  })
}

function loadPersonalInfo(){
  $.post( 'h_user.php', { action: 'get', us_id}).done(function( data ) {
    data = JSON.parse(data)
    $( '#userName' ).html( data.name )
    $( '#userAddress' ).html( data.address1+'<br>'+data.address2 )
    $( '#userTel' ).html( data.tel )
    $( '#userMail' ).html( data.mail )
    $( '#userAHV' ).html( data.ahv )
    $( '#userDob' ).html( data.dob )
    $( '#userKonto' ).html( data.konto )
    $( '#userBVG' ).html( data.bvg )
  })
}

function updateAll(){
  for (var i = 0; i < rowElement.length; i++) {
    loadRow(i)
    updateRow(i)
  }
  updateBottom()
}

$(function() {
  activateSideMenu()
  if (loadElement.length == 0){
    rowElement = new Array()
  }else{
    loadJSON(loadElement)
    updateAll()
  }

  loadProjectInfo()
  loadPersonalInfo()

  setInterval(function() {
    if(!saved){
      Save()
    }
  }, 15000)
  $('#companylist').html('').load('./h_load_companies.php', () => $('#companylist').val(company))

  $('#updateProject').ajaxForm({
    dataType:  'json',
    success: updateSuccess
  })
})

//OBJECTS -----------------------------------------------------------------------

class Project {
  constructor(){
    this.name=null
    this.work=null
    this.pay=null
    this.company=null
  }
}
