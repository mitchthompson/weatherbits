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


require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling
require_once '../inc_0700/credentials_inc.php'; #provides db credentials

if((isset($_GET['id'])) && (int)$_GET['id'] > 0){//good data, process
    $id = (int)$_GET['id'];
}else{//bad data, don't process
    //redirect
    header('Location:../index.php');
}

# SQL statement 
$sql = 'select u.UserID, c.CityName, c.CitiesID, u.UserName from wbit_user_cities as c
inner join wbit_user as u on c.UserID = u.UserID
where u.UserID =' . $id;

//END CONFIG AREA ---------------------------------------------------------- 

include '../inc_0700/header_userview.php';

echo '<div class="jumbotron"><main class="container"><div class="row">';

#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


if(mysqli_num_rows($result) > 0)
{#there are records - present data
    
    echo '<section class="col-md-4 col-md-offset-4 intro text-center">';
    echo '<h2>Favorite Cities</h2>';
    echo '<div class="list-group">';
    
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array 
            
           echo '<a class="list-group-item text-uppercase" href="view.php?id=' . $row['CitiesID'] . '">' . $row["CityName"] . '</a>';
              
    }
    
    echo '</div>';
    echo '</div><!--section-->';
    
    
}else{#no records
	echo '<div align="center">You do not have any favorite cities</div>';
}

@mysqli_free_result($result);

            
echo '<a class="btn btn-primary btn-lg col-md-4 col-md-offset-4" type="button" href="./add.php?id=' . $id . '">Add favorite cities</a>';
?>       
        </div><!--row-->
    </main> 
</div><!--jumbotron-->
<?php include '../inc_0700/footer.php' ?>
