import Project from './Project.js'
import {
  timeToMins,
  roundToTwo,
  minsToHours,
  formatDate
} from './timeHelpers.js'
import {
  activateSideMenu
} from './sidemenu.js'
import {
  loadJoblist
} from './Jobs.js'
import {
  getParam
} from './miscHelpers.js'

moment().format()

export function getRate(num) {
  return roundToTwo(p.pay / 9 * num / 100)
}

//var loadElement = new Array()
const p_id = getParam('id')
if (p_id === null) {
  window.location.href = './home.php'
}
const p = new Project(p_id)
var img = ''


Dropzone.options.fileUploadZone = {
  url: 'https://filmstunden.ch/api/v01/expenses/' + p_id + '/upload',
  paramName: 'file', // The name that will be used to transfer the file
  maxFilesize: 5, // MB
  maxFiles: 1,
  acceptedFiles: 'image/*,application/pdf',
  dictDefaultMessage: '<p class="text-muted"><i class="fa fa-cloud-upload fa-5x"></i></br>Scans von Quittungen hinzufügen</p>',
  dictMaxFilesExceeded: 'Es kann nur eine Quittung pro Eintrag hochgeladen werden',
  dictInvalidFileType: 'Es können nur Bilder und PDF\'s hochgeladen werden',
  success: function(file, response) {
    uploadComplete(response)
  }
}


//   BUTTONS
//-----------------------------------------------------------------------------
$('#saveButton').click(() => Save())
$('#resaveButton').click(() => Save())
$('#openProjectModal').click(() => {
  $('#companylist').load('https://filmstunden.ch/api/v01/company', (resp) => {
    let t = ''
    for (let i of JSON.parse(resp)) {
      t += '<option value=' + i['company_id'] + '>' + i['name'] + '</option>'
    }
    $('#companylist').html(t).val(p.companyId)
  })
})
$('#addRow').click(() => {
  p.saved = false
  addRow()
  updateAll()
  updateSaveStatus()
})
$('#removeRow').click(() => {
  let rowCounter = p.rows.length
  p.saved = false
  if (rowCounter != 0) {
    rowCounter--
    $('#r' + rowCounter).remove()
    p.rows.pop() //TODO: Make Individual Rows deletabel
  }
  updateAll()
  updateSaveStatus()
})
$('#refresh').click(() => {
  document.getElementById('refresh').setAttribute('disabled', true)
  updateAll()
  document.getElementById('refresh').removeAttribute('disabled')
})
$('#submitComment').click((event) => {
  event.preventDefault()
  let text = $('#commentText').val()
  addChat(text)
})

$('.collapseHeader').click(function() {
  $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-right')
  $(this).parent('tr').nextUntil('.cat').toggle()
})

$('#saveExpenseBtn').click(() => {
  if (!$('#exp_date')[0].checkValidity()) {
    $('#exp_date_g').addClass('has-error')
    console.error('error in form')
    return
  } else {
    $('#exp_date_g').removeClass('has-error')
  }

  if (!$('#exp_name')[0].checkValidity()) {
    $('#exp_name_g').addClass('has-error')
    console.error('error in form')
    return
  } else {
    $('#exp_name_g').removeClass('has-error')
  }

  if (!$('#exp_value')[0].checkValidity()) {
    $('#exp_value_g').addClass('has-error')
    console.error('error in form')
    return
  } else {
    $('#exp_value_g').removeClass('has-error')
  }

  $.ajax({
    url: 'https://filmstunden.ch/api/v01/expenses/' + p.id,
    dataType: 'json',
    data: {
      'date': $('#exp_date').val(),
      'name': $('#exp_name').val(),
      'comment': $('#exp_comment').val(),
      'img': img,
      'val': $('#exp_value').val()
    },
    type: 'POST'
  }).done(() => {
    $('#exp_date').val(formatDate(new Date()))
    $('#exp_name').val('')
    $('#exp_comment').val('')
    $('#exp_value').val('')
    $('#addExpenseModal').modal('toggle')
    loadExpenses()
  }).fail(() => {
    console.error('fail')
  }).always(() => {

  })

})


// CHHANGEFUNCTION ------------------------------------------------------------
//-----------------------------------------------------------------------------

$('#workhours').change((event) => {
  p.saved = false
  let curField = event.target.name.substring(0, 4)
  let curNumber = event.target.name.substring(4)
  let tval = event.target.value

  switch (curField) {
    case 'star':
      p.rows[curNumber].start = tval
      break
    case 'ende':
      p.rows[curNumber].end = tval
      break
    case 'brea':
      p.rows[curNumber].break = tval
      break
    case 'date':
      p.rows[curNumber].date = tval
      break
    case 'base':
      p.rows[curNumber].manualBase = true
      p.rows[curNumber].base = tval
      break
    case 'cark':
      p.rows[curNumber].car = tval
      break
    case 'lunc':
      p.rows[curNumber].lunch ^= true
      break
    case 'work':
      p.rows[curNumber].work = tval
      break
  }
  updateRow(curNumber)
  updateSaveStatus()
})

$('#comment').change(() => {
  p.saved = false
  p.comment = $('#comment').val()
  updateSaveStatus()
})

// HELPERS
//------------------------------------------------------------------------------
function addRow() {
  let currentDate = p.startdate
  let rowCounter = p.rows.length
  if (rowCounter > 0) {
    let currentCounter = rowCounter - 1
    let inputDate = $('[name="date' + currentCounter + '"]').val()
    currentDate = new Date(inputDate)
    currentDate.setDate(currentDate.getDate() + 1)
  }
  let date = formatDate(currentDate)
  p.addRow(rowCounter, date)
}

function redrawRows() {
  $('#workhours').find('tr:gt(1)').remove()
  for (let c of p.rows) {
    let currentRow = c.id
    let newRow = `<tr id="r${currentRow}">
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
                      </tr>`
    /*  newRow = `<li class="list-group-item-project">
                <div class="col-md-1" style="border:1px solid gray">
                </div>
                <div class="col-md-5">
                  <h4 class="list-group-item-heading"> List group heading </h4>
                    <p class="list-group-item-text"> Qui diam libris ei, vidisse incorrupte at mel. His euismod salutandi dissentiunt eu. Habeo offendit ea mea. Nostro blandit sea ea, viris timeam molestiae an has. At nisl platonem eum.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                  <button type="button" class="btn btn-default btn-lg btn-block"> Vote Now! </button>
                </div>
              </li>`
    */
    $('#workhours').append(newRow)
    if (c.lunch) {
      $('#lunch' + currentRow).prop('checked', true)
    }
  }
}


function Save() {
  updateAll()
  let error = 0
  $('#saveButton').hide()
  $('#resaveButton').hide()
  $('#saveButtonDisabled').show()
  let rows = JSON.stringify(p.rows)
  p.json = rows
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/project/' + p.id,
    dataType: 'json',
    data: {
      'data': p.json,
      'add': p.addInfo,
      'comment': p.comment
    },
    type: 'POST'
  }).done(() => {
    p.saved = true
    error = 0
  }).fail(() => {
    p.saved = false
    error = 1
  }).always(() => {
    $('#saveButton').show()
    $('#saveButtonDisabled').hide()
    updateSaveStatus(error)
  })
}

function updateRow(row) {
  $('#wtim' + row).html(p.rows[row].getWorkHours())
  $('#tent' + row).html((p.rows[row].getOvertime(10) > 0) ? (p.rows[row].getOvertime(10)) : '&nbsp;')
  $('#elev' + row).html((p.rows[row].getOvertime(11) > 0) ? (p.rows[row].getOvertime(11)) : '&nbsp;')
  $('#twel' + row).html((p.rows[row].getOvertime(12) > 0) ? (p.rows[row].getOvertime(12)) : '&nbsp;')
  $('#thir' + row).html((p.rows[row].getOvertime(13) > 0) ? (p.rows[row].getOvertime(13)) : '&nbsp;')
  $('#four' + row).html((p.rows[row].getOvertime(14) > 0) ? (p.rows[row].getOvertime(14)) : '&nbsp;')
  $('#fift' + row).html((p.rows[row].getOvertime(15) > 0) ? (p.rows[row].getOvertime(15)) : '&nbsp;')
  $('#sixt' + row).html((p.rows[row].getOvertime(16) > 0) ? (p.rows[row].getOvertime(16)) : '&nbsp;')
  $('#nigh' + row).html((p.rows[row].getNightHours() > 0) ? (p.rows[row].getNightHours()) : '&nbsp;')
  $('#base' + row).val(p.rows[row].getBase())
}

function updateBottom() {
  let rowCounter = p.rows.length
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

  let hours150 = 0
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

  $('#payRateDay').html('&agrave; ' + p.pay)
  $('#payRate125').html('&agrave; ' + getRate(125))
  $('#payRate150').html('&agrave; ' + getRate(150))
  $('#payRate200').html('&agrave; ' + getRate(200))
  $('#payRate250').html('&agrave; ' + getRate(250))
  $('#payRate25').html('&agrave; ' + getRate(25))
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
  $('#totalOverall').html(roundToTwo(totalDay + totalOvertime + totalAdditional))

  if (p.rows.length > 0) {
    p.enddate = p.rows[p.rows.length - 1].date
  }
  p.tothour = minsToHours(totalWorkHours)
  p.totmoney = roundToTwo(totalDay + totalOvertime + totalAdditional)
}

function updateSaveStatus(error = 0) {
  if (p.saved) {
    $('#saveInfo').show()
    $('#saveNone').hide()
    $('#saveWarning').hide()
    $('#saveError').hide()
  } else if (!p.saved && !error) {
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
  if (data) {
    data = JSON.parse(data)
    for (var i = 0; i < data.length; i++) {
      p.addRow(i) //DATE
      p.rows[i].loadFromJSON(data[i])
    }
  }
}

function updateSuccess(data) {
  if (data.status == 'SUCCESS') {
    $('#updateProjectModal').modal('hide')
    p.loadProject().then(() => {
      updateAll()
    })
  } else {
    console.error(data.message)
  }
}

function loadProject(p) {
  p.loadProject().then(() => {
    console.log(p)
    loadJSON(p.json)
    document.getElementById('view').onclick = () => window.open('view.php?id=' + p_id)
    document.getElementById('updateProject').setAttribute('action', 'https://filmstunden.ch/api/v01/project/' + p_id + '/info')
    updateAll()
  })
}

function loadPersonalInfo() {
  let p = $.ajax({
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
    $('#chats').html(p.chatProjHtml)
  })
  return p
}

function loadExpenses() {
  p.getExpenses().then(() => {
    $('#expenseTable').html(p.expenseHtml)
  })
  return p
}

$('#expenseTable').on('click', '.delExpense', (event) => {
  console.log('nice:', event)
  deleteExpense(event.currentTarget.id)
})


function deleteExpense(e_id) {
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/expenses/' + p.id + '/' + e_id,
    dataType: 'json',
    type: 'DELETE'
  }).done(() => {
    console.log('done')
    loadExpenses()
  }).fail(() => {
    console.error('fail')
  })
}

export function updateAll() {
  document.getElementById('comment').innerHTML = p.comment
  document.getElementById('projectName').innerHTML = p.name
  document.getElementById('title').innerHTML = p.name
  document.getElementById('projectJob').innerHTML = p.job
  document.getElementById('projectPay').innerHTML = p.pay
  document.getElementById('projectCompany').innerHTML = p.company
  document.getElementById('p_name').value = p.name
  document.getElementById('p_job').value = p.job
  document.getElementById('p_pay').value = p.pay

  redrawRows()

  for (let i = 0; i < p.rows.length; i++) {
    updateRow(i)
  }

  updateBottom()
}

function addChat(text) {
  $('.hideSend').hide()
  $.ajax({
      url: 'https://filmstunden.ch/api/v01/chats/' + p_id,
      type: 'POST',
      xhrFields: {
        withCredentials: true
      },
      dataType: 'json',
      data: {
        text: text
      }, //TODO Correct entries here
    })
    .done(() => {
      loadChats()
      $('.hideSend').show()
    })
    .fail(() => {
      $('.hideSend').show()
    })
}

function uploadComplete(resp) {
  if (resp.status == 'SUCCESS') {
    img = resp.file_id
    //$('#imageupload').hide()
    //$('#imagefinder').show()
  }
}


// STARTFUNCTION
// -----------------------------------------------------------------------------
$(() => { // JQUERY STARTFUNCTION
  $('#exp_date').val(formatDate(new Date()))
  $('#workhours').basictable('start')

  activateSideMenu()

  loadProject(p)
  loadPersonalInfo()
  loadChats()
  loadJoblist()
  loadExpenses()

  //Autosave
  setInterval(() => {
    if (!p.saved) { Save()}
  }, 10000)

  //Jqueery Form
  $('#updateProject').ajaxForm({
    dataType: 'json',
    success: updateSuccess
  })
})
