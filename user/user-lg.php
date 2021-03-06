<?php 
 /**
 * user-lg.php a log in page for users
 *
 *
 * @package nmCommon
 * @author Mitchell Thompson <thomitchell@gmail.com>
 * @version 1.0 2016/05/15 
 * @link App: http://mitchlthompson.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @todo none
 */


require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling
require_once '../inc_0700/credentials_inc.php'; #provides db credentials

include '../inc_0700/header.php';

# SQL statement 
$sql = 'select u.UserID, u.UserName, u.Password from wbit_user u';

//END CONFIG AREA ---------------------------------------------------------- 

echo '<div class="jumbotron"><main class="container"><div class="row">';

if(isset($_POST['submit']))
{//data submitted  

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    #IDB::conn() creates a shareable database connection via a singleton class
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

    if(mysqli_num_rows($result) > 0)
    {#there are records - present data

        while($row = mysqli_fetch_assoc($result))
        {# pull data from associative array 
                

               if (strcasecmp($row['UserName'], $user) == 0 and password_verify('' . $pass . '','' . $row['Password'] . ''))
               {//if username & password matches, case-insensitive
                   header('Location:list.php?id=' . $row[UserID] . '');
               }
        }
        
        echo '
         <h3>Username and/or password incorrect</h3>
         <a href="./user-lg.php">Try Again?</a>';

    }else{#no records
         echo '
         <h3>Username and/or password incorrect</h3>
         <a href="./user-lg.php">Try Again?</a>';
    }
    
    @mysqli_free_result($result);
                       
 }else{//show form
    
        echo '
            <section class="col-md-6 col-md-offset-3">
            <h2>Log In</h2>
            <form method="POST">
              <fieldset class="form-group">
                <label for="username">User Name</label>
                <input type="text" class="form-control" name="user" placeholder="Enter user name" size="60">
              </fieldset>
              <fieldset class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="pass" size="60" placeholder="Enter password">
              </fieldset>
              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form></section>';
}           
  

?>

        </div><!--row-->
        </main>
</div><!--jumbotron-->
<?php include '../inc_0700/footer.php' ?>

