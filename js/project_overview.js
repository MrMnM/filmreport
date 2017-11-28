import {renderTools, renderTitle} from  './dataTableRender.js'
import {activateSideMenu} from  './sidemenu.js'


function newCreated(data) {
  if (data.message == 'SUCCESS') {
    window.location.href = './project.php?id=' + data.project_id
  } else {
    console.error(data.message)
  }
}

function companyCreated(data) {
  if (data.message == 'SUCCESS') {
    $('#companylist').html('').load('./h_load_companies.php', ()=>{
      $('#newCompany').modal('hide')
      $('#companylist').val(data.c_id)
    })
  } else {
    console.error(data.message)
  }
}

function projDeleted(data) {
  if (data.message == 'SUCCESS') {
    $('#deleteProjectModal').modal('hide')
    table.ajax.reload()
  } else {
    console.error(data.message)
  }
}

function projFinished(data) {
  if (data.message == 'SUCCESS') {
    $('#finishProjectModal').modal('hide')
    table.ajax.reload()
  } else {
    console.error(data.message)
  }
}

window.setDelete = function(id, name) {
  $('#toDelID').val(id)
  $('#delModalTitle').html('<strong>"' + name + '"</strong> wirklich L&ouml;schen ?')
}

window.setFinish = function(id, name) {
  $('#toFinID').val(id)
  $('#finModalTitle').html('' + name)
}

$(function() {
  activateSideMenu()
  window.table = $('#projectTable').DataTable({
    'ajax': 'h_listprojects.php?fin=' + fin,
    'pagingType': 'numbers',
    'order': [
      [0, 'desc']
    ],
    'autoWidth': false,
    'columns': [
      { width: '5em' },
      { width: '12em' },
      { width: '80px' },
      { width: '20px' },
      { width: '20px' },
      { width: '30px' }
    ],
    'columnDefs': [{
      'targets': 1,
      'render': (data, type, row) => renderTitle(data, type, row, fin)
    },{
      'targets': 5,
      'data': 5,
      'searchable': false,
      'sortable': false,
      'render': (data, type, row) => renderTools(data, type, row, fin)
    }],
    'responsive': {
      'details': {
        'display': $.fn.dataTable.Responsive.display.childRowImmediate,
        'type': ''
      }
    }
  })

  if(search){
    table.search(search).draw()
  }


  new $.fn.dataTable.Responsive(table)

  $('#newProject').ajaxForm({
    dataType: 'json',
    success: newCreated
  })
  $('#finishProject').ajaxForm({
    dataType: 'json',
    success: projFinished
  })
  $('#deleteProject').ajaxForm({
    dataType: 'json',
    success: projDeleted
  })
  $('#newProdcomp').ajaxForm({
    dataType: 'json',
    success: companyCreated
  })
  $('#companylist').load('./h_load_companies.php')
})
