<?php
/**
 * common_inc.php stores site-wide utility functions
 *
 * 
 * @package nmCommon
 * @author Mitchell Thompson <thomitchell@gmail.com>
 * @version 1.0 2016/05/05 
 * @link http://www.mitchlthompson.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo none
 Common Files
 */
 
/**
 * Forcibly passes user to a URL.  Accepts either an absolute or relative address.
 *
 * This function is a alternative to the PHP header() function.
 *  
 * Any page using myRedirect() needs ob_start() at the top of the page or header() errors 
 * will occur i.e.: 'headers already sent'.
 *
 * Will sniff for "http://", "https://", which will force an absolute redirect, otherwise assume local.
 * 
 * @param string $myURL locally referenced file, or absolute with 'http://' as destination for user
 * @return void
 * @todo examine HTTPS support
 */
function myRedirect($myURL)
{
	$httpCheck = strtolower(substr($myURL,0,8)); # http:// or https://
	if(strrpos($httpCheck,"http://")>-1 || strrpos($httpCheck,"https://")>-1){//absolute URL
		header("Location: " . $myURL);
	}else{//relative URL
		$myProtocol = strtolower($_SERVER["SERVER_PROTOCOL"]); # Cascade the http or https of current address
		if(strrpos($myProtocol,"https")>-1){$myProtocol = "https://";}else{$myProtocol = "http://";}
		$dirName = dirname($_SERVER['REQUEST_URI']);  #Path derives properly on Windows & UNIX. alternatives: SCRIPT_URL, PHP_SELF
		$char = substr($dirName,strlen($dirName) - 1);
		if($char != "/"){$dirName .= "/";} # Only add slash if required!
		header("Location: " . $myProtocol . $_SERVER['HTTP_HOST'] . $dirName . $myURL);
	}
	die(); //added for safety!
} #End myRedirect()

/**
 * Wrapper function for processing data pulled from db
 *
 * Forward slashes are added to MySQL data upon entry to prevent SQL errors.  
 * Using our dbOut() function allows us to encapsulate the most common functions for removing  
 * slashes with the PHP stripslashes() function, plus the trim() function to remove spaces.
 *
 * Later, we can add to this function sitewide, as new requirements or vulnerabilities develop.
 *
 * @param string $str data as pulled from MySQL
 * @return $str data cleaned of slashes, spaces around string, etc.
 * @see dbIn()
 * @todo none
 */
function dbOut($str)
{
	if($str!=""){$str = stripslashes(trim($str));}//strip out slashes entered for SQL safety
	return $str;
} #End dbOut()

/**
 * Filters data per MySQL standards before entering database. 
 *
 * Adds slashes and helps prevent SQL injection per MySQL standards.    
 * Function enclosed in 'wrapper' function to add further functionality when 
 * as vulnerabilities emerge.
 *
 * @param string $var data as entered by user
 * @return string returns data filtered by MySQL, adding slashes, etc.
 * @see dbOut()
 * @see idbIn()  
 * @todo Rebuild so global $myConn no longer involved
 */
function dbIn($var)
{
	global $myConn;//checks data against active DB connection

	if(isset($var) && $var != "")
	{
		return mysql_real_escape_string($var);
	}else{
		return "";
	}
} #End dbIn()

/**
 * mysqli version of dbIn()
 * 
 * Filters data per MySQL standards before entering database. 
 *
 * Adds slashes and helps prevent SQL injection per MySQL standards.    
 * Function enclosed in 'wrapper' function to add further functionality when 
 * as vulnerabilities emerge.
 *
 * @param string $var data as entered by user
 * @param object $myConn active mysqli DB connection, passed by reference.
 * @return string returns data filtered by MySQL, adding slashes, etc.
 * @see dbIn() 
 * @todo none
 */
function idbIn($var,&$iConn)
{
	if(isset($var) && $var != "")
	{
		return mysqli_real_escape_string($iConn,$var);
	}else{
		return "";
	}
	
} #End idbIn()


/**
 * nl2br2() changes '\n' (newline)  to '<br />' tags
 * Break tags can be stored in DB and used on page to replicate user formatting
 * Use on input/update into DB from forms
 *
 * <code>
 * $myText = nl2br2($myText); # \n changed to <br />
 * </code>
 * 
 * @param string $text Data from DB to be loaded into <textarea>
 * @return string Data stripped of <br /> tag variations, replaced with new line 
 * @todo none
 */
function nl2br2($text)
{
	$text = str_replace(array("\r\n", "\r", "\n"), "<br />", $text);
	return $text;
} #End nl2br2()

/**
 * wrapper function for PHP session_start(), to prevent 'session already started' error messages. 
 *
 * To view any session data, sessions must be explicitly started in PHP.  
 * In order to use sessions in a variety of INC files, we'll check to see if a session 
 * exists first, then start the session only when necessary.
 *
 * 
 * @return void
 * @todo none 
 */
function startSession()
{
	//if(!isset($_SESSION)){@session_start();}
	if(isset($_SESSION))
	{
		return true;
	}else{
		@session_start();
	}
	if(isset($_SESSION)){return true;}else{return false;}
} #End startSession()

/**
 * Checks for email pattern using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */
function onlyEmail($myString)
{
  if(preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z0-9_\-]+$/",$myString))
  {return true;}else{return false;}
}#end onlyEmail()

/**
 * Checks data for alphanumeric characters using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */
function onlyAlphaNum($myString)
{
  if(preg_match("/[^a-zA-Z0-9]/",$myString))
  {return false;}else{return true;} //opposite logic from email?
}#end onlyAlphaNum()

/**
 * Checks data for numeric characters using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */
function onlyNum($myString)
{
  if(preg_match("/[^0-9]/",$myString))
  {return false;}else{return true;} //opposite logic from email?
}#end onlyNum()

/**
 * Checks data for alphanumeric characters using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */
function onlyAlpha($myString)
{
  if(preg_match("/[^a-zA-Z]/",$myString))
  {return false;}else{return true;} //opposite logic from email?  
}#end onlyAlpha()

/**
 * Requires data submitted as isset() and passes dat to 
 * dbIn() which processes per MySQL standards, adding slashes and 
 * attempting to prevent SQL injection.     
 * Upon failure, user is forcibly redirected to global variable,  
 * $redirect, which is applied just before checking a series of form values.
 *
 *<code>
 * $redirect = THIS_PAGE; //global redirect
 * $myVar = formReq($_POST['myVar']);
 *</code>
 *
 * @uses dbIn()
 * @param string $var data as entered by user
 * @return string returns data filtered by MySQL, adding slashes, etc.
 * @todo none
 */
function formReq($var)
{
	/**
	 * $redirect stores page to redirect user to upon failure 
	 * This variable is declared in the page, just before the form fields are tested.
	 *
	 * @global string $redirect
	 */
	global $redirect;

	if(!isset($_POST[$var]))
	{
		feedback("Required Form Data Not Passed","error");
		
		if(!isset($redirect) || $redirect == "")
		{//if no redirect indicated, use the current page!
			myRedirect(THIS_PAGE);		
		}else{
			myRedirect($redirect);	
		}
	}else{
		return dbIn($_POST[$var]);
	}
}#end formReq()

/**
 * Requires data submitted as isset() and passes dat to 
 * dbIn() which processes per MySQL standards, adding slashes and 
 * attempting to prevent SQL injection.     
 * Upon failure, user is forcibly redirected to global variable,  
 * $redirect, which is applied just before checking a series of form values.
 *
 *<code>
 * $redirect = THIS_PAGE; //global redirect
 * $myVar = formReq($_POST['myVar']);
 * $otherVar = formReq($_POST['otherVar']);
 *</code>
 *
 * @uses dbIn()
 * @param string $var data as entered by user
 * @return string returns data filtered by MySQL, adding slashes, etc.
 * @todo merge formReq (uses global) with form_Req (preferred) below:
 */
function form_Req($var,$redirect)
{
	/**
	 * $redirect stores page to redirect user to upon failure 
	 * This variable is declared in the page, just before the form fields are tested.
	 *
	 * @global string $redirect
	 */
	global $redirect;

	if(!isset($_POST[$var]))
	{
		feedback("Required Form Data Not Passed","error");
		
		if(!isset($redirect) || $redirect == "")
		{//if no redirect indicated, use the current page!
			myRedirect(THIS_PAGE);		
		}else{
			myRedirect($redirect);	
		}
	}else{
		return dbIn($_POST[$var]);
	}
	
}

/**
 * mysqli version of formReq()
 * 
 * Requires data submitted as isset() and passes data to 
 * idbIn() which processes per MySQL standards, adding slashes and 
 * attempting to prevent SQL injection.
 *     
 * Upon failure, user is forcibly redirected to global variable,  
 * $redirect, which is applied just before checking a series of form values.
 *
 * mysqli version requires explicit connection, $myConn
 *
 *<code>
 * $iConn = conn("admin",TRUE); //mysqli connection
 * $myVar = iformReq($_POST['myVar'],$iConn);
 * $otherVar = iformReq($_POST['otherVar'],$iConn);
 *</code>
 *
 * @uses idbIn()
 * @see formReq() 
 * @param string $var data as entered by user
 * @param object $myConn active mysqli DB connection, passed by reference.
 * @return string returns data filtered by MySQL, adding slashes, etc.
 * @todo none
 */
function iformReq($var,&$iConn)
{
	/**
	 * $redirect stores page to redirect user to upon failure 
	 * These variables are declared in the page, just before the form fields are tested.
	 *
	 * @global string $redirect
	 */
	global $redirect;

	if(!isset($_POST[$var]))
	{
		feedback("Required Form Data Not Passed","error");
		
		if(!isset($redirect) || $redirect == "")
		{//if no redirect indicated, use the current page!
			myRedirect(THIS_PAGE);		
		}else{
			myRedirect($redirect);	
		}
	}else{
		return idbIn($_POST[$var],$iConn);
	}
	
}#end iformReq()



/* 
 * rte() function allows multiple RTE edit points on a page.
 *
 * Provides session protected wiring of fckeditor Rich Text Editor. 
 * If not logged in, shows data on page only.  If logged in, shows 'edit' 
 * button for each RTE, and allows RTE editing of data.
 *
 *<code>
 * rte(1); //mimimum, id of RTE only
 * rte(2,'50%','300','Default'); //all but border identified
 * rte(3,'300','400','Basic',TRUE);  //full implementation
 *</code>
 *
 * @param int $RTEID id number of RTE field to store data
 * @param str $Width width in percent or pixels of RTE edit box
 * @param str $Height height in pixels of RTE edit box
 * @param str $ToolBar configured in fckconfig.js, our implementations include "Default" & "Basic"
 * @param boolean $showBorder true will place a border around the entire RTE area and edit button  
 * @return void
 */
 	

/* 
 * troubleshooting wrapper function for var_dump
 *
 * saves annoyance of needing to type pre-tags
 *
 * Optional parameter $adminOnly if set to TRUE will require 
 * currently logged in admin to view crash - will not interfere with 
 * public's view of the page
 *
 * WARNING: Use for troubleshooting only: will crash page at point of call!
 *
 * <code>
 * dumpDie($myObject);
 * </code>
 *
 * @param object $myObj any object or data we wish to view internally 
 * @param boolean $adminOnly if TRUE will only show crash to logged in admins (optional) 
 * @return none
 */
function dumpDie($myObj,$adminOnly = FALSE)
{
	if(!$adminOnly || startSession() && isset($_SESSION['AdminID'])) 
	{#if optional TRUE passed to $adminOnly check for logged in admin
		echo '<pre>';
		var_dump($myObj);
		echo '</pre>';
		die;
	}
}

/**
 * Creates a smart (sic) title from words present in the php file name (page)
 *
 * If no string is input, will take current PHP file name, strip of extension 
 * and replace "-" and "_" with spaces
 *
 * Will also title case first letter of significant words in title
 *
 * A comma separated string named $skip can be used to add/delete more 
 * words that are NOT title cased
 *
 * First word is always title case by default
 *
 * <code>
 * $config->titleTag = smartTitle();
 * </code>
 * 
 * added version 2.07
 *
 * @param string $myTitle file name or etc to amend (optional)
 * @return string converted title cased version of file name/string
 * @todo none
 */
function smartTitle($myTitle = '')
{
	if($myTitle == ''){$myTitle = THIS_PAGE;}
	$myTitle = strtolower(substr($myTitle, 0, strripos($myTitle, '.'))); #remove extension, lower case
	$separators = array("_", "-");  #array of possible separators to remove
	$myTitle = str_replace($separators, " ", $myTitle); #replace separators with spaces
	$myTitle = explode(" ",$myTitle); #create an array from the title
	$skip = "this|is|of|a|an|the|but|or|not|yet|at|on|in|over|above|under|below|behind|next to| beside|by|among|between|by|till|since|during|for|throughout|to|and|my";
	$skip = explode("|",$skip); # words to skip in title case
	
	for($x=0;$x<count($myTitle);$x++)
	{#title case words not skipped
		if($x == 0 || !in_array($myTitle[$x], $skip)) {$myTitle[$x] = ucwords($myTitle[$x]);}
		//echo $word . '<br />';
	}
	return implode(" ",$myTitle); #return imploded (spaces re-added) version
}# End smartTitle()


/**
 * loads a quick user message (flash/heads up) to provide user feedback
 *
 * Uses a Session to store the data until the data is displayed via showFeedback() loaded 
 * inside the bottom of header_inc.php (or elsewhere) 
 *
 * <code>
 * feedback('Flash!  This is an important message!'); #will show up next running of showFeedback()
 * </code>
 * 
 * added version 2.07
 *
 * @param string $msg message to show next time showFeedback() is invoked
 * @return none 
 * @see showFeedback() 
 * @todo none
 */

#flash message is a temporary message sent to the user
#load it here and show it one time when showFeedback() is called
function feedback($msg,$level="warning")
{
	startSession();
	$_SESSION['feedback'] = $msg;
	$_SESSION['feedback-level'] = $level;

}

/**
 * shows a quick user message (flash/heads up) to provide user feedback
 *
 * Uses a Session to store the data until the data is displayed via showFeedback()
 *
 * Related feedback() function used to store message 
 *
 * <code>
 * echo showFeedback(); #will show then clear message stored via feedback()
 * </code>
 * 
 * changed from showFeedback() version 2.10
 *
 * @param none 
 * @return string html & potentially CSS to style feedback
 * @see feedback() 
 * @todo none
 */
function showFeedback()
{
	startSession();//startSession() does not return true in INTL APP!
	
	$myReturn = "";  //init
	if(isset($_SESSION['feedback']) && $_SESSION['feedback'] != "")
	{#show message, clear flash
		if(defined('THEME_PHYSICAL') && file_exists(THEME_PHYSICAL . 'feedback.css'))
		{//check to see if feedback.css exists - if it does use that
			$myReturn .= '<link href="' . THEME_PATH . 'feedback.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
		}else{//create css for feedback
			$myReturn .= 
				'
				<style type="text/css">
				.feedback {  /* default style for div */
					border: 1px solid #000;
					margin:auto;
					width:100%;
					text-align:center;
					font-weight: bold;
				}
			
				.error {
				  color: #000;
				  background-color: #ee5f5b; /* error color */
				}
			
				.warning {
				  color: #000;
				  background-color: #f89406; /* warning color */
				}
			
				.notice {
				  color: #000;
				  background-color: #5bc0de; /* notice color */
				}
				
				.success {
				  color: #000;
				  background-color: #62c462; /* notice color */
				}
				</style>
				';
				
		}
	
		if(isset($_SESSION['feedback-level'])){$level = $_SESSION['feedback-level'];}else{$level = 'warning';}
		$myReturn .= '<div class="feedback ' . $level . '">'  . $_SESSION['feedback'] . '</div>';
		$_SESSION['feedback'] = ""; #cleared
		$_SESSION['feedback-level'] = "";
		return $myReturn; //data passed back for printing
		
	}
}


/**
 * Provides active connection to MySQL DB.
 *
 * A set of default credentials should be placed in the conn() function, and optional 
 * levels of access can be chosen on a case by case basis on specific pages.  
 *
 * One of 5 strings indicating a MySQL user can be passed to the function  
 *
 * 1 admin
 * 2 delete
 * 3 insert
 * 4 update
 * 5 select
 *  
 * MySQL accounts must be setup for each level, with 'select' account only able 
 * to access db via 'select' command, and update able to 'select' and 'update' etc. 
 * Each credential set must exist in MySQL before it can be used.
 *
 * If no data is entered into conn() function when it is called, a mysqli connection with the 
 * default access is returned:
 *
 *<code>
 * $myConn = conn();
 *</code>
 *
 * If you create multiple MySQL users and have a 'select only' user, you can create a 'select only' connection:
 *
 * <code>
 * $myConn = conn("select");
 * </code>
 *
 * You can also create a mysql classic (mysql) connection by declaring FALSE as a second optional argument:
 *
 * <code>
 * $iConn = conn("select",FALSE);
 * </code>
 *
 * There are times you may want to use a mysql classic connnection over mysqli for security or compatibility
 *
 * @param string $access represents level of access
 * @param boolean $improved If TRUE, uses mysqli improved connection 
 * @return object Returns active connection to MySQL db.
 * @todo error logging, or emailing admin not implemented
 */ 

function conn($access="",$improved = TRUE)
{
	$myUserName = "";
	$myPassword = "";
	
	if($access != "")
	{#only check access if overwritten in function
		switch(strtolower($access))
		{# Optionally overwrite access level via function
			case "admin":	
				$myUserName = ""; #your MySQL username
				$myPassword = ""; #your MySQL password	
				break;
			case "delete":	
				$myUserName = ""; 
				$myPassword = ""; 
				break;	
			case "insert":	
				$myUserName = ""; 
				$myPassword = ""; 
				break;
			case "update":	
				$myUserName = ""; 
				$myPassword = ""; 
				break;
			case "select":	
				$myUserName = ""; 
				$myPassword = ""; 
				break;		
			
		}
	}
	
	if($myUserName == ""){$myUserName = DB_USER;}#fallback to constants
	if($myPassword == ""){$myPassword = DB_PASSWORD;}#fallback to constants
	if($improved)
	{//create mysqli improved connection
		$myConn = @mysqli_connect(DB_HOST, $myUserName, $myPassword, DB_NAME) or die(trigger_error(mysqli_connect_error(), E_USER_ERROR));
	}else{//create standard connection
		$myConn = @mysql_connect(DB_HOST,$myUserName,$myPassword) or die(trigger_error(mysql_error(), E_USER_ERROR));
		@mysql_select_db(DB_NAME, $myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	}
	return $myConn;
}

/** 
 * Placing the DB connection inside a class allows us to create a shared 
 * connection to improve use of resources.
 *
 * Returns a mysqli connection:
 *
 * <code>
 * $iConn = IDB::conn();
 * </code>
 *
 * All calls to this class will use the same shared connection.
 * 
 */ 

class IDB 
{ 
	private static $instance = null; #stores a reference to this class

	private function __construct() 
	{#establishes a mysqli connection - private constructor prevents direct instance creation 
		#hostname, username, password, database
		$this->dbHandle = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME) or die(trigger_error(mysqli_connect_error(), E_USER_ERROR)); 
	} 

	/** 
	* Creates a single instance of the database connection 
	* 
	* @return object singleton instance of the database connection
	* @access public 
	*/ 
	public static function conn() 
    { 
      if(self::$instance == null){self::$instance = new self;}#only create instance if does not exist
      return self::$instance->dbHandle;
    }
}

function pdo()
{//return PDO object
/*
PDO & SQL Injection: 
PDO tutorial: http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
*/
	try {
	   $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',DB_USER,DB_PASSWORD);
	} catch(PDOException $ex) {
	   trigger_error($ex->getMessage(), E_USER_ERROR);
	}
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//make errors catchable
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);//disable emulated prepared statements

	return $db;

}

/*
$today = date("Y-m-d H:i:s");
$to = 'bill@example.com';
$subject = 'Test Email, No ReplyTo: ' . $today;
$message = '
	Test Message Here.  Below should be a carriage return or two: ' . PHP_EOL . PHP_EOL .
	'Here is some more text.  Hopefully BELOW the carriage return!
';
*/

/**
 * adds links to associative nav array of $config object
 *
 * Will add link before or after current associative array, default is after 
 *
 * <code>
 * $today = date("Y-m-d H:i:s");
 * $to = 'client@example.com';
 * $subject = 'Test Email, No ReplyTo, Text, not HTML format: ' . $today;
 * $message = '
 * 	Test Message Here.  Below should be a carriage return or two: ' . PHP_EOL . PHP_EOL .
 * 	'Here is some more text.  Hopefully BELOW the carriage return!';
 *
 * safeEmail($to, $subject, $message,'','');//replyTo and contentType are eliminated here
 * </code>
 * 
 * added version 2.07
 *
 * @param string $to email address where message will be received
 * @param string $subject message shown in header of email
 * @param string $message body of email
 * @param string $replyTo (optional) used for reply to so client can respond to user
 * @param string $contentType(optional) defaults to HTML
 * @return boolean true or false to indicate if PHP found an error while trying to send email
 * @todo none
*/

function safeEmail($to, $subject, $message, $replyTo='',$contentType='text/html; charset=ISO-8859-1 ')
{#builds and sends a safe email, using Reply-To properly!
	$fromDomain = $_SERVER["SERVER_NAME"];
	$fromAddress = "noreply@" . $fromDomain; //form always submits from domain where form resides

	if($replyTo==''){$replyTo='';}

	$headers = 'From: ' . $fromAddress . PHP_EOL .
		'Content-Type: ' . $contentType . PHP_EOL .
		'Reply-To: ' . $replyTo . PHP_EOL .
		'X-Mailer: PHP/' . phpversion();
	return mail($to, $subject, $message, $headers);
}

/**
 * requires POST or GET params or redirect, etc. back to calling form or 
 * safe page
 *
 * <code>
 * $params = array('last_name','first_name','email');#required fields to register	
 * 
 * if(!required_params($params,true))
 * {//abort - required fields not sent
 *		feedback("Data not entered/updated. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
 *		myRedirect(VIRTUAL_PATH . 'index.php');
 * 	die();
 * }
 * </code>
 *
 * @param array names of all POST/GET required fields
 * @param boolean if true, only allow the passed in params, no others
 * @return void
 * @todo none 
 */
 
 function required_params($params,$exclusive=false) {
	foreach($params as $param) {
		if(!isset($_POST[$param])) {
			return false;
		}
	}
	if($exclusive)
	{//if any field submitted is different from required params, disallow
		foreach($_POST as $name => $value)
		{
			if(!in_array($name,$params)){return false;}
		}
	}
	return true;
}#end required_params()