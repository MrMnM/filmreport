import {activateSideMenu} from  './sidemenu.js'

let u = {}


$('#editInfo').click(function(event) {event.preventDefault(); activateEdit()})
$('#editPassword').click(function(event) {event.preventDefault(); editPassword()})
$('#saveInfo').click(function(event) {event.preventDefault(); saveUser()})

function activateEdit(){
  document.getElementById('editBtn').style.display = "none"
  document.getElementById('saveBtn').style.display = "block"
  let readOnlyElements = document.getElementsByClassName("readonly")
  for (let i = 0; i < readOnlyElements.length; i++) {
    readOnlyElements[i].readOnly = false
  } 
}

function disableEdit(){
  document.getElementById('editBtn').style.display = "block"
  document.getElementById('saveBtn').style.display = "none"
  let readOnlyElements = document.getElementsByClassName("readonly")
  for (let i = 0; i < readOnlyElements.length; i++) {
    readOnlyElements[i].readOnly = true
  } 
}

function saveUser(){
  readData()
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
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
        disableEdit()
        LoadUser()
      } else {
        console.error(data.msg)
      }
    }
  })
}


function LoadUser() {
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
    xhrFields: {withCredentials: true},
    type: 'GET',
  })
    .done(data => {
      u=data
      disableEdit()
      Redraw()
    })
}

function readData(){
  u.tel = document.getElementById('tel').value 
  u.name = document.getElementById('name').value
  u.ahv = document.getElementById('ahv').value
  u.dateob = document.getElementById('dob').value 
  u.konto = document.getElementById('konto').value 
  u.address_1 = document.getElementById('address1').value
  u.address_2 = document.getElementById('address2').value
  u.name = document.getElementById('name').value
  u.bvg = document.getElementById('bvg').value
}

function Redraw() {
  document.getElementById('tel').value = u.tel
  document.getElementById('name').value = u.name
  document.getElementById('mail').value = u.mail
  document.getElementById('ahv').value = u.ahv
  document.getElementById('dob').value = u.dateob
  document.getElementById('konto').value = u.konto
  document.getElementById('address1').value = u.address_1 
  document.getElementById('address2').value = u.address_2
  document.getElementById('name').value = u.name
  document.getElementById('bvg').value = u.bvg
}

function editPassword(){
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user/setpw',
    type: 'POST',
    dataType: 'json',
    data: {
      'mail': document.getElementById('mail').value,
      'curpw': document.getElementById('curpw').value,
      'newpw1': document.getElementById('newpw1').value,
      'newpw2': document.getElementById('newpw2').value,
    },
    success: function(data) {
      //console.log(data)
      document.getElementById('curpw').value = ""
      document.getElementById('newpw1').value = ""
      document.getElementById('newpw2').value = ""
      if (data.status == 'SUCCESS') {
        //console.log(data.msg)
        document.getElementById('PWsuccess').innerHTML = data.msg
        document.getElementById('PWsuccess').style.display = "block"
        setTimeout(function() {
          document.getElementById('PWsuccess').style.display = "none"
        }, 5000);
      } else {
        console.error(data.msg)
        document.getElementById('PWerror').innerHTML = data.msg
        document.getElementById('PWerror').style.display = "block"
        setTimeout(function() {
          document.getElementById('PWerror').style.display = "none"
        }, 5000);
      }
    }
  })

}

$(()=>{ // JQUERY STARTFUNCTION
  activateSideMenu()
  LoadUser()
  document.getElementById('table').style.display = "block"
  document.getElementById('loading').style.display = "none"


})
