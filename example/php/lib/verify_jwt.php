<?php

	//       Normally you'd use a PHP JWT library to do more extensive checks. See: https://jwt.io/ for examples

require_once 'dump.php';
	
Class XSA_JWT {
   public $verified;
   public $output;
   public $jwt_alg;
   public $jwt_user_name;
   public $jwt_user_scope;
}

function verify_XSA_JWT_auth($auth) {

	$output = "";

	$jwt_alg = "";
	$jwt_user_name = "";
	$jwt_user_scope = null;
	$jwt_zid = "";

	$is_valid_jwt = false;

	$auth_parts  = explode(' ', $auth);
	if (count($auth_parts) == 2) {
		$bearer = $auth_parts[0];
		$jwt_token = $auth_parts[1];

		if ($bearer == "Bearer") {
			$output .= "Auth has Bearer, continuing.\n";
			$token_parts  = explode('.', $jwt_token);
			if (count($token_parts) == 3) {

				$jwt_header = base64_decode($token_parts[0]);
				$jwt_payload = base64_decode($token_parts[1]);
				$jwt_signature = $token_parts[2];

				//$output .= "jwt_header: " . $jwt_header . "\n";
				//$output .= "jwt_payload: " . $jwt_payload . "\n";
				//$output .= "jwt_signature: " . $jwt_signature . "\n";

				$header_obj = json_decode($jwt_header,false);
				if (!(is_null($header_obj))) {
					$output .= "jwt_header: " . dump_var($header_obj) . "\n";
					if (isset($header_obj->alg)) {
						$jwt_alg = $header_obj->alg;
						$output .= "jwt_alg: " . $jwt_alg . "\n";
					}
					else {
						$output .= "JWT Header has no alg element.\n";
					}
				}
				else {
					$output .= "JSON_decode of jwt_header failed.\n";
				}

				// Split off the SAML stuff
				$saml_parts = explode(',"hdb.nameduser.saml"',$jwt_payload);
				//$output .= "saml1: " . $saml_parts[0] . "\n";
				$jwt_payload = $saml_parts[0] . "}";
				$output .= "jwt_payload: " . $jwt_payload . "\n";
				
				// Inspect the JWT Payload

				$payload_obj = json_decode($jwt_payload,false);
				if (!(is_null($payload_obj))) {

					$output .= "jwt_payload: " . dump_var($payload_obj) . "\n";

					if (isset($payload_obj->user_name)) {
						$output .= "jwt_user_name: " . $payload_obj->user_name . "\n";
						$jwt_user_name = $payload_obj->user_name;
					}
					else {
						$output .= "JWT Payload has no user_name element.\n";
					}

					if (isset($payload_obj->scope)) {
						$output .= "jwt_user_scope: " . dump_var($payload_obj->scope) . "\n";
						$jwt_user_scope = $payload_obj->scope;
					}
					else {
						$output .= "JWT Payload has no scope element.\n";
					}

					if (isset($payload_obj->zid)) {
						$output .= "jwt_zid: " . dump_var($payload_obj->zid) . "\n";
						$jwt_zid = $payload_obj->zid;
					}
					else {
						$output .= "JWT Payload has no zid element.\n";
					}

				}
				else {
					$output .= "JSON_decode of jwt_payload failed.\n";
				}

				// Check to see if this looks like a XS-A JWT
				// Note: This is a rough check for certain elements and not a comprehensive verification of the signature.
				//       Normally you'd use a PHP JWT library to do more extensive checks. See: https://jwt.io/ for examples

				if ($jwt_alg == "RS256") {
					if ($jwt_user_name != "") {
						if (!(is_null($jwt_user_scope))) {
							if ($jwt_zid == "uaa") {
								// $output .= "Valid XS-A JWT.\n";
								$is_valid_jwt = true;
							}
							else {
								$output .= "INVALID: jwt_zid is not uaa.\n";
							}
						}
						else {
							$output .= "INVALID: jwt_user_scope is null.\n";
						}
					}
					else {
						$output .= "INVALID: jwt_user_name is empty.\n";
					}
				}
				else {
					$output .= "INVALID: jwt_alg is not RS256.\n";
				}
			}
			else {
				$output .= "Expected 3 parts in jwt_token.\n";
			}
		}
		else {
			$output .= "Auth doesn't start with Bearer.\n";
		}

	}
	else {
		$output .= "Expected 2 parts in auth.\n";
	}
	
	$result_obj = new XSA_JWT();

	if ($is_valid_jwt) {
		$result_obj->validated = true;
		$result_obj->output = "VALID";
		$result_obj->jwt_alg = $jwt_alg;
		$result_obj->jwt_user_name = $jwt_user_name;
		$result_obj->jwt_user_scope = $jwt_user_scope;
	}
	else {
		$result_obj->validated = false;
		$result_obj->output = $output;
		$result_obj->jwt_alg = $jwt_alg;
		$result_obj->jwt_user_name = $jwt_user_name;
		$result_obj->jwt_user_scope = $jwt_user_scope;
	}

	return($result_obj);
}

?>
