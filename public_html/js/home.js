import {refreshStats, refreshDonut, refreshYearView} from  './stats.js'
import {activateSideMenu, switchTypes} from  './sidemenu.js'
import {pad} from './timeHelpers.js'

let chart = null
let donut = null
let yearView = null

let e=0
let s=0
if (sessionStorage.getItem('interval')) {
  let i = sessionStorage.getItem('interval').split(';')
  s = i[0]
  e = i[1]
}else{
  let end = new Date()
  let start = new Date(end.getFullYear()+'-01-01')
  s = start.getFullYear()+'-'+pad(start.getMonth()+1)+'-'+pad(start.getDate())
  e = end.getFullYear()+'-'+pad(end.getMonth()+1)+'-'+pad(end.getDate())
}

$('#switchType').change(function() {
  switchTypes($(this).prop('checked'))
})

$( '#toDate' ).change(function() {
  e=$('#toDate').val()
  sessionStorage.setItem('interval',s+';'+e)
  chart = refreshStats(chart,s,e)
  donut = refreshDonut(donut,s,e)

})

$( '#fromDate' ).change(function() {
  s=$('#fromDate').val()
  sessionStorage.setItem('interval',s+';'+e)
  chart = refreshStats(chart,s,e)
  donut = refreshDonut(donut,s,e)

})

// --------------------------------------------------------
$(()=> { // STARTFUNCTION
  activateSideMenu()
  $('#toDate').val(e)
  $('#fromDate').val(s)
  $('#switchType').bootstrapToggle()
  switchTypes($('#switchType').prop('checked'))
  $('.input-daterange input').each(function() {
    $(this).datepicker({
      format: 'yyyy-mm-dd',
      todayBtn: 'linked',
      todayHighlight: true
    })
  })
  yearView = refreshYearView(yearView)
  donut = refreshDonut(donut,s,e)
  chart = refreshStats(chart,s,e)
  chart.redraw()
})
