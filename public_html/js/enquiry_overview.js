// MAIN
import {activateSideMenu} from  './sidemenu.js'
import {renderEnquiry, renderEnquiryTools} from  './dataTableRender.js'


$(document).ready(function() {
  activateSideMenu()
})

$('#newEnquiry').click(function() {
  window.location.href = 'enquiry.php'
  return false
})

window.table = $('#projectTable').DataTable({
  'ajax': {'url':'https://filmstunden.ch/api/v01/enquiries'},
  'pagingType': 'numbers',
  'order': [
    [0, 'desc']
  ],
  'autoWidth': false,
  'columns': [
    { width: '100px' },
    { width: '5em' },
    { width: '150px' },
    { width: '30px' }
  ],
  'responsive': {
    'details': {
      'display': $.fn.dataTable.Responsive.display.childRowImmediate,
      'type': ''
    }
  },
  'columnDefs': [{
    'targets': 1,
    'render': (data, type, row) => renderEnquiry(data, type, row)
  },{
    'targets': 3,
    'data': 3,
    'searchable': false,
    'sortable': false,
    'render': (data, type, row) => renderEnquiryTools(data, type, row)
  }]
})

