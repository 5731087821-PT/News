<?php
require '../vendor/autoload.php';
require '../connected.php';

use Parse\ParseQuery;
use Parse\ParseObject;

	if($_GET&&$_GET["LastSyncTime"]>0){
		$LastSyncTime = intval($_GET["LastSyncTime"]);
	}else{
		$LastSyncTime = 0;
	}

	$queryAdd = new ParseQuery("NEWS");
	$queryDel = new ParseQuery("NEWS");
	$queryEdit = new ParseQuery("mod_log");

	//do this if app was runing first time COMMENT JOE//
	if($LastSyncTime==0){
		$queryAdd->equalTo("DEL", 0);
		$queryAdd->descending("POST_TS");
		try {
		  	$result = $queryAdd->find();
		  	// The object was retrieved successfully.
		} catch (ParseException $ex) {
		  	// The object was not retrieved successfully.
		  	// error is a ParseException with an error code and message.
		}
	//////////////////////////////////////////////////////////////////////

	//do this if app wasn't runing first time COMMENT JOE//
	}elseif($LastSyncTime>0){
		$queryAdd->greaterThan("POST_TS", $LastSyncTime);
		$queryAdd->descending("POST_TS");
		$queryAdd->equalTo("DEL", 0);
		$queryDel->equalTo("DEL", 1);
		$queryDel->descending("POST_TS");
		//$queryDel->limit(15);
		$queryEdit->greaterThan("OLD_POST_TS", 0);
		$queryEdit->descending("OLD_POST_TS");
		//$queryEdit->limit(15);
		try {
		  	$result = $queryAdd->find();
		  	$resultDel = $queryDel->find();
		  	$resultEdit = $queryEdit->find();
		  	// The object was retrieved successfully.
		} catch (ParseException $ex) {
		  	// The object was not retrieved successfully.
		  	// error is a ParseException with an error code and message.
		}
	}
	/////////////////////////////////////////////////////////////////////

	if (count($result) > 0 || count($resultDel) > 0) {
		if(count($result)>0){
			$toSend = array();
		    for($c=0;$c<count($result);$c++){
		      	//$toSend[$c] = json_decode($result[$c]->get("data"), true);
		      	$toSend[$c]["NEWS_TITLE"] =  $result[$c]->get("NEWS_TITLE");
		      	$toSend[$c]["NEWS_MSG"] = $result[$c]->get("NEWS_MSG");
		      	$toSend[$c]["NEWS_IMG"] = $result[$c]->get("NEWS_IMG") ? ($result[$c]->get("NEWS_IMG")) : 0;
		      	$toSend[$c]["EVENT_START"] = $result[$c]->get("EVENT_START") ? $result[$c]->get("EVENT_START") : 0;
		      	$toSend[$c]["EVENT_END"] = $result[$c]->get("EVENT_END") ? $result[$c]->get("EVENT_END") : 0;
		      	$toSend[$c]["NEWS_LOCATION"] = $result[$c]->get("NEWS_LOCATION") ? $result[$c]->get("NEWS_LOCATION") : "0";
		      	$toSend[$c]["SYS_CALEN"] = $result[$c]->get("SYS_CALEN") ? $result[$c]->get("SYS_CALEN") : false;
		      	$toSend[$c]["MOD"] = $result[$c]->get("MOD");
		      	$toSend[$c]["POST_TS"] = $result[$c]->get("POST_TS");
		      	$toSend[$c]["NEWS_TS"] = $result[$c]->get("NEWS_TS");
		    }
		    $toEncode["toAdd"] = $toSend;
	    	//json_encode -> obj -> string
	    	//json_decodde -> string -> obj
		}

	    if(count($resultDel) > 0 || count($resultEdit) > 0){
	    	$toDelete = array();
		    for($c=0;$c<count($resultDel);$c++){
	    		$toDelete[$c]["POST_TS"] = $resultDel[$c]->get("POST_TS");
	    	}
	    	for($c=0;$c<count($resultEdit);$c++){
	    		$toDelete[$c+count($resultDel)]["POST_TS"] = $resultEdit[$c]->get("OLD_POST_TS");
	    	}
	    	rsort($toDelete);
	    	$toEncode["toDelete"] = $toDelete;
    	}else{
    		$toDelete[0]["POST_TS"] = 0;
    		$toEncode["toDelete"] = $toDelete;
    	}
    	echo json_encode($toEncode);
	} else {
    	echo "0 results";
	}

?>