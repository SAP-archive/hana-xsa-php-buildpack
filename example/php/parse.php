<?php

	print("Begin...<br />\n");

        $xsjs_req_url = "https://sfphcp-ua-xsjs.xsadv.sfphcp.com:30033/query.xsjs";

        $destinations_str = "[ { \"forwardAuthToken\": true, \"name\": \"myappjs_be\", \"url\": \"https://sfphcp-dev-xsjs.xsadv.sfphcp.com:30033\" } ]";

        $xsjs_dest_name = "myappjs_be";

        $xsjs_request = "query.xsjs";

$auth = "Bearer eyJhbGciOiJSUzI1NiJ9.eyJqdGkiOiIzMmQzZDc5NC03ZmNjLTQ4YjAtOGI2OC03ZDVhOWE2ODI4MjciLCJzdWIiOiIxNjExOTMiLCJzY29wZSI6WyJvcGVuaWQiLCJ1YWEudXNlciJdLCJjbGllbnRfaWQiOiJzYi1uYS03Nzc5ZDIzYi04MGRmLTRiODYtOGQ1Ny02NTk0YjcxNDBjMmEiLCJjaWQiOiJzYi1uYS03Nzc5ZDIzYi04MGRmLTRiODYtOGQ1Ny02NTk0YjcxNDBjMmEiLCJhenAiOiJzYi1uYS03Nzc5ZDIzYi04MGRmLTRiODYtOGQ1Ny02NTk0YjcxNDBjMmEiLCJncmFudF90eXBlIjoiYXV0aG9yaXphdGlvbl9jb2RlIiwidXNlcl9pZCI6IjE2MTE5MyIsInVzZXJfbmFtZSI6IlhTQV9VU0VSIiwiZW1haWwiOiJYU0FfVVNFUkB1bmtub3duIiwiZmFtaWx5X25hbWUiOiJYU0FfVVNFUiIsImlhdCI6MTQ2NjgzMjU5MCwiZXhwIjoxNDY2ODc1NzkwLCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAvdWFhL29hdXRoL3Rva2VuIiwiemlkIjoidWFhIiwiaGRiLm5hbWVkdXNlci5zYW1sIjoiPD94bWwgdmVyc2lvbj1cIjEuMFwiIGVuY29kaW5nPVwiVVRGLThcIj8-PHNhbWwyOkFzc2VydGlvbiB4bWxuczpzYW1sMj1cInVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDphc3NlcnRpb25cIiBJRD1cIl9mZGUyYzE2YS04NzAzLTQzYzgtOTI5NC03N2FiNGFkYTg4ZjFcIiBJc3N1ZUluc3RhbnQ9XCIyMDE2LTA2LTI1VDA1OjI0OjUwLjM2OFpcIiBWZXJzaW9uPVwiMi4wXCI-PHNhbWwyOklzc3Vlcj5YU0Etc2FtbDwvc2FtbDI6SXNzdWVyPjxkczpTaWduYXR1cmUgeG1sbnM6ZHM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjXCI-PGRzOlNpZ25lZEluZm8-PGRzOkNhbm9uaWNhbGl6YXRpb25NZXRob2QgQWxnb3JpdGhtPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMS8xMC94bWwtZXhjLWMxNG4jXCIvPjxkczpTaWduYXR1cmVNZXRob2QgQWxnb3JpdGhtPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI3JzYS1zaGExXCIvPjxkczpSZWZlcmVuY2UgVVJJPVwiI19mZGUyYzE2YS04NzAzLTQzYzgtOTI5NC03N2FiNGFkYTg4ZjFcIj48ZHM6VHJhbnNmb3Jtcz48ZHM6VHJhbnNmb3JtIEFsZ29yaXRobT1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNlbnZlbG9wZWQtc2lnbmF0dXJlXCIvPjxkczpUcmFuc2Zvcm0gQWxnb3JpdGhtPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMS8xMC94bWwtZXhjLWMxNG4jXCIvPjwvZHM6VHJhbnNmb3Jtcz48ZHM6RGlnZXN0TWV0aG9kIEFsZ29yaXRobT1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNzaGExXCIvPjxkczpEaWdlc3RWYWx1ZT5NdE9jS0w4T2dUTXFlNEFMN2tBVjhWdk5tUjg9PC9kczpEaWdlc3RWYWx1ZT48L2RzOlJlZmVyZW5jZT48L2RzOlNpZ25lZEluZm8-PGRzOlNpZ25hdHVyZVZhbHVlPms5bHJsL1ZtaExybWJJM3YzaWtCa3oxZ0RBOHZGelFyRGVvU2lueEdyeEVQVGdkRmRWdEg0RS9zcDhCVUIxLzdHZW80Z0lyemZiU2U5MEZwV0V0U0d4S0pOYkJJMGJjNGRyT0YzYTNnRk9GS2pYTWRZTzdraUZoK2x0ekVUd0VEaVhINmNNak55U2N3M0pBK1hjY00rbnQwVUhHKy9rMnN4ZWZabGZYenpyTVNSSUx3eGRhUkRGWC85WmVvb3Vhb3g1VGgvT2xqQlRlc0hvdTUvU3dOcDdxa21QVDkyK3phSnl3dHY1SnUxQWJXVlhPZG52anRhYTErWGpreFVNZFdDZ0RMVlgwS0lUajVlODZrSkhmSC9UOFpkeUxjZEIxUU56eCtLOVgrT0xzSWJma3FnNDg2RDNTLzRTNWRNZVZJb1pyTlJDRlZjZ202dUkxd3Q2UU1NUT09PC9kczpTaWduYXR1cmVWYWx1ZT48L2RzOlNpZ25hdHVyZT48c2FtbDI6U3ViamVjdD48c2FtbDI6TmFtZUlEIEZvcm1hdD1cInVybjpvYXNpczpuYW1lczp0YzpTQU1MOjEuMTpuYW1laWQtZm9ybWF0OnVuc3BlY2lmaWVkXCI-WFNBX1VTRVI8L3NhbWwyOk5hbWVJRD48c2FtbDI6U3ViamVjdENvbmZpcm1hdGlvbiBNZXRob2Q9XCJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6Y206YmVhcmVyXCI-PHNhbWwyOlN1YmplY3RDb25maXJtYXRpb25EYXRhIE5vdE9uT3JBZnRlcj1cIjIwMTYtMDYtMjVUMDk6Mjk6NTAuMzY4WlwiLz48L3NhbWwyOlN1YmplY3RDb25maXJtYXRpb24-PC9zYW1sMjpTdWJqZWN0PjxzYW1sMjpDb25kaXRpb25zIE5vdEJlZm9yZT1cIjIwMTYtMDYtMjVUMDU6MjQ6NTAuMzY4WlwiIE5vdE9uT3JBZnRlcj1cIjIwMTYtMDYtMjVUMDk6Mjk6NTAuMzY4WlwiLz48c2FtbDI6QXV0aG5TdGF0ZW1lbnQgQXV0aG5JbnN0YW50PVwiMjAxNi0wNi0yNVQwNToyOTo1MC4zNjhaXCIgU2Vzc2lvbk5vdE9uT3JBZnRlcj1cIjIwMTYtMDYtMjVUMDU6MzQ6NTAuMzY4WlwiPjxzYW1sMjpBdXRobkNvbnRleHQ-PHNhbWwyOkF1dGhuQ29udGV4dENsYXNzUmVmPnVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDphYzpjbGFzc2VzOlBhc3N3b3JkPC9zYW1sMjpBdXRobkNvbnRleHRDbGFzc1JlZj48L3NhbWwyOkF1dGhuQ29udGV4dD48L3NhbWwyOkF1dGhuU3RhdGVtZW50Pjwvc2FtbDI6QXNzZXJ0aW9uPiIsInhzLnVzZXIuYXR0cmlidXRlcyI6e30sImF1ZCI6WyJzYi1uYS03Nzc5ZDIzYi04MGRmLTRiODYtOGQ1Ny02NTk0YjcxNDBjMmEiLCJvcGVuaWQiLCJ1YWEiXX0.s-OJ6KqgxB0HvfNoL5TAO9skiy5ywh46_FGOFzWpviyrkDQx0gYl2DrYefHyFbBHAT_Di55MO216D86bwiy8NkOqALmH6TWpB1gFPUnUNWNfSe5AUMHhLWB0ltvHIms1fDzMgq-TymD8uPNp_xrffSyzkljUTy6hptTN_NjRTTJoISBkU1Qe-QA-5eZzVBnrDM4_Gp5UpgCAYu6XzAE5NSExgBJAsFStv4X4CoTakAZDYCdqveHZV1UzUfW8sst9nZBB_vNZ77yTRi6iMTcgvPUQ5nL-iqeqJO_BcmtneS2KreUbrqv6HDd1zmoSr9XBqHSOxZ01kzeSKF5POrmXGA";

$destinations = json_decode($destinations_str,false);

//	var_dump($destinations);	

        $xsjs_req_url = "";

print("<pre>\n");
foreach ($destinations as $dest_key => $dest_val) {
	$dest_obj = $dest_val;
	// print("key: " . $dest_key . " val: " . var_dump($dest_obj) . "<br />\n");

	//print("name: " . $dest_obj->name . "\n");
	//print("url: " . $dest_obj->url . "\n");
	//print("fwd: "); if ($dest_obj->forwardAuthToken) { print("True"); } else { print("False"); } print("\n");

	if ($dest_obj->name == $xsjs_dest_name) {
		$xsjs_req_url = $dest_obj->url . "/query.xsjs";
	}

}
print("xsjs_req_url: " . $xsjs_req_url . "\n");

$proxy = 'mitm.sfphcp.com:80';

$curl = curl_init();
//CURLOPT_PROXY
curl_setopt_array($curl, array(
	CURLOPT_HTTPHEADER  => array('Authorization: ' . $auth),
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_URL => $xsjs_req_url
));

//curl_setopt($curl, CURLOPT_PROXY, $proxy);

$result = curl_exec($curl);

if(!curl_exec($curl)){
    print('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}

curl_close($curl);

print("result: " . var_dump(json_decode($result, true)) . "\n");

print("</pre>\n");
       // if ($_ENV["destinations"]) {
       // }
       // else {
       // }
                                // Now do business logic things and access the HANA database.
                                // The connection point for the xsjs module for querying is this.
                                // https://sfphcp-ua-web.xsadv.sfphcp.com:30033/query.xsjs //Through app-router
                                // Find the direct url in the destinations environment variable
                                // https://sfphcp-ua-xsjs.xsadv.sfphcp.com:30033/query.xsjs //Direct

        //                        $xsjs_req_url = "https://sfphcp-ua-xsjs.xsadv.sfphcp.com:30033/query.xsjs";

                                // Invoke using CURL libs.

	print("End...<br />\n");

?>
