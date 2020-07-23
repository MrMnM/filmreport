if (location.protocol !== "https:") location.protocol = "https:";

import Project from './Project.js'
import Company from './Company.js'
import {
  roundToTwo,
  addTimes,
  timeToMins,
  minsToHours,
  formatDateFilename,
  formatDateSwiss
} from "./timeHelpers.js";

const us_id = "guest"

let url = new URL(window.location.href)
const p_id = url.searchParams.get('id')
const p = new Project(p_id)
let dat = {}
let clc = {}

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
  const usr = dat[0].userData
  const cmp = dat[0].companyData
  const prj = dat[0].projectData
  clc = prj.settings

  const curDate = formatDateFilename(prj.p_end)
  const pageTitle =curDate+'_'+prj.p_name.replace(/ /g,"_")+'_'+usr.name.replace(/ /g,"_")
  document.title = pageTitle

  $('.hoursperday').html(`(${clc.hoursDay}h / Tag)`)
  $('.tohoursperday').html(`(bis ${clc.hoursDay}h/Tag)<font class="f9"><sup>1</sup></font>`)
  $('.fromhoursperday').html(`&Uuml;berstunden <font class="f9"><sup>2</sup></font><font class="f6">(${clc.hoursDay}h +)</font>`)
  $('#otText').html(`2 &Uuml;berstunden: Bei mehr als ${clc.hoursDay} h pro Tag auf der Basis von 1/${clc.hoursDay} Tag.`)

  $('.gage').html(prj.p_gage)
  $('.foodrate').html(clc.lunch)
  $('.kilrate').html(clc.car)
  $('.startdate').html(formatDateSwiss(prj.p_start))
  $('.enddate').html(formatDateSwiss(prj.p_end))
  $('.projectname').html(prj.p_name)
  $('.job').html(prj.p_job)
  $('.username').html(usr.name)
  $('.u_address1').html(usr.address_1)
  $('.u_address2').html(usr.address_2)
  $('.ahv').html(usr.ahv)
  $('.dob').html(formatDateSwiss(usr.dateob))
  $('.tel').html(`<a href="tel:${usr.tel}">${usr.tel}</a>`)
  $('.konto').html(usr.konto)
  $('.mail').html(`<a href="mailto:${usr.mail}">${usr.mail}</a>`)
  $('.bvg').html(usr.bvg)
  $('.company').html(`<a href="${cmp.url}">${cmp.c_name}</a>`)
  $('.c_addr1').html(`<a href="${cmp.url}">${cmp.c_address_1}</a>`)
  $('.c_addr2').html(`<a href="${cmp.url}">${cmp.c_address_2}</a>`)
  $('#dateFromTo').html(formatDateSwiss(prj.p_start) + ' bis ' + formatDateSwiss(prj.p_end))
  $('#pay_additional').html()
  $('#comments').html(prj.p_comment)
  $('#ab_rappnr').html(p_id.substring(0,5))

  refreshProjectList(prj,clc)
  refreshExpenseList(prj)
  refreshAbrechnungList(clc)
}

function refreshProjectList(prj,clc) {
  let overtime = [0, 0, 0, 0]
  let nighttime = 0
  let nrOfDays = 0
  let workHours = 0
  let alllunches = 0
  let allcar = 0
  let rate = [0, 0, 0, 0, 0]
  rate[0] = prj.p_gage / clc.hoursDay * clc.rate[0]
  rate[1] = prj.p_gage / clc.hoursDay * clc.rate[1]
  rate[2] = prj.p_gage / clc.hoursDay * clc.rate[2]
  rate[3] = prj.p_gage / clc.hoursDay * clc.rate[3]
  rate[4] = prj.p_gage / clc.hoursDay * clc.rate[4]

  let alltr = ''
  for (let cur of prj.p_json) {
    overtime[0] += cur.overtime[0] + cur.overtime[1]
    overtime[1] += cur.overtime[2] + cur.overtime[3]
    overtime[2] += cur.overtime[4] + cur.overtime[5]
    overtime[3] += cur.overtime[6]
    nighttime += cur.night
    nrOfDays += parseFloat(cur.base)
    workHours += Number(timeToMins(cur.workhours))
    let tr = `<tr>
    <td class="td186 td187" colspan="2" height="30">${formatDateSwiss(cur.date)}</td>
    <td class="td186 td187" colspan="2">${cur.work}</td>
    <td class="td186">${cur.start}</td>
    <td class="td186">${cur.end}</td>
    <td class="td186">${cur.break}</td>
    <td class="td186">${cur.workhours}</td>
    <td></td>
    <td class="darkyellow bold">${cur.base}</td>
    <td></td>
    <td class="${cur.overtime[0] ? 'brightorange' : 'medorange'}">${cur.overtime[0]?cur.overtime[0]:'&nbsp;'}</td>
    <td class="${cur.overtime[1] ? 'brightorange' : 'medorange'}">${cur.overtime[1]?cur.overtime[1]:'&nbsp;'}</td>
    <td class="${cur.overtime[2] ? 'brightorange' : 'medorange'}">${cur.overtime[2]?cur.overtime[2]:'&nbsp;'}</td>
    <td class="${cur.overtime[3] ? 'brightorange' : 'medorange'}">${cur.overtime[3]?cur.overtime[3]:'&nbsp;'}</td>
    <td class="${cur.overtime[4] ? 'brightorange' : 'medorange'}">${cur.overtime[4]?cur.overtime[4]:'&nbsp;'}</td>
    <td class="${cur.overtime[5] ? 'brightorange' : 'medorange'}">${cur.overtime[5]?cur.overtime[5]:'&nbsp;'}</td>
    <td class="${cur.overtime[6] ? 'brightorange' : 'medorange'}">${cur.overtime[6]?cur.overtime[6]:'&nbsp;'}</td>
    <td class="${cur.night ? 'brightorange' : 'medorange'}" colspan="2">${cur.night?cur.night:'&nbsp;'}</td>
    <td></td>
    <td></td>
    <td class="${cur.lunch?'darkgreen':'brightgreen'}">${cur.lunch ? '1':'&nbsp;'}</td>
    <td class="${cur.car?'darkgreen':'brightgreen'}">${cur.car ? cur.car:'&nbsp;'}</td>
    </tr>`
 if(cur.lunch)alllunches = alllunches+1
    alltr += tr
  }
  //----------------------------------------------------------------------------
  $('#fromhere').after(alltr);
  $('#overtime1').html(roundToTwo(overtime[0]))
  $('#overtime2').html(roundToTwo(overtime[1]))
  $('#overtime3').html(roundToTwo(overtime[2]))
  $('#overtime4').html(roundToTwo(overtime[3]))
  $('#nighttime').html(roundToTwo(nighttime))
  $('#nrOfDays').html(roundToTwo(nrOfDays))
  $('#totalWorkHours').html(minsToHours(workHours))
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
  $('#totalAdditional').html(roundToTwo(alllunches * clc.lunch + allcar * clc.car))
  $('.alllunches').html(alllunches)
  $('.allcar').html(allcar)
}

function refreshAbrechnungList(clc,refreshOnly) {
  const prj = dat[0].projectData
  let rate = [0, 0, 0, 0, 0]
  rate[0] = prj.p_gage / clc.hoursDay * clc.rate[0]
  rate[1] = prj.p_gage / clc.hoursDay * clc.rate[1]
  rate[2] = prj.p_gage / clc.hoursDay * clc.rate[2]
  rate[3] = prj.p_gage / clc.hoursDay * clc.rate[3]
  rate[4] = prj.p_gage / clc.hoursDay * clc.rate[4]
  let nrOfDays = 0
  let totalBase = 0
  let overtime = [0, 0, 0, 0]
  let nighttime = 0
  let alllunches = 0
  let allcar = 0
  let alltr = ''
  for (let cur of prj.p_json) {
    nrOfDays += parseFloat(cur.base) // TODO: move these two into one function
    totalBase += prj.p_gage * cur.base
    overtime[0] += cur.overtime[0] + cur.overtime[1]
    overtime[1] += cur.overtime[2] + cur.overtime[3]
    overtime[2] += cur.overtime[4] + cur.overtime[5]
    overtime[3] += cur.overtime[6]
    nighttime += cur.night

    if(!refreshOnly){
      let tr = `<tr>
      						<td class="bryellow f8">${formatDateSwiss(cur.date)}</td>
      						<td class="bryellow f8">${cur.work}</td>
      						<td class="ab_darkyellow f8">${cur.base}</td>
      						<td class="f8">&nbsp;</td>
      						<td class="bryellow f8">Tag</td>
      						<td class="bryellow f8">${prj.p_gage}</td>
      						<td class="bryellow f8" colspan="2">${prj.p_gage*cur.base}</td>
      					</tr>`
      alltr += tr
    }
  }

  const ferienzulage = totalBase * clc.ferien
  const totalLohn = totalBase + ferienzulage
  const totalUeberstunden = overtime[0] * rate[0] + overtime[1] * rate[1] + overtime[2] * rate[2] + overtime[3] * rate[3] + nighttime * rate[4]
  const totalBrutto = totalLohn + totalUeberstunden
  const ahv = totalBrutto * clc.ahv
  const alv = totalBrutto * clc.alv
  const bvg = totalBrutto * clc.bvg
  const uvg = totalBrutto * clc.uvg
  const totalAbz = ahv+alv+bvg+uvg
  const totalNetto = totalBrutto - totalAbz
  const addExpense = calculateExpenses(prj)
  const totalSpesen = allcar*clc.car + alllunches*clc.lunch + addExpense
  const total = totalNetto + totalSpesen

  if(!refreshOnly) {$('#abr_baselist').after(alltr)}

  $('.totalBase').html(totalBase)
  $('#totalDays').html(roundToTwo(nrOfDays))
  $('#ferienzulage').html(roundToTwo(ferienzulage))
  $('#lohnundfz').html(totalLohn)
  $('#abr_ot0').html(roundToTwo(overtime[0]))
  $('#abr_ot1').html(roundToTwo(overtime[1]))
  $('#abr_ot2').html(roundToTwo(overtime[2]))
  $('#abr_ot3').html(roundToTwo(overtime[3]))
  $('#abr_nt').html(roundToTwo(nighttime))
  $('#rate0').html(roundToTwo(rate[0]))
  $('#rate1').html(roundToTwo(rate[1]))
  $('#rate2').html(roundToTwo(rate[2]))
  $('#rate3').html(roundToTwo(rate[3]))
  $('#rate4').html(roundToTwo(rate[4]))
  $('#ot0').html(roundToTwo(overtime[0] * rate[0]))
  $('#ot1').html(roundToTwo(overtime[1] * rate[1]))
  $('#ot2').html(roundToTwo(overtime[2] * rate[2]))
  $('#ot3').html(roundToTwo(overtime[3] * rate[3]))
  $('#ot4').html(roundToTwo(nighttime * rate[4]))
  $('#totalUeberstunden').html(roundToTwo(totalUeberstunden))
  $('.totalBrutto').html(roundToTwo(totalBrutto))
  $('#abzAhv').html(roundToTwo(ahv))
  $('#abzAlv').html(roundToTwo(alv))
  $('#abzBvg').html(roundToTwo(bvg))
  $('#abzUvg').html(roundToTwo(uvg))
  $('#totalAbz').html(roundToTwo(totalAbz))
  $('#totalNetto').html(roundToTwo(totalNetto))
  $('#totalSpesen').html(roundToTwo(totalSpesen))
  $('#total').html(roundToTwo(total))
}

function refreshExpenseList(prj) {
  let totalExpense = calculateExpenses(prj)
  let expenseList = ''
  if (prj.expenses.length != 0) {
    for (let cur of prj.expenses) {
      if(cur.img != ''){
        expenseList += `<tr>
                        <td class="blue fs8">${formatDateSwiss(cur.date)}</td>
                        <td class="blue fs8"><a href="#" data-featherlight="https://filmstunden.ch/upload/${cur.img}">${cur.name}</a></td>
                        <td class="blue fs8" colspan="6"><a href="#" data-featherlight="https://filmstunden.ch/upload/${cur.img}">${cur.comment}</a></td>
                        <td class="blue fs8 bold">${cur.value}</td>
                        </tr>`
      }else{
        expenseList += `<tr>
                        <td class="blue fs8">${formatDateSwiss(cur.date)}</td>
                        <td class="blue fs8">${cur.name}</td>
                        <td class="blue fs8" colspan="6">${cur.comment}</td>
                        <td class="blue fs8 bold">${cur.value}</td>
                        </tr>`
      }
    }
  } else {
    expenseList = '<td class="blue fs8" height="30" style="text-align: center; font-weight: 700" colspan="9">Keine zus&auml;tzlichen Spesen angegeben</td>'
  }
  $('#expenseList').after(expenseList)
  $('.additionalExpense').html(roundToTwo(totalExpense))
}

function calculateExpenses(prj){
  let totalExpense = 0.0
  if (prj.expenses.length != 0) {
    for (let cur of prj.expenses) {
      totalExpense += parseFloat(cur.value)
    }
  }
  return totalExpense
}

document.getElementById("percALV").onchange = ()=> {
  clc.alv = parseFloat(document.getElementById("percALV").value*0.01);
  refreshAbrechnungList(clc,true)
}

document.getElementById("percNBU").onchange = ()=> {
   clc.uvg = parseFloat(document.getElementById("percNBU").value*0.01);
   refreshAbrechnungList(clc,true)
 }

$(() => { // JQUERY STARTFUNCTION
  Promise.all([
    loadViewData,
    loadChats()
  ]).then(() => {
    refreshView()
    $("#exceldownload").attr("href", "https://filmstunden.ch/api/v01/view/download/" + p_id + "?format=xlsx");
    $("#pdfdownload").attr("href", "https://filmstunden.ch/api/v01/view/download/" + p_id + "?format=pdf");
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
