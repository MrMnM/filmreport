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

export function refreshDonut (donut,start,end) {
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
  fetch('https://filmstunden.ch/api/v01/stats/chart/donut?start='+start+'&end='+end,{credentials: 'include'})
    .then(response => response.json())
    .then(data=>{
      donut.setData(data)
      return donut
    })
    .catch(error => console.error(error))
}

export function refreshYearView (yearView) {
  if (yearView === null) {
    yearView = Morris.Bar({
      element: 'yearView',
      data: [
        { y: 'Jan', a: 0, b: 0 },
      ],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['Vergangene Jahre', 'Aktuelles Jahr'],
      barColors:['#a5bbcc', '#0b62a4']
    })
  }
  fetch('https://filmstunden.ch/api/v01/stats/chart/yearView',{credentials: 'include'})
      .then((response) => {
        return response.json();
      })
      .then((myJson) => {
        let d = []
        var tot = []
        for (let i of myJson) {
          let cur = {}
          cur.val = parseInt(i.tot_money)
          cur.dat = new Date(i.p_end)
          if (cur.dat.getFullYear() != new Date().getFullYear()) {
            cur.dat = cur.dat.getMonth()
            d.push(cur)
          }else{
            cur.dat = cur.dat.getMonth()
            tot.push(cur)
          }
        }
        return [d, tot]
      })
      .then((input) => {
        let d = input[0]
        let tot = input[1]
        d.sort((a, b) => {a.dat - b.dat})
        d = d.reduce(redFun, [])
        tot = tot.reduce(redFun, [])
        let val = [0,0,0,0,0,0,0,0,0,0,0,0]
        let cnt = 0
        for (var i of d) {
          let o = i.reduce((acc, curVal) => {
            return acc + curVal.val
          }, 0)
          val[cnt] = Math.round(o/3)
          cnt++
        }
        let totVal = [0,0,0,0,0,0,0,0,0,0,0,0]
        cnt = 0
        //console.log(tot)
        for (const i of tot) {
          //unelegant
          let o2 = 0
          try{
            o2 = i.reduce((acc, curVal) => {return acc + curVal.val}, 0)
          }catch(e){
            o2 = 0
          }
          totVal[cnt] = o2
          cnt++
        }
        return [val,totVal]
      })
      .then((input) =>{
        let val = input[0]
        let tot = input[1]
        let data = [
          { y: 'Jan', a: val[0], b: tot[0] },
          { y: 'Feb', a: val[1], b: tot[1] },
          { y: 'Mar', a: val[2], b: tot[2] },
          { y: 'Apr', a: val[3], b: tot[3] },
          { y: 'Mai', a: val[4], b: tot[4] },
          { y: 'Jun', a: val[5], b: tot[5] },
          { y: 'Jul', a: val[6], b: tot[6] },
          { y: 'Aug', a: val[7], b: tot[7] },
          { y: 'Sep', a: val[8], b: tot[8] },
          { y: 'Okt', a: val[9], b: tot[9] },
          { y: 'Nov', a: val[10], b: tot[10] },
          { y: 'Dez', a: val[11], b: tot[11] }
        ]
        yearView.setData(data)
      })
    .catch(error => console.error(error))
}

function redFun (acc, obj) {
    let key = obj.dat
    if (!acc[key]) {
      acc[key] = []
    }
    acc[key].push(obj)
    return acc
}
