import Project from './Project.js'

const p = new Project(p_id)


function loadChats(){
  p.getChats().then(()=>{
    if(p.viewHtml!=''){
      $( '#chats' ).html(p.viewHtml)
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

function addChat(text){
  $('.hideSend').hide()
  $.ajax({
    url: 'https://api.filmstunden.ch/chats',
    xhrFields: {withCredentials: true},
    dataType: 'json',
    data : { p_id: p_id, text: text}, //TODO Move to one file and include
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

$(()=>{ // JQUERY STARTFUNCTION
  loadChats()
})
