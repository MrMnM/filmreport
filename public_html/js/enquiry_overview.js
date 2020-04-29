// MAIN
import {activateSideMenu} from  './sidemenu.js'

$(document).ready(function() {
  activateSideMenu()
})

$('#newEnquiry').click(function() {
  window.location.href = 'enquiry.php'
  return false
})

window.table = $('#projectTable').DataTable({
  'ajax': {'url':'https://filmstunden.ch/api/v01/project?m=' + mode},
  'pagingType': 'numbers',
  'order': [
    [0, 'desc']
  ],
  'autoWidth': true,
  'responsive': {
    'details': {
      'display': $.fn.dataTable.Responsive.display.childRowImmediate,
      'type': ''
    }
  }
})

