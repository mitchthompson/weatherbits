<?php include 'inc_0700/header.php' ?>
                
        <main class="container">
            <div class="row">
                <section class="col-md-12 intro text-center">
                    <h3>Get a U.S. City Weather & Forecast</h3>
                    <p class="text-muted"><a href="#">Register</a> to save your favorite cities</p>
                </section>
                <section class="intro col-md-12 text-center">
                    <form>
                        <label class="city">City Name</label>
                        <input type="text" name="city"><br/ >
                        <button class="btn btn-primary weather" type="submit">Current Weather</button>
                        <button class="btn btn-primary forecast" type="submit">Five Day Forecast</button>
                    </form>
                    <small class="feedback text-muted"></small>
                </section><!--section intro-->
            </div><!--row-->
            <div class="row">
                <section id="result" class="container">
                </section>
            </div><!--row-->
        </main>    
    </body>
</html