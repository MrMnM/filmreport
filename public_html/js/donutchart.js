export default function refreshDonut (donut) {
  if (donut === null) {
    donut = Morris.Donut({
      element: 'donut', 
      data: [{
        label: 'loading...',
        value: 100
      }]
    }).on('click', function(i, row) {
      window.location.href = './project_overview.php?search=' + row.label
    })
  }

  $.ajax({
    url: 'h_chart.php',
    type: 'GET',
    dataType: 'json',
    data: {
      't': 'd'
    }
  })
    .done(data => {
      donut.setData(data)
    })
    .fail(() => { console.error('donutchart request failed') })
  return donut
}
