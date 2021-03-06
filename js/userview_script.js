//script for view.php for user to get weather & forecast for a fav city
$(document).ready(function(){  
    

        $('button.weather, form input[type=submit]').on('click', function(e) {
           e.preventDefault();
            //ajax request replacing city query with user input
            $.getJSON("http://api.openweathermap.org/data/2.5/weather?q=" + cityName + ",us&mode=json&appid=eba558c0e8d425f21d760c3534758f31",function(data,status){

            if (status == "success") {//if successful request


                //Output heading with city name
                $("#result").html("<div class='city-name'><h3>" + data["name"] + " Current Weather</h3></div>");
                
                //Output temp, description, weather icon
                $("#result").append('<div class="current text-center">'
                    + '<div class="row"><div class="col-sm-4">'
                    + '<img src="http://openweathermap.org/img/w/' + data.weather[0].icon + '.png">'
                    + '</div><!--col-xs-3-->'
                    + '<div class="col-sm-4">'
                    + '<h3 class="description">' + data.weather[0].description  + '</h3>'
                    + '<h3>' + kelvinToF(data["main"]["temp"]) + '&deg;F</h3>'
                    + '</div><!--col-xs-3-->'
                    + '<div class="col-sm-4"><ul>'
                    + "<li>Min Temp: " + kelvinToF(data["main"]["temp_min"]) + "&deg;F</li>"
                    + "<li>Max Temp: " + kelvinToF(data["main"]["temp_max"]) + "&deg;F</li>"
                    + "<li>Humidity: " + data["main"]["humidity"] + "%"
                    + '</li></div><!--col-xs-3--></div><!--row--></div><!--current-->' 
                    );
                
                $(".city-name").hide();
                $(".city-name").fadeIn(1500);
                $(".current").hide();
                $(".current").fadeIn(1500);

            } else {//if request failed
                $("#result").html("<h4>Weather lookup failed...</h4>");
            }
            });
        });

       $('button.forecast, form input[type=submit]').on('click', function(e) {
            e.preventDefault();        
            //ajax request replacing city query with user input
            $.getJSON("http://api.openweathermap.org/data/2.5/forecast?q=" + cityName + ",us&mode=json&appid=eba558c0e8d425f21d760c3534758f31",function(data,status){
                    
            if (status == "success") {//if successful request
                
                
                //Output heading with city name
                $("#result").html("<div class='city-name'><h3>" + data["city"]["name"] + " Forecast" + "</h3></div>");
                
                //iterate through data pulling out the data for 12pm each day
                for (i = 3; i <= 35; i += 8)  {    
                    $("#result").append('<div class="col-lg-2 col-md-5 col-xs-12  five-day text-center"><h4 class="date">' 
                    + formatDate(data["list"][i]["dt_txt"]) + "</h4>"
                    + '<div class="row"><div class="col-md-6 col-lg-12 col-xs-6">'
                    + '<img src="http://openweathermap.org/img/w/' + data["list"][i]["weather"][0]["icon"] + '.png">'
                    + '</div><!--col-md-6-->' 
                    + '<div class="col-md-6 col-lg-12 col-xs-6"><ul>'
                    + '<li class="description">' + data["list"][i]["weather"][0]["description"] + '</li>' 
                    + "<li>Min Temp: " + kelvinToF(data["list"][i]["main"]["temp_min"]) + "&deg;F</li>"
                    + "<li>Max Temp: " + kelvinToF(data["list"][i]["main"]["temp_max"]) + "&deg;F</li>"
                    + "<li>Humidity: " + data["list"][i]["main"]["humidity"] + "%" 
                    + "</li></div><!--col-md-6--></div><!--row--></div><!--col-md-2-->"
                    
                 )}; 
                
                $("#result").show();
                $(".city-name").hide();
                $(".city-name").fadeIn(1500);
                $(".five-day").hide();
                $(".five-day").fadeIn(1500);
                
                
            } else {//if request unsuccesful output message to user
                $("#result").show(); //show #result
                $("#result").html("<h4>Weather lookup failed...</h4>");
            }
            }); 
       });
     
    //date data from ajax request & returns in correct format
    function formatDate(data) {
        var ajaxDate = data.split(" ");
        var date =  ajaxDate[0].split("-");
        var monthNames = [
          "January", "February", "March",
          "April", "May", "June", "July",
          "August", "September", "October",
          "November", "December"
        ];
        var month = monthNames[parseInt(date[1]) - 1];
        var day = date[2];
        var dayWithMonth = month + " " + day; 
        
        return dayWithMonth;
    }
    
    //kelvin to fahrenheit conversion
    function kelvinToF(value) {
        var fahrenheit = (((Number(value) - 273.15)*9)/5) + 32;
        return fahrenheit.toFixed(0);
    }
});