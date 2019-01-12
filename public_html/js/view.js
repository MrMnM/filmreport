if (location.protocol !== "https:") location.protocol = "https:";

import Project from './Project.js'
import Company from './Company.js'

const us_id = "guest"

let url = new URL(window.location.href)
const p_id  = url.searchParams.get('id')
const p = new Project(p_id)
let dat = {}

function loadChats(){
  p.getChats().then(()=>{
    if(p.chatViewHtml!=''){
      $( '#chats' ).html(p.chatViewHtml)
    }else{
      let emptyComment=`
      <li class="divider"></li>
      <li>
        <div>
          <strong> </strong>
            <span class="pull-right text-muted">
            <em> </em></span>
        </div>
        <div>Noch keine Kommentare</div>
      </li>`
      $( '#chats' ).html(emptyComment)
    }
  })
}

$('#submitComment').click((e)=>{
  e.preventDefault()
  //TODO check required
  let text=$('#commentText').val()
  addChat(text)
})

$('#navAbr').click((e)=>{
  e.preventDefault()
  $('#rapport').hide()
  $('#spesen').hide()
  $('#abrechnung').show()
})
$('#navRap').click((e)=>{
  e.preventDefault()
  $('#rapport').show()
  $('#abrechnung').hide()
  $('#spesen').hide()
})
$('#navExp').click((e)=>{
  e.preventDefault()
  $('#spesen').show()
  $('#rapport').hide()
  $('#abrechnung').hide()
})


var loadViewData = new Promise((resolve)=>{
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/view/'+p_id,
    type: 'GET',
    dataType: 'json'
  }).done((data) => {
    dat=data
    resolve()
  })
})


function addChat(text){
  $('.hideSend').hide()
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/chats/'+p_id,
    xhrFields: {withCredentials: true},
    dataType: 'json',
    data : { text: text}, //TODO Move to one file and include
    type: 'POST',
  })
    .done(()=>{
      loadChats()
      $('.hideSend').show()
    })
    .fail(()=>{
      loadChats()
      $('.hideSend').show()
    })
}

function refreshView(){
  refreshRapport()
  refreshAbrechnung()
}

function refreshRapport(){
  let usr = dat[0].userData
  let cmp = dat[0].companyData
  $('#p_pay').html()
  $('#p_name').html()
  $('#p_job').html()
  $('#sdate').html()
  $('#edate').html()
  $('#u_name').html(usr.name)
  $('#u_address1').html(usr.address_1)
  $('#u_address2').html(usr.address_2)
  $('#u_ahv').html(usr.ahv)
  $('#u_dateob').html(usr.dateob)
  $('#u_tel').html(usr.tel)
  $('#u_konto').html(usr.konto)
  $('#u_mail').html(usr.mail)
  $('#u_bvg').html(usr.bvg)
  $('#c_name').html(cmp.c_name)
  $('#c_address1').html(cmp.c_address_1)
  $('#c_address2').html(cmp.c_address_2)
  $('#pay_additional').html()
}

function refreshAbrechnung(){
  let usr = dat[0].userData
  let cmp = dat[0].companyData
  let prj = dat[0].projectData
  $('#ab_date').html(prj.p_start)
  $('#ab_rappnr').html('TEST_NR')
  $('#ab_name').html(usr.name)
  $('#ab_addr1').html(usr.address_1)
  $('#ab_addr2').html(usr.address_2)
  $('#ab_proj').html(prj.p_name)
  $('#ab_fromdate').html(prj.p_start)
  $('#ab_todate').html('TEST_TO')
  $('#ab_mail').html(usr.mail)
  $('#ab_job').html(prj.p_job)
  $('#ab_tel').html(usr.tel)
  $('#ab_base').html(prj.p_gage)
  $('#ab_ahv').html(usr.ahv)
  $('#ab_dob').html(usr.dateob)
  $('#ab_company').html(cmp.name)
  $('#ab_caddr1').html(cmp.address1)
  $('#ab_caddr2').html(cmp.address2)
  $('#ab_bvg').html(usr.bvg)
  $('#ab_konto').html(usr.konto)
}

$(()=>{ // JQUERY STARTFUNCTION
  Promise.all([
    loadViewData,
    loadChats()
  ]).then(()=>{

    refreshView()
    $('#loading').hide()
    $('#rapport').show()
  })

//Navigation
  $( 'ul.nav li' ).on( 'click', function() {
    $( this ).parent().find( 'li.active' ).removeClass( 'active' )
    $( this ).addClass( 'active' )
  })
})
