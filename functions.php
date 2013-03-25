<?php
require_once 'include.php';

function retrieve_fields($sf) {

    return json_encode($sf);
}

function verify_fields($f,$sf) {
    $fields = json_encode($sf);
    return (strcmp($fields,$f) === 0);
}

function register_user($resp) {
    extract($resp["registration"],EXTR_PREFIX_ALL, "fb");

    // prepare values
    $fb_id = mysql_real_escape_string($resp["user_id"]);
    $fb_name = mysql_real_escape_string($fb_name);

    $query_str = "INSERT INTO users VALUES ('$fb_id', '$fb_name', '$fb_email') ON DUPLICATE KEY UPDATE fb_id=fb_id";
    mysql_query($query_str);
}

function check_registration($fb, $fb_fields) {
    if ($_REQUEST) {
        $response = $fb->getSignedRequest();
        if ($response && isset($response["registration_metadata"]["fields"])) {
            $verified = verify_fields($response["registration_metadata"]["fields"], $fb_fields);

            if (!$verified) { // fields don't match!
                header("location: bad.php");
            } else { // we verifieds the fields, insert the Data to the DB
                $GLOBALS['congratulations'] = TRUE;
                register_user($response);
            }
        }
    }
}

function get_user_by_id($id) {
    $res = mysql_query("SELECT * FROM users WHERE fb_id = '$id'");
    if($res) {
        $row = mysql_fetch_array($res);
        return $row;
    } else
        return FALSE;
}

function mailThroughRouter($to, $subject, $message)
{
	$url = "http://www.tomhschmidt.com/parkit/mailrouter.php?to=".$to."&subject=".$subject."&message=".$message;
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL, $url);
	curl_exec($curl_handle);
	curl_close($curl_handle);
}

function textMessage($number, $provider, $subject, $message)
{
	$textAddress = "";
	switch($provider)
	{
		case "ATT":
			$textAddress = $number."@txt.att.net";
			break;
		case "Verizon":
			$textAddress = $number."@vtext.com";
			break;
		case "Sprint":
			$textAddress = $number."@messaging.sprintpcs.com";
			break;
		case "T-Mobile":
			$textAddress = $number."@tmomail.net";
			break;
	}
	
	mailThroughRouter($textAddress, $subject, $message);
}

?>