export function refreshStats(chart,start,end){
  if (chart === null) {
    chart = Morris.Line({
      element: 'stats',
      data: [{
        period: '0000-00',
        Pay: 500
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


  let fetchData = {
    method: 'GET',
    headers: new Headers(),
    credentials: 'include'
  }

  // TODO Promise All & gotten rid of jQuery
  fetch('https://filmstunden.ch/api/v01/stats?start='+start+'&end='+end,fetchData)
    .then(response => response.json())
    .then(data=>{
      document.getElementById('monthlyMean').innerHTML = data.mean_month
      document.getElementById('dateRange').innerHTML = start+' bis '+end
      document.getElementById('activeProjects').innerHTML = data.active_projects
      chart.options.goals = [data.mean_month]
      return fetch('https://filmstunden.ch/api/v01/stats/chart/line?start='+start+'&end='+end, fetchData)
    })
    .then(response => response.json())
    .then(data =>{
      let odata = data
      data.sort(function(a, b) {return b.Pay - a.Pay})
      chart.options.ymax = Math.round((data[0].Pay+1000)/1000)*1000
      chart.setData(odata)
      chart.redraw()
      return chart
    })
    .catch(error => console.error(error))
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
      window.location.href = './project_overview.php?search=' + encodeURIComponent(row.label)
    })
  }
  fetch('https://filmstunden.ch/api/v01/stats/chart/donut',{credentials: 'include'})
    .then(response => response.json())
    .then(data=>{
      donut.setData(data)
      return donut
    })
    .catch(error => console.error(error))

}
