<?php
/**
 * index.php a list page of user cities
 *
 *
 * @package nmCommon
 * @author Mitchell Thompson <thomitchell@gmail.com>
 * @version 1.0 2016/05/05 
 * @link App: http://mitchlthompson.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @todo none
 */


require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
require_once '../inc_0700/credentials_inc.php'; #provides db credentials

# SQL statement 
$sql = "select u.UserID, c.CityName, c.CitiesID, u.UserName from wbit_user_cities as c
inner join wbit_user as u on c.UserID = u.UserID
where u.UserID = 2"

//END CONFIG AREA ---------------------------------------------------------- 

?>

<?php include '../inc_0700/header.php' ?>

    <main class="container">
        <div class="row">

<?php
#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


if(mysqli_num_rows($result) > 0)
{#there are records - present data
    
    echo '<section class="col-md-6 intro text-center">';
    echo '<h2>Favorite Cities</h2>';
    echo '<ul>';
    
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array 
            
           echo '<li><a href="view.php?id=' . $row['CitiesID'] . '">' . $row["CityName"] . '</a></li>';
              
    }
    
    echo '</ul>';
    echo '</div><!--section-->';
    
    
}else{#no records
	echo '<div align="center">Sorry, there are no news feeds that match that category</div>';
}
?>
            
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
</html>
