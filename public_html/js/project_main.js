import Project from './Project.js'
import { timeToMins, roundToTwo, minsToHours, formatDate } from './timeHelpers.js'
import { activateSideMenu } from './sidemenu.js'
import { loadJoblist } from './Jobs.js'
import { getParam} from './miscHelpers.js'

if(getParam('id')===null){window.location.href = './home.php'}
export function getRate(num) {return roundToTwo(p.pay / 9 * num / 100)}

const p = new Project(p_id)
var rowCounter = loadElement.length
var saved = true
const startDate = new Date($('#startDate').val())

//   BUTTONS
//-----------------------------------------------------------------------------
$('#saveButton').click(() => Save())
$('#resaveButton').click(() => Save())
$('#hoursTab').click(() => updateAll())

$('#openProjectModal').click((event) => {
  event.preventDefault()
  $('#companylist').load('https://filmstunden.ch/api/v01/company', (resp) => {
    let t = ''
    for (let i of JSON.parse(resp)) {
      t = t + '<option value=' + i['company_id'] + '>' + i['name'] + '</option>'
    }
    $('#companylist').html(t).val(p.companyId)
  })
})

$('#addRow').click(() => {
  saved = false
  addRow()
  updateBottom()
  updateSaveStatus()
})

$('#removeRow').click(() => {
  saved = false
  if (rowCounter != 0) {
    rowCounter--
    $('#r' + rowCounter).remove()
    p.rows.pop() //TODO: Make Individual Rows deletabel
  }
  updateAll()
  updateSaveStatus()
})

$('#refresh').click(() => {
  saved = false
  updateAll()
  updateSaveStatus()
})

$('#submitComment').click((event) => {
  event.preventDefault()
  let text = $('#commentText').val()
  addChat(text)
})

$('.collapseHeader').click(function(){
  $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-right')
  $(this).parent('tr').nextUntil('.cat').toggle()
})

// CHHANGEFUNCTION ------------------------------------------------------------
//-----------------------------------------------------------------------------

$('#workhours').change(function(event) {
  saved = false
  let currentField = event.target.name.substring(0, 4)
  let currentNumber = event.target.name.substring(4)
  if (currentField == 'star') {
    p.rows[currentNumber].start = event.target.value
  } else if (currentField == 'ende') {
    p.rows[currentNumber].end = event.target.value
  } else if (currentField == 'brea') {
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
  p.rows[currentNumber].date = $('#date' + currentNumber).val()
  updateAll()
  updateSaveStatus()
})

$('#comment').change(()=> {
  saved = false
  p.comment = $('#comment').val()
  updateSaveStatus()
})

// HELPERS
//------------------------------------------------------------------------------
function addRow() {
  let currentDate = startDate
  if (rowCounter > 0) {
    let currentCounter = rowCounter - 1
    let inputDate = $('[name="date' + currentCounter + '"]').val()
    currentDate = new Date(inputDate)
    currentDate.setDate(currentDate.getDate() + 1)
  }
  let date = formatDate(currentDate)
  p.addRow(rowCounter, date)
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
        <td><input type="checkbox" id="lunch${rowCounter}" name="lunc${rowCounter}"></td>
        <td><input type="number" name="cark${rowCounter}" min=0 value=0></td>
        </tr>`)
  $('#workhours').append(newRow)
  saved = false
  updateAll()
  updateSaveStatus()
  rowCounter++
}

function loadRow(currentRow) {
  let newRow = $(`
        <tr id="r${currentRow}">
        <td><input type="date" id="date${currentRow}" name="date${currentRow}" value="${p.rows[currentRow].date}"></td>
        <td><input type="text" name="work${currentRow}" size=10 list="work" value="${p.rows[currentRow].work}"></td>
        <td><input type="time" name="star${currentRow}" min=0 value="${p.rows[currentRow].start}"></td>
        <td><input type="time" name="ende${currentRow}" min=0 value="${p.rows[currentRow].end}"></td>
        <td><input type="time" name="brea${currentRow}" min=0 value="${p.rows[currentRow].break}"></td>
        <td id="wtim${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="number" id="base${currentRow}" name="base${currentRow}" min=0 step="0.1" value="${p.rows[currentRow].base}"></td>
        <td id="tent${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="elev${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="twel${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="thir${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="four${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="fift${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="sixt${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td id="nigh${currentRow}" class="hidden-xs hidden-sm hidden-md">0</td>
        <td><input type="checkbox" id="lunch${currentRow}" name="lunc${currentRow}" value="${p.rows[currentRow].lunch}"></td>
        <td><input type="number" name="cark${currentRow}" min=0 value="${p.rows[currentRow].car}"></td>
        </tr>`)
  $('#workhours').append(newRow)
  if (p.rows[currentRow].lunch) {
    $('#lunch' + currentRow).prop('checked', true)
  }
}


function Save() {
  let error=0
  $('#saveButton').hide()
  $('#resaveButton').hide()
  $('#saveButtonDisabled').show()
  let rows = JSON.stringify(p.rows)
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/project/'+p.id,
    dataType: 'json',
    data: { 'data': rows, 'add': p.addInfo, 'comment': p.comment },
    type: 'POST'
  }).done(()=>{
    saved=true
    error=0
  }).fail(()=>{
    saved=false
    error=1
  }).always(()=>{
    $('#saveButton').show()
    $('#saveButtonDisabled').hide()
    updateSaveStatus(error)
  })
}

function updateRow(row) {
  $('#wtim' + row).html(p.rows[row].getWorkHours())

  if (p.rows[row].getOvertime(10) > 0) {
    $('#tent' + row).html(p.rows[row].getOvertime(10))
  } else { $('#tent' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(11) > 0) {
    $('#elev' + row).html(p.rows[row].getOvertime(11))
  } else { $('#elev' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(12) > 0) {
    $('#twel' + row).html(p.rows[row].getOvertime(12))
  } else { $('#twel' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(13) > 0) {
    $('#thir' + row).html(p.rows[row].getOvertime(13))
  } else { $('#thir' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(14) > 0) {
    $('#four' + row).html(p.rows[row].getOvertime(14))
  } else { $('#four' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(15) > 0) {
    $('#fift' + row).html(p.rows[row].getOvertime(15))
  } else { $('#fift' + row).html('&nbsp;') }
  if (p.rows[row].getOvertime(16) > 0) {
    $('#sixt' + row).html(p.rows[row].getOvertime(16))
  } else { $('#sixt' + row).html('&nbsp;') }
  $('#nigh' + row).html(p.rows[row].getNightHours())
  $('#base' + row).val(p.rows[row].getBase())
}

function updateBottom() {
  let totalKilometers = 0
  for (let i = 0; i < p.rows.length; ++i) {
    totalKilometers += parseInt(p.rows[i].car)
  }

  let totalWorkHours = 0
  for (let i = 0; i < p.rows.length; ++i) {
    if (rowCounter > 0 && $('#wtim' + i).html() > '00:00') {
      var currentWorkHours = timeToMins(p.rows[i].getWorkHours())
      totalWorkHours = totalWorkHours + currentWorkHours
    }
  }

  let lunches = 0
  for (let i = 0; i < p.rows.length; ++i) {
    if (p.rows[i].lunch) {
      lunches += 1
    }
  }

  let totalBase = 0
  for (let i = 0; i < p.rows.length; ++i) {
    totalBase += parseFloat(p.rows[i].base)
  }

  let hours125 = 0
  for (let i = 0; i < p.rows.length; ++i) {
    hours125 += parseFloat(p.rows[i].getOvertime(10))
    hours125 += parseFloat(p.rows[i].getOvertime(11))
  }

  var hours150 = 0
  for (let i = 0; i < p.rows.length; ++i) {
    hours150 += parseFloat(p.rows[i].getOvertime(12))
    hours150 += parseFloat(p.rows[i].getOvertime(13))
  }

  let hours200 = 0
  for (let i = 0; i < p.rows.length; ++i) {
    hours200 += parseFloat(p.rows[i].getOvertime(14))
    hours200 += parseFloat(p.rows[i].getOvertime(15))
  }

  let hours250 = 0
  for (let i = 0; i < p.rows.length; ++i) {
    hours250 += parseFloat(p.rows[i].getOvertime(16))
  }

  let hours25 = 0
  for (let i = 0; i < p.rows.length; ++i) {
    hours25 += parseFloat(p.rows[i].getNightHours())
  }

  let total125 = roundToTwo(hours125 * getRate(125))
  let total150 = roundToTwo(hours150 * getRate(150))
  let total200 = roundToTwo(hours200 * getRate(200))
  let total250 = roundToTwo(hours250 * getRate(250))
  let total25 = roundToTwo(hours25 * getRate(25))
  let totalLunch = lunches * 32
  let totalCar = roundToTwo(totalKilometers * 0.7)
  let totalDay = roundToTwo(totalBase * p.pay)
  let totalAdditional = roundToTwo(totalLunch + totalCar)
  let totalOvertime = roundToTwo(total25 + total125 + total150 + total200 + total250)

  $('#payRateDay').html('&agrave; '+p.pay)
  $('#payRate125').html('&agrave; '+getRate(125))
  $('#payRate150').html('&agrave; '+getRate(150))
  $('#payRate200').html('&agrave; '+getRate(200))
  $('#payRate250').html('&agrave; '+getRate(250))
  $('#payRate25').html('&agrave; '+getRate(25))
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
  $('#totalOverall').html(roundToTwo(totalDay+totalOvertime+totalAdditional))

  if(p.rows.length>0){
    p.enddate = p.rows[p.rows.length - 1].date
  }
  p.tothour = minsToHours(totalWorkHours)
  p.totmoney = roundToTwo(totalDay + totalOvertime + totalAdditional)
}

function updateSaveStatus(error=0) {
  if (saved) {
    $('#saveInfo').show()
    $('#saveNone').hide()
    $('#saveWarning').hide()
    $('#saveError').hide()
  } else if (!saved && !error) {
    $('#saveInfo').hide()
    $('#saveNone').hide()
    $('#saveWarning').show()
    $('#saveError').hide()
  } else {
    $('#saveInfo').hide()
    $('#saveNone').hide()
    $('#saveWarning').hide()
    $('#saveError').show()
    $('#resaveButton').show()
  }
}

function loadJSON(data) {
  for (var i = 0; i < data.length; i++) {
    p.addRow(i) //DATE
    p.rows[i].loadFromJSON(data[i])
    loadRow(i)
  }
}

function updateSuccess(data) {
  if (data.message == 'SUCCESS') {
    loadProject(p)
    $('#updateProjectModal').modal('hide')
  } else {
    alert(data.message)
  }
}

function loadProject(p) {
  p.loadProject().then(() => {
    document.getElementById('comment').innerHTML = p.comment
    document.getElementById('projectName').innerHTML = p.name
    document.getElementById('title').innerHTML = p.name
    document.getElementById('projectJob').innerHTML = p.job
    document.getElementById('projectPay').innerHTML = p.pay
    document.getElementById('projectCompany').innerHTML = p.company
    //modal
    document.getElementById('p_name').value = p.name
    document.getElementById('p_job').value = p.job
    document.getElementById('p_pay').value = p.pay
  })
}

function loadPersonalInfo() {
  let p =$.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
    type: 'GET',
    dataType: 'json'
  }).done((data) => {
    document.getElementById('userName').innerHTML = data.name
    document.getElementById('userAddress').innerHTML = data.address_1 + '<br>' + data.address_2
    document.getElementById('userTel').innerHTML = data.tel
    document.getElementById('userMail').innerHTML = data.mail
    document.getElementById('userAHV').innerHTML = data.ahv
    document.getElementById('userDob').innerHTML = data.dateob
    document.getElementById('userKonto').innerHTML = data.konto
    document.getElementById('userBVG').innerHTML = data.bvg
  })
  return p
}

function loadChats() {
  p.getChats().then(() => {
    $('#chats').html(p.projHtml)
  })
  return p
}

export function updateAll() {
  for (let i = 0; i < p.rows.length; i++) {
    updateRow(i)
  }
  updateBottom()
}

function addChat(text) {
  $('.hideSend').hide()
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/chats/'+p_id,
    type: 'POST',
    xhrFields: {withCredentials: true},
    dataType: 'json',
    data: { text: text }, //TODO Correct entries here
  })
    .done(() => {
      loadChats()
      $('.hideSend').show()
    })
    .fail(() => {
      $('.hideSend').show()
    })
}

// STARTFUNCTION
// -----------------------------------------------------------------------------
$(() => { // JQUERY STARTFUNCTION
  activateSideMenu()
  if (loadElement.length == 0) {
    p.rows = []
  } else {
    loadJSON(loadElement) //TODO Load over AJAX
  }

  loadProject(p)
  loadPersonalInfo()
  loadChats()
  loadJoblist()

  setInterval(() => { if (!saved) { Save() } }, 15000)

  $('#updateProject').ajaxForm({
    dataType: 'json',
    success: updateSuccess
  })
})
