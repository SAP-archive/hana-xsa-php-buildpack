<?php

function getParameter($param) {
	global $_GET,$options;

	$tf = false;

	if (isset($_GET) && isset($_GET[$param])) {

		if (strtolower($_GET[$param]) == "true") {
			$tf = true;
		}
		else {
			$tf = false;
		}
	}
	else {
		//$output .= "_GET['" . $param ."'] doesn't exist.\n";
		//$output .= "Checking command line args.\n";
		if (isset($options) && isset($options[$param]) && (strtolower($options[$param]) == "true")) {
			$tf = true;
		}
		else {
			$tf = false;
		}
	}

	return($tf);
}

	
	$longopts  = array(
		"dump::",    // Optional value
		"phpinfo::"    // Optional value
	);

	$options = getopt(null, $longopts);

	$output = "";
	$dump = false;

	$output .= "Starting Exists.PHP\n";
	$output .= "Usage: http://host/../exists.php?dump=true&phpinfo=true\n";
	$output .= "Usage: php exists.php --dump=true --phpinfo=true\n";

	$dump = getParameter("dump");

	if ($dump) {
		$output .= "Dump is True.\n";
	}
	else {
		$output .= "Dump is False.\n";
	}

	$phpinfo = getParameter("phpinfo");

	if ($phpinfo) {
		$output .= "phpInfo is True.\n";
	}
	else {
		$output .= "phpInfo is False.\n";
	}

	$output .= "Ending Exists.PHP\n";

	header("Content-Type: text/plain"); 

	print($output);

?>
