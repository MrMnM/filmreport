


$(document).ready(function() {
    $.ajax({
        url: 'h_miscdata.php',
        dataType: 'json',
        data : {'y':y,'m':m},
        type: 'GET',
        success: function(data){
            $("#monthlyMean").html(data.mean_month);


        }
    });

    $(function() {
        // Create a Bar Chart with Morris
        var chart = Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'stats',
            data: [{period: "2017-01", Pay: 0}], // Set initial data (ideally you would provide an array of default data)
            xkey: 'period', // Set the key for X-axis
            ykeys: ['Pay'], // Set the key for Y-axis
            labels: ['Einnahmen'], // Set the label when bar is rolled over
            pointSize: 3,
            hideHover: 'auto',
            resize: true
        });

        // Fire off an AJAX request to load the data
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "h_linechart.php", // This is the URL to the API
            data: { "y": y, "m":m } // Passing a parameter to the API to specify number of days
        })
        .done(function(data) {
            // When the response to the AJAX request comes back render the chart with new data
            chart.setData(data);
        })
        .fail(function(data) {
            console.log(data);
            // If there is no communication between the server, show an error
            alert( "error occured" );
        });
    });

});
