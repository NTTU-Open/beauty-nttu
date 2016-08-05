<?php
	set_time_limit(0);
	ini_set("display_errors", "On");
	require __DIR__ . "/vendor/autoload.php";
	require "CnnectDB.php";
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\RequestException;
	
	$client = new Client;
	
	$connection = new ConnectDB();
	$conn = $connection -> InitialDB();
	
	$AppToken = "";
	
	$handle = @fopen("./token/tokens.txt", "r");
	
	if(!$handle) {
		echo "the token file is not existed.";
		exit();
	}
	else {
		while(!feof($handle)) {
			if(strlen(fgets($handle)) != 0)
			$AppToken = fgets($handle);
		}
	}
	
	$contents = array();
	$PageID = array("1450930895146846");
	
	$response = $client -> get("https://graph.facebook.com/" . $PageID[0] . "/feed?access_token=" . $AppToken);
	
	$page = json_decode($response -> getBody(), true);
	
	$count = count($page["data"]);
	
	while($count !== 0) {
		for($CountData=0;$CountData<$count;$CountData++) {
			if(isset($page["data"][$CountData]["message"]))
				$message = $page["data"][$CountData]["message"];
			else
				$message = "";
			if(isset($page["data"][$CountData]["object_id"]))
				$ObjectID = $page["data"][$CountData]["object_id"];
			else
				$ObjectID = "";
			if(isset($page["data"][$CountData]["created_time"]))
				$CreatedTime = $page["data"][$CountData]["created_time"];
			else
				$CreatedTime = "";
			
			$data["message"] = str_replace("☺ ", "", trim($message));
			$data["message"] = str_replace("\n", "", $data["message"]);
			$data["object_id"] = $ObjectID;
			$data["created_time"] = $CreatedTime;
			
			if($page_arr[$counter] === $PageID[0])
				$sql = "INSERT INTO beauty_nttu(message,obj_id,created_time) VALUES(:message,:object_id,:created_time)";
			
			$result = $connection -> ProcessData($conn, $sql, $data, "insert-record");
			
			switch($result) {
				case "insert-fail":
					file_put_contents("log/error.log", "insert-fail,".$message.",".$ObjectID."\r\n", FILE_APPEND);
					break;
				case "duplicate-entry":
					file_put_contents("log/error.log", "duplicate-entry,".$message.",".$ObjectID."\r\n", FILE_APPEND);
					break;
			}
		}
		
		$page = $client -> get($page["paging"]["next"]);
		$page = json_decode($response -> getBody(), true);
		
		$count = count($page["data"]);
	}
?>
