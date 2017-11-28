import refreshStats from  './linechart.js'
import refreshDonut from  './donutchart.js'
import {activateSideMenu, switchTypes} from  './sidemenu.js'
import {pad} from './timeHelpers.js'

let end = new Date()
let year = end.getFullYear()
let start = new Date(year+'-01-01')
let s = start.getFullYear()+'-'+pad(start.getMonth()+1)+'-'+pad(start.getDate())
let e = end.getFullYear()+'-'+pad(end.getMonth()+1)+'-'+pad(end.getDate())

var chart = null
var donut = null

$('#switchType').change(function() {
  switchTypes($(this).prop('checked'))
})

$( '#toDate' ).change(function() {
  e=$('#toDate').val()
  console.log(e)
  chart = refreshStats(chart,s,e)
})
$( '#fromDate' ).change(function() {
  s=$('#fromDate').val()
  console.log(s)
  chart = refreshStats(chart,s,e)
})

$(function() {
  activateSideMenu()
  $('#toDate').val(e)
  $('#fromDate').val(s)
  chart = refreshStats(chart,s,e)
  refreshDonut(donut)
  $('#switchType').bootstrapToggle()
  switchTypes($('#switchType').prop('checked'))
  $('.input-daterange input').each(function() {
    $(this).datepicker({
      format: 'yyyy-mm-dd',
      todayBtn: 'linked',
      todayHighlight: true
    })
  })
})
