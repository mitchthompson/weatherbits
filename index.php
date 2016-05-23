<?php include 'inc_0700/header.php' ?>
                
        <main class="container">
            <div class="row">
                <section class="col-md-6 intro text-center">
                    <h2>Get a U.S. City Weather & Forecast</h2>
                    <p>Register to save your favorite cities</p>
                </section>
                <section class="intro col-md-6 text-center">
                    <form>
                        <label class="city">City Name</label>
                        <input type="text" name="city"><br/ >
                        <button class="btn btn-primary weather" type="submit">Current Weather</button>
                        <button class="btn btn-primary forecast" type="submit">Five Day Forecast</button>
                    </form>
                    <h4 class="feedback"></h4>
                </section><!--section intro-->
            </div><!--row-->
            <div class="row">
                <section id="result" class="container">
                </section>
            </div><!--row-->
        </main>    
    </body>
</html