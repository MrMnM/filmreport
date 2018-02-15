import Project from './Project.js'

const p = new Project(p_id)

function loadChats(){
  p.getComments().then(()=>{
    if(p.viewHtml!=''){
      $( '#comments' ).html(p.viewHtml)
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
      $( '#comments' ).html(emptyComment)
    }
  })
}

$('#submitComment').click((e)=>{
  e.preventDefault()
  //TODO check required
  let text=$('#commentText').val()
  console.log('clicked', text)
  addComment(text)
})

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

$(()=>{ // JQUERY STARTFUNCTION
  loadChats()
})
