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
$pass = $_POST['pass'];
$email = $_POST['email'];

#SQL statement
$sql = "insert into wbit_user values (NULL, '" . $user . "','" . $email . "','" . $pass . "', NOW(), NOW())";
    
//dumpDie($sql);

#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

echo '      
        <main class="container">
        <h2>You have successfully registered!</h2>
         </main>';
    
}else{//show form
    
        echo '      
        <main class="container">
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
        </main>';
}
?>

    </body>
</html>

