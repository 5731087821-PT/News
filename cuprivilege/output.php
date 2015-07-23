<?php
require '../vendor/autoload.php';
require '../connected.php';

use Parse\ParseQuery;
use Parse\ParseObject;

$LastSyncTime = 0;

	if($LastSyncTime==0){
		$query = new ParseQuery("PRIVILEGE");
		$query -> equalTo("DEL", 0);
		$query->descending("POST_TS");
		try {
		  	$result = $query->find();
		  	// The object was retrieved successfully.
		} catch (ParseException $ex) {
		  	// The object was not retrieved successfully.
		  	// error is a ParseException with an error code and message.
		}
	} elseif($LastSyncTime > 0) {
		//list($year, $month, $day, $hour, $minute, $second) = split('[/ :]', $LastSyncTime); 
		//$LastSyncTime = $year . $month . $day . $hour . $minute . $second;
		/*$time = strtotime($LastSyncTime);
		echo $time;
		$time = strtotime("2015-01-22 21:00:00");
		echo $time;
		$time = strtotime("2015-01-24 21:41:06");
		echo $time;
		echo gmdate("Y-m-d H:i:s", $time);
		echo date("Y-m-d H:i:s");
		$time = time();
		echo $time;
		echo gmdate("Y-m-d H:i:s", time()+(7*60*60));
		*/
		//$sql = "SELECT postTS FROM news";
		//$result = $conn->query($sql);
		//echo $result;

		$query = new ParseQuery("PRIVILEGE");
		$query -> equalTo("DEL", 0);
		$query -> greaterThan($LastSyncTime);
		$query -> descending("POST_TS");
		try {
		  	$result = $query->find();
		  	// The object was retrieved successfully.
		} catch (ParseException $ex) {
		  	// The object was not retrieved successfully.
		  	// error is a ParseException with an error code and message.
		}
	}
    //$stmt = $conn->prepare("SELECT newsTS,postTS,data FROM news WHERE `delete` = 0 ORDER BY u_id desc");
    //$stmt -> execute();
    //$stmt->bind_result($result);


	if (count($result) > 0) {
		if(count($result)>0){
			$toSend = array();
		    for($c=0;$c<count($result);$c++){
		      	//$toSend[$c] = json_decode($result[$c]->get("data"), true);
		      	$toSend[$c]["POST_TS"] = $result[$c]->get("POST_TS") ? ($result[$c]->get("POST_TS")) : 0;
		      	$toSend[$c]["PRIVI_TS"] = $result[$c]->get("PRIVI_TS") ? ($result[$c]->get("PRIVI_TS")) : 0;
		      	$toSend[$c]["PRIVI_TITLE"] = $result[$c]->get("PRIVI_TITLE");
		      	$toSend[$c]["PRIVI_MSG"] = $result[$c]->get("PRIVI_MSG");
		      	$toSend[$c]["PRIVI_IMG"] = $result[$c]->get("PRIVI_IMG");
		      	$toSend[$c]["PRIVI_GPS"] = $result[$c]->get("PRIVI_GPS");
		      	$toSend[$c]["SHOP"] = $result[$c]->get("SHOP");
		      	$toSend[$c]["PRIVI_LOCATION"] = $result[$c]->get("PRIVI_LOCATION") ? $result[$c]->get("PRIVI_LOCATION") : '0';
		      	$toSend[$c]["PRIVI_START"] = $result[$c]->get("PRIVI_START") ? ($result[$c]->get("PRIVI_START")) : 0;
		      	$toSend[$c]["PRIVI_END"] = $result[$c]->get("PRIVI_END") ? $result[$c]->get("PRIVI_END") : 0;
		      	$toSend[$c]["MOD"] = $result[$c]->get("MOD");
		    }
		    $toEncode["toAdd"] = $toSend;
	    	//json_encode -> obj -> string
	    	//json_decodde -> string -> obj
		}
    	echo json_encode($toEncode);
	} else {
    	echo "0 results";
	}
?>