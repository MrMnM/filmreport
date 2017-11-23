import refreshStats from  "./linechart.js";
import {activateSideMenu, switchTypes} from  "./sidemenu.js";
const d = new Date()
var y = d.getFullYear()
var m = d.getMonth() + 1
var chart = null



$('#switchType').change(function() {
      switchTypes($(this).prop('checked'))
})



$('#fromDateEarly').click(function(event){
console.log("fEarly");
});

$('#fromDateEarly').click(function(event){
console.log("fEarly");
});
$('#fromDateLate').click(function(event){
console.log("fLate");
});
$('#toDateEarly').click(function(event){
m--
chart = refreshStats(chart,y,m)
});
$('#toDateLate').click(function(event){
m++
chart = refreshStats(chart,y,m)
});




$(function() {
activateSideMenu();
refreshStats(chart,y,m)
$('#switchType').bootstrapToggle();
switchTypes($('#switchType').prop('checked'))
});
