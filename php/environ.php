<?php

require 'main.php';

	// Put any messages into a local variable for output.  They way you can control how it's displayed later.
	// Make the lines of output terminated by \n for text/plain

$output = "";

set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

ini_set('display_errors', false); #in order to hide errors shown to user by php 
ini_set('log_errors',FALSE); #assuming we log the errors our selves 
ini_set('error_reporting', E_ALL); #We like to report all errors

function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context) {
	$error = "lvl: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | ln:" . $error_line;
	switch ($error_level) {
	    case E_ERROR:
	    case E_CORE_ERROR:
	    case E_COMPILE_ERROR:
	    case E_PARSE:
	        mylog($error, "fatal");
	        break;
	    case E_USER_ERROR:
	    case E_RECOVERABLE_ERROR:
	        mylog($error, "error");
	        break;
	    case E_WARNING:
	    case E_CORE_WARNING:
	    case E_COMPILE_WARNING:
	    case E_USER_WARNING:
	        mylog($error, "warn");
	        break;
	    case E_NOTICE:
	    case E_USER_NOTICE:
	        mylog($error, "info");
	        break;
	    case E_STRICT:
	        mylog($error, "debug");
	        break;
	    default:
	        mylog($error, "warn");
	}
}

function shutdownHandler() { //will be called when php script ends.
	$lasterror = error_get_last();
	switch ($lasterror['type'])
	{
	    case E_ERROR:
	    case E_CORE_ERROR:
	    case E_COMPILE_ERROR:
	    case E_USER_ERROR:
	    case E_RECOVERABLE_ERROR:
	    case E_CORE_WARNING:
	    case E_COMPILE_WARNING:
	    case E_PARSE:
		$error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
		mylog($error, "fatal");
	}
}

function mylog($error, $errlvl)
{
	global $output;
	$output .=  "Error(" . $errlvl . ") :" . $error . "\n";
}

	// When executing PHP code from a web server, unexpected exceptions will likely fail silently.
	// Put everything in a try/catch block so that these are handled so that you can see the output.


$output .= main($output);

try {
  checkNum(2);
  //If the exception is thrown, this text will not be shown
  $output .= 'If you see this, the number is 1 or below';
}

//catch exception
catch(Exception $e) {
	$output .= "Message: " .$e->getMessage() . "\n";
}

print($output);

?>
