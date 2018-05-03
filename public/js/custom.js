function init()
{
    //Map Options
    var options = {
        zoom: 7,
        center:{lat:52.3884, lng: -1.9777}
    }

    var map = new google.maps.Map(document.getElementById('map'), options);

    //AJAX to get rating
    var url = '/dashboard/events';
    $.get(url, function(response)
    {
        var events = JSON.parse(response);

    //Loop through markers
    for(var i = 0; i < events.length; i++)
    {
        //Add Marker
        addMarker(events[i]);
    }

    //Add Marker Function
    function addMarker(props)
    {  
        //Convert Times
        var start = moment(props.starttime).format('dddd Do MMMM YYYY');
        var starttime = moment(props.starttime).format('hh:mm a');
        var endtime = moment(props.endtime).format('hh:mm a');

        //Add Marker
        var lat = props.latitude;
        var lon = props.longitude;
        var latLng = {lat: parseFloat(lat), lng: parseFloat(lon)};

        var marker = new google.maps.Marker({
            position:latLng,
            map:map
        });

        var infoWindow = new google.maps.InfoWindow({
            content: 
                '<strong><p>' + props.eventname + '</p></strong>' +
                '<p>'+ 'Date: ' + start + '</p>' +
                '<p>' + 'Time: ' + starttime + ' - '+ endtime +'</p>'
        });

        marker.addListener('click', function(){
            infoWindow.open(map, marker);
        });
        }
    });

    //AJAX to get rating
    var url = '/dashboard/maintenanceRating';
    $.get(url, function(response)
    {
        //Variables
        var rating = response;
        var total = 100;    
        var diff = total - rating;
        var color;

        if(rating >= 0 && rating <= 33)
        {
            color = "#d60000";
        }
        if(rating > 33 && rating < 66)
        {
            color = "#f48600";
        }
        if(rating >= 66)
        {
            color = "#00c132";
        }

        var config = {
            type: 'doughnut',
            data: {
              datasets: [{
                data: [rating, diff],
                backgroundColor: [color],
              }
            ]},
            options: {
                      cutoutPercentage: 70,
                      tooltips:{enabled: false},
                      elements:{arc:{borderWidth: 0}},
                      legend:{display: false},
                      layout:{padding:{
                          top: 10,
                          bottom: 10
                      }}
                  },
            plugins: [{
                   beforeDraw: (chart) => {
                    const width  = chart.chart.width
                    const height = chart.chart.height
                    const ctx    = chart.chart.ctx
                
                    ctx.restore()
                    const fontSize = (height / 120).toFixed(2)
                    ctx.font = fontSize + "em sans-serif"
                    ctx.textBaseline = "middle"
          
                    const text  = rating
                    const textX = Math.round((width - ctx.measureText(text).width) / 2)
                    const textY = height / 2
                
                    ctx.fillText(text, textX, textY)
                    ctx.save()
                  }
                  }]
          };
          
          var ctx = document.getElementById("rating").getContext("2d");
          var myDoughnut = new Chart(ctx, config);
        });
}