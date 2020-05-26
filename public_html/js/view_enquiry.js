import {getParam} from './miscHelpers.js'
const p_id = getParam('id')
fetch('https://filmstunden.ch/api/v01/enquiries/'+p_id)
  .then(response => response.json())
  .then(data => {
    //console.log(data)
    document.getElementById('p_title').innerHTML = data[0].p_name
    document.getElementById('text_intro').innerHTML = data[0].text.intro
    document.getElementById('c_name').innerHTML = data[0].c_name
    document.getElementById('c_address').innerHTML = data[0].c_address
    document.getElementById('u_name').innerHTML = data[0].u_name
    document.getElementById('u_address').innerHTML = data[0].u_address
    document.getElementById('e_job').innerHTML = data[0].employment.job
    document.getElementById('e_type').innerHTML = data[0].employment.type
    document.getElementById('e_pay').innerHTML = data[0].employment.pay
    document.getElementById('e_cond').innerHTML = data[0].employment.cond
    document.getElementById('d_prep_nr').innerHTML = data[0].d_prep_nr
    document.getElementById('d_prep_date').innerHTML = data[0].d_prep_date
    document.getElementById('d_shoot_nr').innerHTML = data[0].d_shoot_nr
    document.getElementById('d_shoot_date').innerHTML = data[0].d_shoot_date
    document.getElementById('d_uload_nr').innerHTML = data[0].d_uload_nr
    document.getElementById('d_uload_date').innerHTML = data[0].d_uload_date
    document.getElementById('d_misc_nr').innerHTML = data[0].d_misc_nr
    document.getElementById('d_misc_date').innerHTML = data[0].d_misc_date
    document.getElementById('text_comments').innerHTML = data[0].text.comments
    document.getElementById('text_outro').innerHTML = data[0].text.outro
    document.getElementById('end_name').innerHTML = data[0].u_name
  })