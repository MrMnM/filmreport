import {renderTools, renderTitle} from  './dataTableRender.js'
import {activateSideMenu} from  './sidemenu.js'


function newCreated(data) {
  if (data.message == 'SUCCESS') {
    window.location.href = './project.php?id=' + data.project_id
  } else {
    console.error(data.message)
  }
}

function companyCreated(cmp) {
  $('#newCompany').modal('hide')
  let date = new Date()
  let ts = date.getTime()
  if (cmp.message == 'SUCCESS') {
    $.ajax({
      url: 'h_company.php?_='+ts,
      type: 'POST',
      dataType: 'json',
      data: {'action':'list','fields':['id','name']}
    })
      .done(data => {
        $('#companylist').html('')
        data.forEach((c) =>{
          $('#companylist').append($('<option>', {
            value: c.id,
            text: c.name
          }))
        })
        $('#companylist').val(cmp.c_id)
      })
      .fail(() => { console.error('companies couldnt be loaded') })
  } else {
    console.error(cmp.message)
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
  let date = new Date()
  let ts = date.getTime()
  $.ajax({
    url: 'h_company.php?_='+ts,
    type: 'POST',
    dataType: 'json',
    data: {'action':'list','fields':['id','name']}
  })
    .done(data => {
      data.forEach((c) =>{
        $('#companylist').append($('<option>', {
          value: c.id,
          text: c.name
        }))
      })
    })
    .fail(() => { console.error('companies couldnt be loaded') })
})
