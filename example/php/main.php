<?php

function main($output) {

	try {
	  checkNum(2)
	  //If the exception is thrown, this text will not be shown
	  $output .= 'If you see this, the number is 1 or below';
	}

	//catch exception
	catch(Exception $e) {
		$output .= "Message: " .$e->getMessage() . "\n";
	}

	return($output);
}

?>
