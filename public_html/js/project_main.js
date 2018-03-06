import Project from './Project.js'
import {timeToMins, roundToTwo, minsToHours, formatDate} from './timeHelpers.js'
import {activateSideMenu} from  './sidemenu.js'

export function getRate(num){
  return roundToTwo(p.pay/9*num/100)
}

const p = new Project(p_id)
var rowCounter = loadElement.length
//var rowElement = new Array()
var saved = true
//const basePay = $('#basePay').val()
//const basePay = p.pay
const startDate = new Date($('#startDate').val())

//   BUTTONS
//-----------------------------------------------------------------------------
$('#saveButton').click((event)=>{
  event.preventDefault()
  Save()
  updateSaveStatus()
})

$('#addRow').click((event)=>{
  event.preventDefault()
  saved = false
  addRow()
  updateBottom()
  updateSaveStatus()
})
$('#removeRow').click((event)=>{
  event.preventDefault()
  saved = false
  if (rowCounter != 0){
    rowCounter--
    $('#r'+rowCounter).remove()
    //rowElement.pop()
    p.rows.pop()
  }
  updateBottom()
  updateSaveStatus()
})
$('#refresh').click((event)=>{
  event.preventDefault()
  saved=false
  updateAll()
  updateSaveStatus()
})

$('#submitComment').click((event)=>{
  event.preventDefault()
  //TODO check required
  let text=$('#commentText').val()
  console.log('clicked', text)
  addComment(text)
})

// CHHANGEFUNCTION ------------------------------------------------------------
//-----------------------------------------------------------------------------

$( '#workhours' ).change(function() {
  saved = false
  let currentField = event.target.name.substring(0, 4)
  let currentNumber = event.target.name.substring(4)
  if (currentField == 'star') {
    p.rows[currentNumber].start = event.target.value
  } else if (currentField == 'ende') {
    p.rows[currentNumber].end = event.target.value
  } else if (currentField == 'brea'){
    p.rows[currentNumber].break = event.target.value
  } else if (currentField == 'date') {
    p.rows[currentNumber].date = event.target.value
  } else if (currentField == 'base') {
    p.rows[currentNumber].manualBase = true
    p.rows[currentNumber].base = event.target.value
  } else if (currentField == 'cark') {
    p.rows[currentNumber].car = event.target.value
  } else if (currentField == 'lunc') {
    p.rows[currentNumber].lunch ^= true
  } else if (currentField == 'work') {
    p.rows[currentNumber].work = event.target.value
  }
  p.rows[currentNumber].date = $('#date'+currentNumber).val()
  updateRow(currentNumber)
  updateBottom()
  updateSaveStatus()
})
$( '#comment' ).change(function() {
  p.comment = $( '#comment' ).val()
  console.log(p)
  saved = false
  updateSaveStatus()
})

// HELPERS
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
  let  date = formatDate(currentDate)
  p.addRow(rowCounter,date)
  //rowElement.push(new Row(rowCounter))
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
  //rowElement[rowCounter].date = formatDate(currentDate)
  saved = false
  updateRow(rowCounter)
  updateSaveStatus()
  rowCounter++
}

function loadRow(currentRow){
  //rowElement.push(new Row(rowCounter));
  let newRow = $(`
        <tr id="r`+currentRow+`">
        <td><input type="date" id="date`+currentRow+'" name="date'+currentRow+'" value="'+p.rows[currentRow].date+`"></td>
        <td><input type="text" name="work`+currentRow+'" size=10 list="work" value="'+p.rows[currentRow].work+`"></td>
        <td><input type="time" name="star`+currentRow+'" min=0 value="'+p.rows[currentRow].start+`"></td>
        <td><input type="time" name="ende`+currentRow+'" min=0 value="'+p.rows[currentRow].end+`"></td>
        <td><input type="time" name="brea`+currentRow+'" min=0 value="'+p.rows[currentRow].break+`"></td>
        <td id="wtim`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="number" id="base`+currentRow+'" name="base'+currentRow+'" min=0 step="0.1" value="'+p.rows[currentRow].base+`"></td>
        <td id="tent`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="elev`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="twel`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="thir`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="four`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="fift`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="sixt`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="nigh`+currentRow+`" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="checkbox" name="lunc`+currentRow+'" value="'+p.rows[currentRow].lunch+`"></td>
        <td><input type="number" name="cark`+currentRow+'" min=0 value="'+p.rows[currentRow].car+`"></td>
        </tr>`)
  $('#workhours').append(newRow)
}

function Save() {
  $('#saveButton').hide()
  $('#saveButtonDisabled').show()

  //let rows = JSON.stringify(rowElement)
  let rows= JSON.stringify(p.rows)
  $.ajax({
    url: 'h_project.php',
    dataType: 'json',
    data : {'action':'save','p_id':p.id, 'data':rows, 'add':p.addInfo, 'comment':p.comment},
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
  $('#wtim'+row).html(p.rows[row].getWorkHours())

  if (p.rows[row].getOvertime(10)>0) {
    $('#tent'+row).html(p.rows[row].getOvertime(10))
  }else{ $('#tent'+row).html('0')}
  if (p.rows[row].getOvertime(11)>0) {
    $('#elev'+row).html(p.rows[row].getOvertime(11))
  }else{ $('#elev'+row).html('0')}
  if (p.rows[row].getOvertime(12)>0) {
    $('#twel'+row).html(p.rows[row].getOvertime(12))
  }else{ $('#twel'+row).html('0')}
  if (p.rows[row].getOvertime(13)>0) {
    $('#thir'+row).html(p.rows[row].getOvertime(13))
  }else{ $('#thir'+row).html('0')}
  if (p.rows[row].getOvertime(14)>0) {
    $('#four'+row).html(p.rows[row].getOvertime(14))
  }else{ $('#four'+row).html('0')}
  if (p.rows[row].getOvertime(15)>0) {
    $('#fift'+row).html(p.rows[row].getOvertime(15))
  }else{ $('#fift'+row).html('0')}
  if (p.rows[row].getOvertime(16)>0) {
    $('#sixt'+row).html(p.rows[row].getOvertime(16))
  }else{ $('#sixt'+row).html('0')}
  $('#nigh'+row).html(p.rows[row].getNightHours())
  $('#base'+row).val(p.rows[row].getBase())
}
function updateBottom(){
  let totalKilometers = 0
  for(let i = 0; i < p.rows.length; ++i){
    totalKilometers += parseInt(p.rows[i].car)
  }

  let totalWorkHours = 0
  for(let i = 0; i < p.rows.length; ++i){
    if (rowCounter > 0 && $('#wtim'+i).html()>'00:00'){
      var currentWorkHours = timeToMins(p.rows[i].getWorkHours())
      totalWorkHours = totalWorkHours + currentWorkHours
    }
  }

  let lunches = 0
  for(let i = 0; i < p.rows.length; ++i){
    if (p.rows[i].lunch){
      lunches += 1
    }
  }

  let totalBase = 0
  for(let i = 0; i < p.rows.length; ++i){
    totalBase += parseFloat(p.rows[i].base)
  }

  let hours125 = 0
  for(let i = 0; i < p.rows.length; ++i){
    hours125 += parseFloat(p.rows[i].getOvertime(10))
    hours125 += parseFloat(p.rows[i].getOvertime(11))
  }

  var hours150 = 0
  for(let i = 0; i < p.rows.length; ++i){
    hours150 += parseFloat(p.rows[i].getOvertime(12))
    hours150 += parseFloat(p.rows[i].getOvertime(13))
  }

  let hours200 = 0
  for(let i = 0; i < p.rows.length; ++i){
    hours200 += parseFloat(p.rows[i].getOvertime(14))
    hours200 += parseFloat(p.rows[i].getOvertime(15))
  }

  let hours250 = 0
  for(let i = 0; i < p.rows.length; ++i){
    hours250 += parseFloat(p.rows[i].getOvertime(16))
  }

  let hours25 = 0
  for(let i = 0; i < p.rows.length; ++i){
    hours25 += parseFloat(p.rows[i].getNightHours())
  }

  let total125 = roundToTwo(hours125 * getRate(125))
  let total150 = roundToTwo(hours150 * getRate(150))
  let total200 = roundToTwo(hours200 * getRate(200))
  let total250 = roundToTwo(hours250 * getRate(250))
  let total25 = roundToTwo(hours25 * getRate(25))
  let totalLunch = lunches*32
  let totalCar = roundToTwo(totalKilometers * 0.7)
  let totalDay = roundToTwo(totalBase*p.pay)
  let totalAdditional = roundToTwo(totalLunch+totalCar)
  let totalOvertime = roundToTwo(total25 + total125 + total150 + total200 +total250)

  $('#payRateDay').html(p.pay)
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

  p.enddate = p.rows[p.rows.length-1].date
  p.tothour = minsToHours(totalWorkHours)
  p.totmoney= roundToTwo(totalDay+totalOvertime+totalAdditional)
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
  for (var i = 0; i < data.length; i++) {
    p.addRow(i) //DATE
    p.rows[i].loadFromJSON(data[i])
    loadRow(i)
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

function loadProject(p){
  p.loadProjectInfo().then(()=>{
    $( '#projectName' ).html( p.name )
    $( '#title' ).html( p.name )
    $( '#projectJob' ).html( p.job )
    $( '#projectPay' ).html( p.pay )
    $( '#projectCompany' ).html( p.company )
  })

}

function loadPersonalInfo(){
  $.post( 'h_user.php', { action: 'get', us_id}).done((data) => {
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

function loadChats(){
  p.getComments().then(()=>{
    $( '#comments' ).html(p.projHtml)
  })
}

function updateAll(){
  for (let i = 0; i < p.rows.length; i++) {
    updateRow(i)
  }
  updateBottom()
}

function addComment(text){
  $('.hideSend').hide()
  $.ajax({
    url: 'h_comments.php',
    dataType: 'json',
    data : { action: 'add', p_id: p_id,us_id: us_id,to_id: 'test', text: text}, //TODO Correct entries here
    type: 'POST',
  })
    .done(()=>{
      loadChats()
      $('.hideSend').show()
    })
    .fail(()=>{
      alert('Fehler')
      $('.hideSend').show()
    })
}

// STARTFUNCTION
// -----------------------------------------------------------------------------

$(()=>{ // JQUERY STARTFUNCTION
  activateSideMenu()
  if (loadElement.length == 0){
    p.rows = new Array()
  }else{
    loadJSON(loadElement)
    updateAll()
  }

  loadProject(p)
  loadPersonalInfo()
  loadChats()

  setInterval(()=>{if(!saved){ Save()}}, 15000)

  $('#companylist').load('./h_load_companies.php', (resp)=>{
    let t = ''
    for (let i of JSON.parse(resp)) {
      t = t+'<option value='+i['id']+'>'+i['name']+'</option>'
    }
    $('#companylist').html(t).val(p.companyId)
  })


  $('#updateProject').ajaxForm({
    dataType:  'json',
    success: updateSuccess
  })
})
