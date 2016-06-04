<?php
/**
 * index.php a page for users to add cities
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
    header('Location:./user-lg.php');
}

//END CONFIG AREA ---------------------------------------------------------- 

include '../inc_0700/header_userview.php';

echo '<div class="jumbotron"><main class="container"><div class="row">';

if(isset($_POST['add']))
{//data submitted
    
    $city = $_POST['city'];
    
    # SQL statement 
    $sql = 'select UserID, CityName from wbit_user_cities where UserID =' . $id;
    
    #IDB::conn() creates a shareable database connection via a singleton class
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
    if(mysqli_num_rows($result) > 0)
    {#there are records - present data
        
        $cityInDB = 0;

        while($row = mysqli_fetch_assoc($result))
        {# pull data from associative array 

               if (strcasecmp($row['CityName'], $city) == 0){
                   $cityInDB = 1;
               }

        }
    }
        
    
    @mysqli_free_result($result);
    
        if($cityInDB == 0){
    
            #SQL statement
            $sql = "insert into wbit_user_cities values (NULL, '" . $id . "','" . $city . "', NOW(), NOW())";

            #IDB::conn() creates a shareable database connection via a singleton class
            $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
            
            echo '<div class="alert alert-success">City added to your favorites!</div>';
            echo '<div class="list-group">';
            echo '<a class="list-group-item" href="./add.php?id=' . $id . '">Add another favorite city -></a>';
            echo '<a class="list-group-item" href="./list.php?id=' . $id . '">Go to your profile -></a>';
            echo '</div>';
        }else{
            echo '<div class="alert alert-danger">That city is already one of your favorites!</div>';
            echo '<div class="list-group">';
            echo '<a class="list-group-item" href="./add.php?id=' . $id . '">Add another favorite city -></a>';
            echo '<a class="list-group-item" href="./list.php?id=' . $id . '">Go to your profile -></a>';
            echo '</div>';
        }
               


    
}else{//show form
    
        echo '
            <section class="col-md-6 col-md-offset-3">
            <h2>Add a favorite city</h2>
            <form method="POST">
              <fieldset class="label">Add a favorite city</fieldset>
              <fieldset class="form-group">
                <input type="text" class="form-control" id="cityInput" name="city" placeholder="Enter city name" size="60">
              </fieldset>
              <fieldset class="form-group">
              <button type="submit" name="add" class="btn btn-primary btn-block">Add</button>
              </fieldset>
            </form></section>';
}
?>
</div>
</main>  
</div><!--jumbotron-->
<?php include '../inc_0700/footer.php' ?>

