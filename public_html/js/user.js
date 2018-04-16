import {activateSideMenu} from  './sidemenu.js'

let u = {}

$('tr.name')
  .mouseenter(function() {
    $(this).find('.name').html('<input type="text" id="name" value="' + u.name + '">')
  })
  .mouseleave(function() {
    u.name = $('#name').val()
    $(this).find('.name').html(u.name)
  })
$('tr.address')
  .mouseenter(function() {
    $(this).find('.address').html('<input type="text" id="address1" value="' + u.address_1 + '"><br><input type="text" id="address2" value="' + u.address_2 + '">')
  })
  .mouseleave(function() {
    u.address_1 = $('#address1').val()
    u.address_2 = $('#address2').val()
    $(this).find('.address').html(u.address_1 + '<br>' + u.address_2)
  })
$('tr.tel')
  .mouseenter(function() {
    $(this).find('.tel').html('<input type="text" id="tel" value="' + u.tel + '">')
  })
  .mouseleave(function() {
    u.tel = $('#tel').val()
    $(this).find('.tel').html(u.tel)
  })

$('tr.ahv')
  .mouseenter(function() {
    $(this).find('.ahv').html('<input type="text" id="ahv" pattern="756\.\d{4}\.\d{4}\.\d{2}" value="' + u.ahv + '">')
  })
  .mouseleave(function() {
    u.ahv = $('#ahv').val()
    $(this).find('.ahv').html(u.ahv)
  })
$('tr.dob')
  .mouseenter(function() {
    $(this).find('.dob').html('<input type="date" id="dob" value="' + u.dateob + '">')
  })
  .mouseleave(function() {
    u.dateob = $('#dob').val()
    $(this).find('.adob').html(u.dateob)
  })
$('tr.konto')
  .mouseenter(function() {
    $(this).find('.konto').html('<input type="text" id="konto" value="' + u.konto + '">')
  })
  .mouseleave(function() {
    u.konto = $('#konto').val()
    $(this).find('.konto').html(u.konto)
  })
$('tr.bvg')
  .mouseenter(function() {
    $(this).find('.bvg').html('<input type="text" id="bvg" value="' + u.bvg + '">')
  })
  .mouseleave(function() {
    u.bvg = $('#bvg').val()
    $(this).find('.bvg').html(u.bvg)
  })

jQuery('#saveInfo').click(function(event) {
  event.preventDefault()
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
    xhrFields: {withCredentials: true},
    type: 'POST',
    dataType: 'json',
    data: {
      'name': u.name,
      'tel': u.tel,
      'address_1': u.address_1,
      'address_2': u.address_2,
      'ahv': u.ahv,
      'dateob': u.dateob,
      'konto': u.konto,
      'bvg': u.bvg,
    },
    success: function(data) {
      if (data.msg == 'SUCCESS') {
        console.log(data.msg)
      } else {
        console.error(data.msg)
      }
    }
  })
})


function LoadUser() {
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
    xhrFields: {withCredentials: true},
    type: 'GET',
  })
    .done(data => {
      u=data
      Redraw()
    })
}

function Redraw() {
  $('td.tel').html(u.tel)
  $('td.name').html(u.name)
  $('td.mail').html(u.mail)
  $('td.ahv').html(u.ahv)
  $('td.dob').html(u.dateob)
  $('td.konto').html(u.konto)
  $('td.address').html(u.address_1 + '<br>' + u.address_2)
  $('td.name').html(u.name)
  $('td.bvg').html(u.bvg)
}

$(()=>{ // JQUERY STARTFUNCTION
  activateSideMenu()
  LoadUser()
})
