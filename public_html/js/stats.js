export function refreshStats(chart,start,end){
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
    url: 'https://api.filmstunden.ch/stats',
    type: 'GET',
    xhrFields: {withCredentials: true},
    dataType: 'json',
    data: {
      'start': start,
      'end': end
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
    url: 'https://api.filmstunden.ch/stats/chart/line',
    type: 'GET',
    xhrFields: {withCredentials: true},
    dataType: 'json',
    data: {
      'start': start,
      'end': end
    }
  })
    .done(data => {
      let odata = data
      data.sort(function(a, b) {return b.Pay - a.Pay})
      //cars.sort(function(a, b){return a.year - b.year});
      chart.options.ymax = Math.round((data[0].Pay+1000)/1000)*1000
      chart.setData(odata)
    })
    .fail(() => {console.error('linechart request failed')})
  return chart
}
export function refreshDonut (donut) {
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
    url: 'https://api.filmstunden.ch/stats/chart/donut',
    type: 'GET',
    xhrFields: {withCredentials: true},
    dataType: 'json'
  })
    .done(data => {
      donut.setData(data)
    })
    .fail(() => { console.error('donutchart request failed') })
  return donut
}
