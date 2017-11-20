const d = new Date()
const y = d.getFullYear()
const m = d.getMonth() + 1
const chart = Morris.Line({
    element: 'stats', //DOM element
    data: [{
        period: "2017-01",
        Pay: 0
    }],
    xkey: 'period',
    ykeys: ['Pay'],
    labels: ['Einnahmen'],
    pointSize: 3,
    hideHover: 'auto',
    resize: true
});

$(function() {
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
        .done(data => {chart.setData(data)})
        .fail(data => {console.error("linechart request failed")})

});
