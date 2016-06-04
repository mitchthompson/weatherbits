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

<?php include '../inc_0700/header_userview.php' ?>

<div class="jumbotron">
    <main class="container">
    

<?php
#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


if(mysqli_num_rows($result) > 0)
{#there are records - present data
    
    
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array 
        
     //   echo '<h2 class="text-uppercase">' . $row["CityName"] . '</h2><br />';
        $cityName = $row["CityName"];
        $userID = $row["UserID"];
                       
    }
   
}else{#no records
	echo '<div align="center">Sorry, there was an error!</div>';
}
@mysqli_free_result($result);

?>
       
        <h2 class="text-uppercase"><?php echo $cityName ?></h2>
        
        
        <fieldset class="form-group">
            <div class="btn-group">

                <button class="btn btn-primary btn-lg weather" type="button">Current Weather</button>


                <button class="btn btn-primary btn-lg forecast" type="button">Five Day Forecast</button>
            </div>
        </fieldset>
        
        <u><a class="pull-right" href="list.php?id=<?php echo $userID ?>">Back to Favorite Cities</a></u>
    
     
        <script> var cityName = " <?php echo $cityName ?> "</script>
            
        
    </main> 
</div><!--jumbotron-->
<?php include '../inc_0700/footer.php' ?>

    
    

