<?php

function dump_var($the_var) {
	$output = "";

	ob_start();

	var_dump($the_var);

	$output = ob_get_contents();

	ob_end_clean();

	return $output;
}

function dump_stuff() {

	$out = "";

	if ((isset($_SERVER)) && (isset($_SERVER["REQUEST_METHOD"]))) {
		$out .= "REQUEST_METHOD: " . $_SERVER["REQUEST_METHOD"] . "<br />\n";
	}

	if ((isset($_SERVER)) && (isset($_SERVER["HTTP_AUTHORIZATION"]))) {
		$out .= "HTTP_AUTHORIZATION: " . $_SERVER["HTTP_AUTHORIZATION"] . "<br />\n";
	}

	if (isset($_COOKIE)) {
		$out .= "COOKIES: " . "<br />\n";
		foreach($_COOKIE as $key => $val) {
		    $out .= "_COOKIE[\"" . $key . "\"] = " . $val . "<br />\n";
		}
	}


	if (isset($_ENV)) {

		$vcap_application = null;

		if (isset($_ENV["VCAP_APPLICATION"])) {
			$vcap_application = json_decode($_ENV["VCAP_APPLICATION"], true);		
			if (json_last_error() === JSON_ERROR_NONE) { 
				$out .= "vcap_application: <br />\n<pre>\n" . dump_var($vcap_application) . "</pre><br />\n";
			} else { 
				$out .= "vcap_application couldn't be JSON parsed." . "<br />\n";
			} 

		}
		else {
			$out .= "VCAP_APPLICATION not found in the environment" . "<br />\n";
		}


		$vcap_services = null;

		if (isset($_ENV["VCAP_SERVICES"])) {
			$vcap_services = json_decode($_ENV["VCAP_SERVICES"], true);		
			if (json_last_error() === JSON_ERROR_NONE) { 
				$out .= "vcap_services: <br />\n<pre>\n" . dump_var($vcap_services) . "</pre><br />\n";
			} else { 
				$out .= "vcap_services couldn't be JSON parsed." . "<br />\n";
			} 

		}
		else {
			$out .= "VCAP_SERVICES not found in the environment" . "<br />\n";
		}


		$dests = null;

		if (isset($_ENV["destinations"])) {
			$dests = json_decode($_ENV["destinations"], true);		
			if (json_last_error() === JSON_ERROR_NONE) { 
				$out .= "dests: <br />\n<pre>\n" . dump_var($dests) . "</pre><br />\n";
			} else { 
				$out .= "destinations couldn't be JSON parsed." . "<br />\n";
			} 

		}
		else {
			$out .= "destinations not found in the environment" . "<br />\n";
		}

		if (isset($_ENV["SUDO_COMMAND"])) {
			$out .= "SUDO_COMMAND: " . $_ENV["SUDO_COMMAND"] . "<br />\n";
		}
		else {
			$out .= "SUDO_COMMAND not found in the environment" . "<br />\n";
		}

	}
	else {
		$out .= "_ENV not found" . "<br />\n";
	}

	return($out);
}

?>
