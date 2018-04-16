export function loadJoblist() {
  let p = $.ajax({
    url: 'https://filmstunden.ch/api/v01/jobs',
    type: 'GET',
    dataType: 'json'
  }).done(data => {
    var dataList = document.getElementById('joblist')
    data.forEach(item => {
      var option = document.createElement('option')
      option.value = item
      dataList.appendChild(option)
    })
  })
  return p
}
