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
  $('#abrechnung').show()
})
$('#navRap').click((e)=>{
  e.preventDefault()
  $('#rapport').show()
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
  $('#p_pay').html()
  $('#p_name').html()
  $('#p_job').html()
  $('#sdate').html()
  $('#edate').html()
  $('#u_name').html(dat[0].userData.name)
  $('#u_address1').html(dat[0].userData.address_1)
  $('#u_address2').html(dat[0].userData.address_2)
  $('#u_ahv').html(dat[0].userData.ahv)
  $('#u_dateob').html(dat[0].userData.dateob)
  $('#u_tel').html(dat[0].userData.tel)
  $('#u_konto').html(dat[0].userData.konto)
  $('#u_mail').html(dat[0].userData.mail)
  $('#u_bvg').html(dat[0].userData.bvg)
  $('#c_name').html(dat[0].companyData.c_name)
  $('#c_address1').html(dat[0].companyData.c_address_1)
  $('#c_address2').html(dat[0].companyData.c_address_2)
  $('#pay_additional').html()
}

function refreshAbrechnung(){/*
  $('#ab_date').html('TEST_DATE')
  $('#ab_rappnr').html('TEST_NR')
  $('#ab_name').html(u.name)
  $('#ab_addr1').html(u.address_1)
  $('#ab_addr2').html(u.address_2)
  $('#ab_proj').html('TEST_PROJ')
  $('#ab_fromdate').html('TEST_FROM')
  $('#ab_todate').html('TEST_TO')
  $('#ab_mail').html(u.mail)
  $('#ab_job').html('TEST_JOB')
  $('#ab_tel').html(u.tel)
  $('#ab_base').html('TEST_BASE')
  $('#ab_ahv').html(u.ahv)
  $('#ab_dob').html(u.dateob)
  $('#ab_company').html(c.name)
  $('#ab_caddr1').html(c.address1)
  $('#ab_caddr2').html(c.address2)
*/}

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
