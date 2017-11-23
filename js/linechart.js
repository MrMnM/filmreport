export default function refreshStats(chart,y,m){
if (chart === null) {
    chart = Morris.Line({
        element: 'stats', //DOM element
        data: [{
            period: "2017-01",
            Pay: 0
        }],
        xkey: 'period',
        ykeys: ['Pay'],
        labels: ['Einnahmen'],
        pointSize: 3,
        goals: [],
        goalLineColors: ['#5bc0de'],
        goalStrokeWidth: 2,
        hideHover: 'auto',
        //resize: true,
        ymin:0,
        ymax:1000,
    }).on('click', function(i, row){
      console.log(i, row.period);
    });
}

    $('#toDate').html(m+"-"+y);

    $.ajax({
            url: 'h_miscdata.php',
            type: 'GET',
            dataType: 'json',
            data: {
                'y': y,
                'm': m
            },
        })
        .done(data => {
            $("#monthlyMean").html(data.mean_month)
            $("#activeProjects").html(data.active_projects)
            chart.options.goals = [data.mean_month]
            chart.redraw()
        })

    $.ajax({
            url: "h_linechart.php",
            type: 'GET',
            dataType: 'json',
            data: {
                'y': y,
                'm': m
            }
        })
        .done(data => {
            let odata = data
            data.sort(function(a, b) {return b.Pay - a.Pay});
            //cars.sort(function(a, b){return a.year - b.year});
            chart.options.ymax = Math.round((data[0].Pay + 1500)/1000)*1000
            chart.setData(odata)
        })
        .fail(data => {console.error("linechart request failed")})
        return chart;
}
