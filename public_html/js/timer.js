// MAIN
import {activateSideMenu} from  './sidemenu.js'

var clickedDel = false
var mode = 9
var t = {}
t.id = null
t.name = null
t.data = new Array()

function SetActive(id,name) {
  if (clickedDel==true) {
    return
  }else{
    t.id     = id
    t.name = name
    $('#activetimer').show()
    $('#selector').hide()
    $('#projectTitle').html(t.name)
    $('#stop').prop('disabled', true)
    $('#pause').prop('disabled', true)
    //console.log(t);
  }
}

function  LoadTimer(id){
  $.ajax({
    url: 'h_timer.php',
    dataType: 'json',
    data : {'action':'load','id':id},
    type: 'POST',
    success: function(data){
      console.log(data)
    }
  })
}

jQuery('#shoot').click(function(event){
  $('#stop').prop('disabled', false)
  $('#pause').prop('disabled', false)
  $('#load').show()
  $('#shoot').hide()
  $('#drive').show()
  t.mode=0
  event.preventDefault()
  var time = new Date()
  startStamp = time
  var dat = 'sh_'+time
  t.data.push(dat)
  saveTimer('shoot', time)
  updateDisplay()
})

jQuery('#load').click(function(event){
  event.preventDefault()
  $('#stop').prop('disabled', false)
  $('#pause').prop('disabled', false)
  $('#load').hide()
  $('#shoot').show()
  $('#drive').show()
  t.mode=1
  var time = new Date()
  startStamp = time
  var dat = 'lo_'+time
  t.data.push(dat)
  saveTimer('load',time)
  updateDisplay()
})

jQuery('#drive').click(function(event){
  $('#stop').prop('disabled', false)
  $('#pause').prop('disabled', false)
  $('#load').show()
  $('#shoot').show()
  $('#drive').hide()
  t.mode=2
  event.preventDefault()
  var time = new Date()
  startStamp = time
  var dat = 'dr_'+time
  t.data.push(dat)
  console.log(t)
  saveTimer('drive',time)
  updateDisplay()
})

jQuery('#pause').click(function(event){
  event.preventDefault()
  $('#pause').prop('disabled', true)
  $('#load').show()
  $('#shoot').show()
  $('#drive').show()
  t.mode=3
  var time = new Date()
  startStamp = time
  var dat = 'pa_'+time
  t.data.push(dat)
  saveTimer('pause',time)
  updateDisplay()
})

jQuery('#stop').click(function(event){
  event.preventDefault()
  $('#stop').prop('disabled', true)
  $('#pause').prop('disabled', true)
  $('#load').show()
  $('#shoot').show()
  $('#drive').show()
  t.mode=9
  var time = new Date()
  var dat = 'st_'+time
  t.data.push(dat)
  //console.log(t);
  saveTimer('stop',time)
  updateDisplay()
})

function updateDisplay()
{
  switch (t.mode) {
  case 0:
    $('#timerCount').html('<i class="fa fa-video-camera"></i> '+updateTimer())
    break
  case 1:
    $('#timerCount').html('<i class="fa fa-truck"></i> '+updateTimer())
    break
  case 2:
    $('#timerCount').html('<i class="fa fa-car"></i> '+updateTimer())
    break
  case 3:
    $('#timerCount').html('<i class="fa fa-pause"></i> '+updateTimer())
    break
  default:
    $('#timerCount').html('')
  }
}

function updateTimer() {
  newDate = new Date()
  newStamp = newDate.getTime()
  var diff = Math.round((newStamp-startStamp)/1000)
  var d = Math.floor(diff/(24*60*60))
  diff = diff-(d*24*60*60)
  var h = Math.floor(diff/(60*60))
  diff = diff-(h*60*60)
  var m = Math.floor(diff/(60))
  diff = diff-(m*60)
  var s = diff
  counter =  pad(h)+':'+pad(m)+':'+pad(s)
  //console.log(counter);
  return counter
}

function saveTimer(act,time){
  $.ajax({
    url: 'h_timer.php',
    dataType: 'json',
    data : {'action':'update','id':t.id, 'a':act, 't':time},
    type: 'POST',
    success: function(data){
      if (data.message=='SUCCESS') {
        console.log('good')
      }else{
        console.log('error')
      }
    }
  })
}

function deleteTimer(id){
  clickedDel = true
  $.ajax({
    url: 'h_timer.php',
    dataType: 'json',
    data : {'action':'delete','id':id},
    type: 'POST',
    success: function(data){
      if (data.message=='SUCCESS') {
        $.post( 'h_timer.php', { action: 'gettimers'})
          .done(function( data ) {
            $( '#timers' ).html( data )
            clickedDel = false
          })
      }else{
        clickedDel = false
      }
    }
  })
}

function newCreated(data) {
  if (data.message=='SUCCESS') {
    $('#newTimerModal').modal('hide')
    SetActive(data.id,data.name)
  }else{
    alert(data.message)
  }
}

$(document).ready(function() {
  alert('Noch nicht implementiert!')
  activateSideMenu()
  $('#newTimerForm').ajaxForm({
    dataType:  'json',
    success:  newCreated
  })

  $.post( 'h_timer.php', { action: 'gettimers'})
    .done(function( data ) {
      $( '#timers' ).html( data )
    })

  setInterval(function(){
    updateDisplay()
  }, 1000)

})
