if (location.protocol !== "https:") location.protocol = "https:";

import Project from './Project.js'
import Company from './Company.js'
import {
  roundToTwo,
  addTimes
} from "./timeHelpers.js";

const us_id = "guest"

let url = new URL(window.location.href)
const p_id = url.searchParams.get('id')
const p = new Project(p_id)
let dat = {}


function loadChats() {
  p.getChats().then(() => {
    if (p.chatViewHtml != '') {
      $('#chats').html(p.chatViewHtml)
    } else {
      let emptyComment = `
      <li class="divider"></li>
      <li>
        <div>
          <strong> </strong>
            <span class="pull-right text-muted">
            <em> </em></span>
        </div>
        <div>Noch keine Kommentare</div>
      </li>`
      $('#chats').html(emptyComment)
    }
  })
}

$('#submitComment').click((e) => {
  e.preventDefault()
  //TODO check required
  let text = $('#commentText').val()
  addChat(text)
})

$('#navAbr').click((e) => {
  e.preventDefault()
  $('#rapport').hide()
  $('#spesen').hide()
  $('#abrechnung').show()
})
$('#navRap').click((e) => {
  e.preventDefault()
  $('#rapport').show()
  $('#abrechnung').hide()
  $('#spesen').hide()
})
$('#navExp').click((e) => {
  e.preventDefault()
  $('#spesen').show()
  $('#rapport').hide()
  $('#abrechnung').hide()
})


var loadViewData = new Promise((resolve, reject) => {
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/view/' + p_id,
    type: 'GET',
    dataType: 'json'
  }).done((data) => {
    dat = data
    if (!dat[0].companyData) {
      reject()
    }
    resolve()
  })
})


function addChat(text) {
  $('.hideSend').hide()
  $.ajax({
      url: 'https://filmstunden.ch/api/v01/chats/' + p_id,
      xhrFields: {
        withCredentials: true
      },
      dataType: 'json',
      data: {
        text: text
      }, //TODO Move to one file and include
      type: 'POST',
    })
    .done(() => {
      loadChats()
      $('.hideSend').show()
    })
    .fail(() => {
      loadChats()
      $('.hideSend').show()
    })
}

function refreshView() {
  let usr = dat[0].userData
  let cmp = dat[0].companyData
  let prj = dat[0].projectData

  document.title = prj.p_name;

  $('.gage').html(prj.p_gage)
  $('.startdate').html(prj.p_start)
  $('.enddate').html(prj.p_end)
  $('.projectname').html(prj.p_name)
  $('.job').html(prj.p_job)
  $('.username').html(usr.name)
  $('.u_address1').html(usr.address_1)
  $('.u_address2').html(usr.address_2)
  $('.ahv').html(usr.ahv)
  $('.dob').html(usr.dateob)
  $('.tel').html(usr.tel)
  $('.konto').html(usr.konto)
  $('.mail').html(usr.mail)
  $('.bvg').html(usr.bvg)
  $('.company').html(cmp.c_name)
  $('.c_addr1').html(cmp.c_address_1)
  $('.c_addr2').html(cmp.c_address_2)

  $('#dateFromTo').html(prj.p_start + ' bis ' + prj.p_end)
  $('#pay_additional').html()
  $('#ab_rappnr').html('TEST_NR')

  refreshProjectList(prj)
  refreshExpenseList(prj)
}



function refreshProjectList(prj) {
  let overtime = [0, 0, 0, 0]
  let nighttime = 0
  let nrOfDays = 0
  let workHours = '00:00'
  let alllunches = 0
  let allcar = 0

  for (let cur of prj.p_json) {
    overtime[0] += cur.overtime[0] + cur.overtime[1]
    overtime[1] += cur.overtime[2] + cur.overtime[3]
    overtime[2] += cur.overtime[4] + cur.overtime[5]
    overtime[3] += cur.overtime[6]
    nighttime += cur.night
    nrOfDays += parseFloat(cur.base)
    workHours = addTimes(workHours, cur.workhours)
    let tr = `<tr>
    <td class="td186 td187" colspan="2" height="30">${cur.date}</td>
    <td class="td186 td187">${cur.work}</td>
    <td></td>
    <td class="td186">${cur.start}</td>
    <td class="td186">${cur.end}</td>
    <td class="td186">${cur.break}</td>
    <td class="td186">${cur.workhours}</td>
    <td></td>
    <td class="darkyellow bold">${cur.base}</td>
    <td></td>
    <td class="brightorange" ${cur.overtime[0] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[0]?cur.overtime[0]:'&nbsp;'}</td>
    <td class="brightorange" ${cur.overtime[1] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[1]?cur.overtime[1]:'&nbsp;'}</td>
    <td class="brightorange" ${cur.overtime[2] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[2]?cur.overtime[2]:'&nbsp;'}</td>
    <td class="brightorange" ${cur.overtime[3] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[3]?cur.overtime[3]:'&nbsp;'}</td>
    <td class="brightorange" ${cur.overtime[4] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[4]?cur.overtime[4]:'&nbsp;'}</td>
    <td class="brightorange" ${cur.overtime[5] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[5]?cur.overtime[5]:'&nbsp;'}</td>
    <td class="brightorange" colspan="2" ${cur.overtime[6] ? '' : 'style="background:#FFF2E5"'}>${cur.overtime[6]?cur.overtime[6]:'&nbsp;'}</td>
    <td class="brightorange" colspan="2" ${cur.night ? '' : 'style="background:#FFF2E5"'}>${cur.night?cur.niight:'&nbsp;'}</td>
    <td></td>
    <td class="${cur.lunch?'darkgreen':'brightgreen'}">${cur.lunch ? '1':'&nbsp;'}</td>
    <td class="${cur.car?'darkgreen':'brightgreen'}">${cur.car ? cur.car:'&nbsp;'}</td>
    </tr>`
    $('#fromhere').after(tr);
  }

  $('#overtime1').html(overtime[0])
  $('#overtime2').html(overtime[1])
  $('#overtime3').html(overtime[2])
  $('#overtime4').html(overtime[3])
  $('#nighttime').html(nighttime)
  $('#nrOfDays').html(nrOfDays)
  $('#totalWorkHours').html(workHours)

  let hoursDay = 9
  let rate = [0, 0, 0, 0, 0]
  rate[0] = prj.p_gage / hoursDay * 1.25
  rate[1] = prj.p_gage / hoursDay * 1.50
  rate[2] = prj.p_gage / hoursDay * 2.00
  rate[3] = prj.p_gage / hoursDay * 2.50
  rate[4] = prj.p_gage / hoursDay * 0.25

  $('#rate125').html(roundToTwo(rate[0]))
  $('#rate150').html(roundToTwo(rate[1]))
  $('#rate200').html(roundToTwo(rate[2]))
  $('#rate250').html(roundToTwo(rate[3]))
  $('#rate25').html(roundToTwo(rate[4]))
  $('#pay125').html(roundToTwo(rate[0] * overtime[0]))
  $('#pay150').html(roundToTwo(rate[1] * overtime[1]))
  $('#pay200').html(roundToTwo(rate[2] * overtime[2]))
  $('#pay250').html(roundToTwo(rate[3] * overtime[3]))
  $('#pay25').html(roundToTwo(rate[4] * nighttime))
  $('#payBase').html(roundToTwo(prj.p_gage * nrOfDays))

  $('#totalBase').html(roundToTwo(prj.p_gage * nrOfDays))
  $('#totalOvertime').html(roundToTwo(rate[0] * overtime[0] + rate[1] * overtime[1] + rate[2] * overtime[2] + rate[3] * overtime[3] + rate[4] * nighttime))
  $('#totalAdditional').html(roundToTwo(alllunches * 32 + allcar * 0.7))

  $('#alllunches').html(alllunches)
  $('#allcar').html(allcar)

}

function refreshExpenseList(prj) {
  let expenseList = `<td class="blue fs8" height="30" style="text-align: center; font-weight: 700" colspan="9">Keine zus&auml;tzlichen Spesen angegeben</td>`

  if (prj.expenses.length != 0) {
      console.log(prj.expenses)
    for (let cur of prj.expenses) {
      expenseList = `<td class="blue fs8"><a href="https://filmstunden.ch/upload/${cur.img}">${cur.date}</a></td>
                      <td class="blue fs8"><a href="https://filmstunden.ch/upload/${cur.img}">${cur.name}</a></td>
                      <td class="blue fs8" colspan="6"><a href="https://filmstunden.ch/upload/${cur.img}">${cur.comment}</a></td>
                      <td class="blue fs8 bold"><a href="https://filmstunden.ch/upload/${cur.img}">${cur.value}</a></td>`
      $('#expenseList').after(expenseList);

    }

  } else {
    $('#expenseList').after(expenseList)
  }
}



$(() => { // JQUERY STARTFUNCTION
  Promise.all([
    loadViewData,
    loadChats()
  ]).then(() => {
    $("#exceldownload").attr("href", "https://filmstunden.ch/api/v01/view/download/" + p_id + "?format=xlsx");
    $("#pdfdownload").attr("href", "https://filmstunden.ch/api/v01/view/download/" + p_id + "?format=pdf");
    refreshView()
    $('#loading').hide()
    $('#rapport').show()
  }).catch(() => {
    $('#loading').hide()
    $('#noProject').show() // some coding error in handling happened
  });
  //Navigation
  $('ul.nav li').on('click', function() {
    $(this).parent().find('li.active').removeClass('active')
    $(this).addClass('active')
  })
})
