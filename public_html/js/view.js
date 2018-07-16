import Project from './Project.js'
import Company from './Company.js'

const p = new Project(p_id)
const c = new Company(c_id) //TODO: get this dynamically
const enc_u_id = '50947f3cdd8ec68d3517c19bb3f26753'
let u = {}


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

var loadPersonalInfo = new Promise((resolve)=>{
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user/get/'+enc_u_id,
    type: 'GET',
    dataType: 'json'
  }).done((data) => {
    u=data
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
  $('#p_pay').html()
  $('#p_name').html()
  $('#p_job').html()
  $('#sdate').html()
  $('#edate').html()

  $('#u_name').html(u.name)
  $('#u_address1').html(u.address_1)
  $('#u_address2').html(u.address_2)
  $('#u_ahv').html(u.ahv)
  $('#u_dateob').html(u.dateob)
  $('#u_tel').html(u.tel)
  $('#u_konto').html(u.konto)
  $('#u_mail').html(u.mail)
  $('#u_bvg').html(u.bvg)
  $('#c_name').html(c.name)
  $('#c_address1').html(c.address1)
  $('#c_address2').html(c.address2)

  $('#pay_additional').html()
}

$(()=>{ // JQUERY STARTFUNCTION
  Promise.all([
    c.loadCompany(),
    loadPersonalInfo,
    loadChats()
  ]).then(()=>{
    refreshView()
    $('#loading').hide()
    $('#rapport').show()
    console.log(p)

  })

  $( 'ul.nav li' ).on( 'click', function() {
    $( this ).parent().find( 'li.active' ).removeClass( 'active' )
    $( this ).addClass( 'active' )
  })
})
