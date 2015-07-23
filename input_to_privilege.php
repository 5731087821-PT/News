<?PHP
session_start();
require 'vendor/autoload.php';
require 'connected.php';
 
use Parse\ParseQuery;
use Parse\ParseObject;

if($_SESSION['privilege']&&$_SESSION['name']){	
	if($_POST&&$_POST["del"]=="false"){
		//#####GET IP ADDRESS#####
		$IP = getenv('HTTP_CLIENT_IP')?:
			  getenv('HTTP_X_FORWARDED_FOR')?:
			  getenv('HTTP_X_FORWARDED')?:
			  getenv('HTTP_FORWARDED_FOR')?:
			  getenv('HTTP_FORWARDED')?:
			  getenv('REMOTE_ADDR')?:
			  "UNKNOW";
		$now = time();

		//#####IMAGE TO UPLOAD#####
		$target_dir = "cuprivilege/uploads/";
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		$extension = image_type_to_extension($check[2], false);
		$name = $now+4852345012;
		$target_file = $target_dir . $name . '.' . $extension;
		$uploadOk = 0;
		// Check if image file is a actual image or fake image
	    if($check !== false) {
	        $uploadOk = 1;
	        // Check if file already exists
			if (file_exists($target_file)) {
			    $uploadOk = 10;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 300000) {
			    $uploadOk = 20;
			}
			// Check file type
			if($extension != "jpg" && $extension != "png" && $extension != "jpeg"
			&& $extension != "gif" ) {
			    $uploadOk = 30;
			}
	    } else {
	        $uploadOk = 0;
	    }
		// Check if $uploadOk is set to 0,10,20,30 by an error
	 	switch($uploadOk){
	 		case 0:
	 			echo "File is not an image.<br>";
	 			echo "Your file and data were not uploaded.<br>";
	 			return;
	 		case 10:
	 			echo "Sorry, file's name already exists. Please rename your image file.<br>";
	 			echo "Your file and data were not uploaded.<br>";
	 			return;
				case 20:
	 			echo "Sorry, your image file is larger than 300kb.<br>";
	 			echo "Your file and data were not uploaded.<br>";
	 			return;
				case 30:
	 			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
	 			echo "Your file and data were not uploaded.<br>";
	 			return;
			// if everything is ok, try to upload file
			default :
			 	try{
			 		if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
			        	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
			    	}
			 	} catch(Exception $e){
			 		echo 'Sorry, there was an error uploading your file. Please send it again.'+$e->getMessage()+'<br>';
			 	} 
	    }

		
		if($uploadOk==1){ // if image upload completed insert all data into database COMMENT JOE//

			//convert date form to timestamp COMMENT JOE//
			$tmp=$_POST["startPrivilege"];
			$startPrivilege = intval(strtotime($tmp))-(7*60*60);
			$tmp=$_POST["endPrivilege"];
			$endPrivilege = intval(strtotime($tmp))-(7*60*60);

			$saving = new ParseObject("PRIVILEGE");
			$saving->set("POST_TS", $now);
			$saving->set("PRIVI_TS", $now);
			$saving->set("PRIVI_TITLE", str_replace("'", "", $_POST["title"]));
			$saving->set("PRIVI_MSG", str_replace("'", "", $_POST["message"]));
			$saving->set("PRIVI_START", $startPrivilege);
			$saving->set("PRIVI_END", $endPrivilege);
			$saving->set("PRIVI_IMG", $target_file);
			$saving->set("PRIVI_GPS", $_POST["map"]);
			$saving->set("PRIVI_LOCATION", str_replace("'", "", $_POST["location"]));
			$saving->set("SHOP", str_replace("'", "", $_POST["shopName"]));
			$saving->set("MOD", $_SESSION['name']);
			$saving->set("DEL", 0);
			$saving->set("TO_BOOKMARK", false);
			$saving->set("VIEWED", false);
			$savingLog = new ParseObject("mod_log_privilege");
	        $savingLog->set("PRIVI_MSG", str_replace("'", "", $_POST["message"]));
	        $savingLog->set("MOD", $_SESSION['name']);
	        $savingLog->set("OLD_POST_TS", 0);
	        $savingLog->set("POST_TS", $now);
	        $savingLog->set("IP_ADDRESS", $IP);

			try {
			  	$saving->save();
			  	$savingLog->save();
			  	echo 'New object created with objectId: ' . $saving->getObjectId();
			} catch (ParseException $ex) {  
				// Execute any logic that should take place if the save fails.
				// error is a ParseException object with an error code and message.
				echo 'Failed to create new object, with error message: ' + $ex->getMessage();
			}
		}
	}elseif($_POST&&$_POST["del"]=="true"){
		$dataDel = intval($_POST['postTS']);
		$delete = new ParseQuery("PRIVILEGE");
		$delete->equalTo("POST_TS", $dataDel);
		$deleteQuery = $delete->find();
		$deleteQuery[0]->set("DEL", 1);

		try {
		  	$deleteQuery[0]->save();
		  	echo 'Object deleted with objectId: ' . $deleteQuery[0]->getObjectId();
		} catch (ParseException $ex) {  
			// Execute any logic that should take place if the save fails.
			// error is a ParseException object with an error code and message.
			echo 'Failed to delete object, with error message: ' + $ex->getMessage();
		}
		echo "<br/><a href='./index.php'>Back</a>";
	}
}else{
	header( "location: ./login.php" );
	exit(0);
}
?>
<html>
<head>
<meta http-equiv="Refresh" content="5;url=index.php">
</head>
</html>	