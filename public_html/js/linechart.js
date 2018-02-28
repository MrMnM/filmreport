export default function refreshStats(chart,start,end){
  if (chart === null) {
    chart = Morris.Line({
      element: 'stats',
      data: [{
        period: '2017-01',
        Pay: 0
      }],
      xkey: 'period',
      ykeys: ['Pay'],
      labels: ['Einnahmen'],
      xLabels: 'months',
      pointSize: 3,
      goals: [],
      goalLineColors: ['#5bc0de'],
      goalStrokeWidth: 2,
      hideHover: 'auto',
      ymin:0,
      ymax:1000,
    }).on('click', function(i, row){
      window.location.href = './project_overview.php?search='+row.period
    })
  }

  $.ajax({
    url: 'h_miscdata.php',
    type: 'GET',
    dataType: 'json',
    data: {
      's': start,
      'e': end
    },
  })
    .done(data => {
      $('#monthlyMean').html(data.mean_month)
      $('#dateRange').html(start+' bis '+end)
      $('#activeProjects').html(data.active_projects)
      chart.options.goals = [data.mean_month]
      chart.redraw()
    })

  $.ajax({
    url: 'h_chart.php',
    type: 'GET',
    dataType: 'json',
    data: {
      't': 'l',
      's': start,
      'e': end
    }
  })
    .done(data => {
      let odata = data
      data.sort(function(a, b) {return b.Pay - a.Pay})
      //cars.sort(function(a, b){return a.year - b.year});
      chart.options.ymax = Math.round((data[0].Pay + 1500)/1000)*1000
      chart.setData(odata)
    })
    .fail(() => {console.error('linechart request failed')})
  return chart
}
