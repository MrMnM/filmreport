export function loadJoblist() {
  $.ajax({
    url: 'https://api.filmstunden.ch/jobs',
    xhrFields: { withCredentials: true },
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
}
