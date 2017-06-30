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
		if (isset($options) && isset($options[$param]) && (strtolower($options[$param]) == "true")) {
			$tf = true;
		}
		else {
			$tf = false;
		}
	}

	return($tf);
}

?>
