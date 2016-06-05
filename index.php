<?php include 'inc_0700/header.php' ?>
<div class="jumbotron">
        <main class="container">
            <div class="row">
                <section class="intro text-center">
                    <h2>Get a U.S. City Weather &amp; Forecast</h2>
                    <p class="text-muted"><a href="./user/register.php">Register</a> to save your favorite cities</p>
                </section>
                <section class="intro text-center">
                    <form class="form-horizontal" role="form">
                     
                        <fieldset class="form-group">
      
                            <div class="input-group col-md-4 col-md-offset-4 col-xs-6 col-xs-offset-3">
                                <input type="name" class="form-control form-group-sm" id="cityInput" name="city" placeholder=" Enter city name">
      
                                <span class="location input-group-addon" id="basic-addon"><span class="glyphicon glyphicon-record"></span></span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <div class="btn-group">
                        
                                <button class="btn btn-primary weather" type="button">Current Weather</button>
                             
                             
                                <button class="btn btn-primary forecast" type="button">Five Day Forecast</button>
                            </div>
                        </fieldset>
                        <span class="location-feedback text-info"></span>
                    </form>
                    <small class="feedback text-muted"></small>
                </section><!--section intro-->
            </div><!--row-->
        </main> 
   </div><!--jumbotron-->
<?php include 'inc_0700/footer.php' ?>
            