<?php
	//require __DIR__ . '/vendor/autoload.php';
	require_once 'lib/dump.php';
	require_once 'lib/get_param.php';
	require 'lib/test_auth.php';
	require_once 'lib/verify_jwt.php';

	//use Lcobucci\JWT\Parser;
	
	$longopts  = array(
		"dump::",    // Optional value
		"nocheck::",    // Optional value
		"killauth::",    // Optional value
		"useproxy::",    // Optional value
		"phpinfo::"    // Optional value
	);

	$options = getopt(null, $longopts);

	$in_browser = true;
	if (isset($_SERVER) && isset($_SERVER['SHELL'])) {
		$in_browser = false;
	}

	// Safe to call getParameters now.

	$dump      = getParameter("dump");
	$nocheck   = getParameter("nocheck");
	$killauth  = getParameter("killauth");
	$useproxy  = getParameter("useproxy");
	$phpinfo   = getParameter("phpinfo");

	$debugging = false;

	$output = "";

	$proxy = 'mitm.sfphcp.com:8888';
	$use_proxy = false;

	$perform_jwt_check = true;

	$jwt_check_passed = false;

	if ($nocheck) {
		$perform_jwt_check = false;
	}
	else {
		$perform_jwt_check = true;
	}

	$print_usage = false;

	if ($print_usage) {
		$output .= "Usage: http://host/../index.php?dump=true&phpinfo=true\n";
		$output .= "Usage: php index.php --dump=true --phpinfo=true\n";
	}

	$output .= "PHP-Test with PHP version: " . phpversion() . "<br />\n";

	date_default_timezone_set('America/New_York'); // EDT

	$current_date = date('d/m/Y == H:i:s');

	$output .= "Server Time is: " . $current_date . "<br />\n";

	$output .= "<br />\n";

	if ($useproxy) {
		$use_proxy = true;
		$output .= "\n\nUsing HTTP Proxy " . $proxy . " make sure it's available to receive connections. <br />\n\n\n";
	}
	else {
		$use_proxy = false;
	}

	// The first real thing we want to do is to determine how we are being called.
	// We could be called from the MTA approuter, directly from a web server, or by the PHP CLI.
	// To make development and debugging easier, it makes sense for the code to handle all these cases.
	// Simulate data that would be found in the environment in local variables if needed.

	if ($dump) {
		$output .= dump_stuff();
	}

	$output .= "<br />\n";


// Just to prove the openssl functions are working.

	if (false) {
$key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDEhwuJ7clKxs9aoBWQAAuE0vmh
XYPNn/I4/OaFkaDqGjxsmzmMwcKWkGyJuBsheC12pibPLjQqOb7/dq2XMvL/I1hx
70NaWbafSF8MsCwXD2azm18Y1aachqXnrFcBEFdf2PPRxebqf5JPKKxqRV89fAS3
LrOYhx9YUMrVgd4WNwIDAQAB
-----END PUBLIC KEY-----'; 

	$res = openssl_pkey_get_public($key); 

	var_dump(openssl_pkey_get_details($res));

	openssl_public_encrypt("hello", $encryptedData, $key);

	}


	if ($perform_jwt_check) {

		// Check for an HTTP_AUTH header in the request
		if ((isset($_SERVER)) && (isset($_SERVER["HTTP_AUTHORIZATION"]))) {
			$auth = $_SERVER["HTTP_AUTHORIZATION"];

			$using_test_auth = false;
			$output .= "Using Auth Header." . "<br />\n";
		}
		else {
			// Headers don't contain an authorization
			$output .= "Using Test Auth." . "<br />\n";
		}

		if ($debugging) {
			$output .= "auth: <br />\n<pre>\n" . dump_var($auth) . "</pre><br />\n";
		}

		if ($killauth) {
			$auth = "";
			$output .= "Killing Auth for Testing." . "<br />\n";
		}

		$verify_result = verify_XSA_JWT_auth($auth);

		if ($verify_result->validated) {
			$output .= "Valid XS-A JWT.\n";
			$output .= "User: " . $verify_result->jwt_user_name . "\n";
			$output .= "Scope: " . dump_var($verify_result->jwt_user_scope) . "\n";
			$jwt_check_passed = true;
		}
		else {
			$output .= "Not a valid XS-A JWT.\n";
			$output .= "ERROR: " . $verify_result->output . "\n";
		}
	}
	else {
		$output .= "Skipping Auth Check." . "<br />\n";
		$auth = "";
	}

	$continue = false;

	if ($perform_jwt_check) {
		if ($jwt_check_passed) {
			$continue = true;
		}
	}
	else {
		$continue = true;
	}

	if ($continue) {

		// Query using the ODATA interface
		// https://sfphcp-dev-xsjs.xsadv.sfphcp.com:30033/service.xsodata/MyEntity?$format=json

		// Note: These defaults are for testing from the PHP command line and will be overritten when processing the "destinations" environment variable
		//       You will want to adjust them to match your module name:port particulars

		$xsjs_req_url = "https://sfphcp-dev-xsjs.xsadv.sfphcp.com:30033/query.xsjs";

		$destinations_str = "[ { \"forwardAuthToken\": true, \"name\": \"myappjs_be\", \"url\": \"https://sfphcp-dev-xsjs.xsadv.sfphcp.com:30033\" } ]";

		$xsjs_dest_name = "myappjs_be";

		$xsjs_request = "query.xsjs";

		if ((isset($_ENV)) && (isset($_ENV["destinations"]))) {
			$destinations_str = $_ENV["destinations"];
		}

		$destinations = json_decode($destinations_str,false);

		$xsjs_req_url = "";

		foreach ($destinations as $dest_key => $dest_val) {
			$dest_obj = $dest_val;

			if ($dest_obj->name == $xsjs_dest_name) {
				$xsjs_req_url = $dest_obj->url . "/" . $xsjs_request;
			}
		}

		$output .= "<pre>\n";

		$output .= "xsjs_req_url: " . $xsjs_req_url . "\n";

		// Now do business logic things and access the HANA database.
		// The connection point for the xsjs module for querying is this.
		// https://sfphcp-dev-web.xsadv.sfphcp.com:30033/query.xsjs //Through app-router
		// Find the direct url in the destinations environment variable
		// https://sfphcp-dev-xsjs.xsadv.sfphcp.com:30033/query.xsjs //Direct
	
				
		$output .= "\nauth: " . $auth . "\n";

		// Invoke using CURL libs.

		if (function_exists('curl_init')) {
			$output .= "\nCURL functions are available.<br />\n";

			$curl = curl_init();
	
			curl_setopt_array($curl, array(
				CURLOPT_HTTPHEADER  => array('Authorization: ' . $auth),
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $xsjs_req_url
			));
	
			if ($use_proxy) {
				curl_setopt($curl, CURLOPT_PROXY, $proxy);
			}

			$result = curl_exec($curl);
	
			if ($result === false) {
				$output .= 'CURL Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl);
			}
	
			$curl_info = curl_getinfo($curl);

			$output .= "<br />\n\n";

  			switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
				case 200:  # OK
					$output .= "CURL OK. \n";
					break;
				case 401:  # Unauthorized
					$output .= "CURL Request Returns Unauthorized. \n";
					break;
				case 500:  # Possible Old JWT
					$output .= "CURL Error. \n";
					$output .= "Try running with dump=true and pasting HTTP_AUTHORIZATION in lib/test_auth.php. \n";
					break;
				default:
					$output .= "CURL Unexpected HTTP code: " . $http_code . "\n";
			}

			curl_close($curl);

			$output .= "\ncurl result from PHP calling XSJS: \n\n" . dump_var(json_decode($result, true)) . "\n";

		} else {
			$output .= "CURL functions are not available.  Check the php buildback. <br />\n";
		}

		$output .= "</pre>\n";

	}

	if ($perform_jwt_check) {
		if ($jwt_check_passed == false) {
			// Return 401 Unauthorized
			if ($in_browser) {
				header("HTTP/1.1 401 Unauthorized");
				print("Unauthorized");
				exit;
			}
			else {
				$output .= "Returning 401 Unauthorized\n";
			}
		}
		else {
			$output .= "JWT Check Passed.<br />\n";
		}
	}
	else {
		$output .= "JWT Check Skipped.<br />\n";
	}
	
	if ($in_browser) {
		header("Content-Type: text/html");
	}

	print($output);

	if ($phpinfo) {
		phpinfo();	// Uncomment this to dump PHP Information
	}

?>
