// MAIN
import {activateSideMenu} from  './sidemenu.js'

$(document).ready(function() {
  activateSideMenu()
  $('#newEnquiryForm').ajaxForm({
    dataType:  'json',
    success:  newCreated
  })
})

function newCreated(data) {
  if (data.message=='SUCCESS') {
    $('#newEnquiryModal').modal('hide')
  }else{
    alert(data.message)
  }
}