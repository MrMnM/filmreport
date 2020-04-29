// MAIN
import {activateSideMenu} from  './sidemenu.js'
import {loadJoblist} from './Jobs.js'

const introText =  `<p>Hey</p><p>Vielen Dank für deine Anfrage zum Dreh. Hier noch die wichtigsten Informationen, die wir soeben telefonisch besprochen haben nochmals in schriftlicher Form, damit für beide Seiten der Umfang des Projekts klar definiert ist.</p>`
const data = []
let user

$(document).ready(function() {
  activateSideMenu()
  loadJoblist()

  //Company Address
  const trumboOptions = {
  btns: [['viewHTML','bold', 'italic']],
  autogrow: true,
  semantic: false
  }

  $('#companyAddress').trumbowyg(trumboOptions)
  $('#companyAddress').closest(".trumbowyg-box").css("min-height", "100px")
  $('#companyAddress').prev(".trumbowyg-editor").css("min-height", "100px")

  $('#introtext').trumbowyg(trumboOptions)
  $('#outrotext').trumbowyg(trumboOptions)

  //Dates
  const datepickerOptions = { 
    multidate: true,
    format: 'dd/mm/yyyy'
  }
  $('#loaddate').datepicker(datepickerOptions)
  $('#shootdate').datepicker(datepickerOptions)
  $('#unloaddate').datepicker(datepickerOptions)
  $('#miscdate').datepicker(datepickerOptions)


  $.ajax({
    url: 'https://filmstunden.ch/api/v01/user',
    type: 'GET',
    dataType: 'json',
  })
  .done(data => {
    user = data
    console.log(user)
  })
  .fail(() => { console.error('companies couldnt be loaded') })



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

/*
  $('#introText').trumbowyg('html', introText)
*/
     //Initialize tooltips
     $('.nav-tabs > li a[title]').tooltip()
    
     //Wizard
     $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
         const $target = $(e.target)
         if ($target.parent().hasClass('disabled')) {
             return false
         }
     })
 
     $(".next-step").click(function(e) {
         const $active = $('.wizard .nav-tabs li.active')
         $active.next().removeClass('disabled')
         nextTab($active)
     })

     $(".prev-step").click(function(e) {
         const $active = $('.wizard .nav-tabs li.active')
         prevTab($active)
     })

     /** VALIDATION
      * 
      *     allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });
      * 
      */

})

function nextTab(elem) {
  $(elem).next().find('a[data-toggle="tab"]').click()
}
function prevTab(elem) {
  $(elem).prev().find('a[data-toggle="tab"]').click()
}


$("#companylist").change(function(){
  let selectedOption= $(this).children("option:selected").val()
  $.ajax({
    url: 'https://filmstunden.ch/api/v01/company/'+selectedOption,
    type: 'GET',
    dataType: 'json',
  })
  .done(data =>{
    let company = data.name+'<br>'+data.address_1+'<br>'+data.address_2
    $('#companyAddress').trumbowyg('html', company)
  })
})


$( "#sendMail" ).click(function(){
  data['p_name'] = $('#projectName').val()
  data['c_address'] = $('#companyAddress').trumbowyg('html')
  data['c_name'] = $('#c_name').val()
  data['c_mail'] = $('#c_mail').val()
  data['emp_job'] = $('#job').val()
  data['emp_type'] = $('#emptype').val()
  data['emp_pay'] = $('#pay').val()
  data['emp_cond'] = $('#pay2').val()
  data['dat_load'] = $('#loaddate').val().split(",").join("<br>")
  data['dat_shoot'] = $('#shootdate').val().split(",").join("<br>")
  data['dat_unload'] = $('#unloaddate').val().split(",").join("<br>")
  data['dat_misc'] = $('#miscdate').val().split(",").join("<br>")
  data['intro'] = $('#introtext').trumbowyg('html')
  data['outro'] = $('#outrotext').trumbowyg('html')
  console.log(data)
 
  $.ajax({
    method: "POST",
    url: "https://filmstunden.ch/api/v01/enquiry",
    data: { 
      u_name: user.name,
      u_address: user.address_1+'<br>'+user.address_2,
      u_mail: user.mail,
      p_name: data['p_name'], 
      c_name: data['c_name'],
      c_mail: data['c_mail'],
      c_addr: data['c_address'],
      e_job: data['emp_job'],
      e_pay: data['emp_pay'],
      e_type: data['emp_type'],
      e_cond: data['emp_cond'],
      d_load: data['dat_load'],
      d_shoot: data['dat_shoot'],
      d_uload: data['dat_unload'],
      d_misc: data['dat_misc'],
      intro: data['intro'],
      outro: data['outro']
    }
  })
    .done(function( msg ) {
      $('#enquiry').hide()
    })
    
})
