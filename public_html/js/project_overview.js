import {renderTools, renderTitle} from  './dataTableRender.js'
import {activateSideMenu} from  './sidemenu.js'
import {loadJoblist} from './Jobs.js'
import {getParam} from './miscHelpers.js'

let delProjURL = ''
const search = getParam('search')
let mode = 0
if (getParam('view')=='archive'){
  mode = 2
}else if(getParam('view')!=null){
  mode = 1
}


$('#delete-btn').click(() => {
  $.ajax({
    url: delProjURL,
    type: 'DELETE'
  }).done(()=>{
    $('#deleteProjectModal').modal('hide')
    table.ajax.reload()
  }).fail(()=>{
    console.error('couldn\'t delete project')
  })
})


function newCreated(data) {
  if (data.status == 'SUCCESS') {
    window.location.href = './project.php?id=' + data.project_id
  } else {
    console.error(data.message)
  }
}

function companyCreated(cmp) {
  $('#newCompany').modal('hide')
  if (cmp.status == 'SUCCESS') {
    $.ajax({
      url: 'https://filmstunden.ch/api/v01/company',
      type: 'GET',
      dataType: 'json'
    })
      .done(data => {
        $('#companylist').html('')
        data.forEach((c) =>{
          $('#companylist').append($('<option>', {
            value: c.company_id,
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

function projFinished(data) {
  if (data.status == 'SUCCESS') {
    $('#finishProjectModal').modal('hide')
    table.ajax.reload()
  } else {
    console.error(data.message)
  }
}

window.setDelete = function(id, name) {
  delProjURL = 'https://filmstunden.ch/api/v01/project/'+id
  $('#delModalTitle').html('<strong>"' + name + '"</strong> wirklich L&ouml;schen ?')
}

window.setFinish = function(id, name) {
  $('#finishProject').attr('action', 'https://filmstunden.ch/api/v01/project/'+id+'/finish')
  $('#finModalTitle').html('' + name)
}

$(()=> { // STARTFUNCTION
  activateSideMenu()
  window.table = $('#projectTable').DataTable({
    'ajax': {'url':'https://filmstunden.ch/api/v01/project?m=' + mode,
      xhrFields: {withCredentials: true},
    },
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
      'render': (data, type, row) => renderTitle(data, type, row, mode)
    },{
      'targets': 5,
      'data': 5,
      'searchable': false,
      'sortable': false,
      'render': (data, type, row) => renderTools(data, type, row, mode)
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
  $('#newProdcomp').ajaxForm({
    dataType: 'json',
    success: companyCreated
  })

  loadJoblist()

  $.ajax({
    url: 'https://filmstunden.ch/api/v01/company',
    type: 'GET',
    dataType: 'json',
  })
    .done(data => {
      data.forEach((c) =>{
        $('#companylist').append($('<option>', {
          value: c.company_id,
          text: c.name
        }))
      })
    })
    .fail(() => { console.error('companies couldnt be loaded') })
})
