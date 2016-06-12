<?php 

/**
 * register.php a registration page for users
 *
 *
 * @package nmCommon
 * @author Mitchell Thompson <thomitchell@gmail.com>
 * @version 1.0 2016/05/10 
 * @link App: http://mitchlthompson.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
require_once '../inc_0700/credentials_inc.php'; #provides db credentials

include '../inc_0700/header.php';

if(isset($_POST['register']))
{//data submitted

    $user = $_POST['user'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $email = $_POST['email'];


    $sql = 'SELECT UserName FROM wbit_user WHERE username="' . $user . '"';

    #IDB::conn() creates a shareable database connection via a singleton class
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
    if(mysqli_num_rows($result) > 0)
    {#there are records - present data
     
        echo '<div class="jumbotron">  <main class="container">
            <h2>Username already exists</h2>
            <a href="./register.php">Try Again</a></main>';
               
    }else{#no records
        
        @mysqli_free_result($result);
        
         #SQL statement
         $sql = "insert into wbit_user values (NULL, '" . $user . "','" . $email . "','" . $pass . "', NOW(), NOW())";
        
        #IDB::conn() creates a shareable database connection via a singleton class
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        
        echo '      
        <div class="jumbotron"><main class="container">
        <h2>You have successfully registered!</h2>
        <a href="./user-lg.php">Log in to add favorite cities -></a>';
        
        
    }

    
}else{//show form
        echo '<div class="jumbotron">     
        <main class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>User Registration</h2>
                    <form method="POST">
                      <fieldset class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" name="user" placeholder="Enter user name" size="60">
                      </fieldset>
                      <fieldset class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="pass" size="60" placeholder="Enter password">
                      </fieldset>
                      <fieldset class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" name="email" size="100" placeholder="Enter email">
                        <small class="text-muted">We will never share your email with anyone else.</small>
                      </fieldset>
                      <button type="submit" name="register" class="btn btn-primary">Register</button>
                    </form> 
                </div><!--col-md-6-->
            </div><!--row-->';
}
?>
</main>
        </div><!--jumbotron-->
<?php include '../inc_0700/footer.php' ?>

