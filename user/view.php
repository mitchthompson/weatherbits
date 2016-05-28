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


if((isset($_GET['id'])) && (int)$_GET['id'] > 0){//good data, process
    $id = (int)$_GET['id'];
}else{//bad data, don't process
    //this is redirection in PHP:
    header('Location:list.php');
}

# SQL statement 
$sql = 'select CityName, UserID from wbit_user_cities
where CitiesID =' . $id;
    

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
    
    
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array 
        
        echo '<h2>' . $row["CityName"] . '</h2>';
        $cityName = $row["CityName"];
        $userID = $row["UserID"];
                       
    }
   
}else{#no records
	echo '<div align="center">Sorry, there was an error!</div>';
}
@mysqli_free_result($result);

?>
            <script> var cityName = " <?php echo $cityName ?> "</script>
                <section id="result" class="container">
                </section>
            </div><!--row-->
        <p><a href="list.php?id=<?php echo $userID ?>">Back to Favorite Cities</a></p>
        </main>    
    </body>
</html>
