<?php
	require "vendor/autload.php";
	require "script/ConnectDB.php";
	ini_set("display_errors", "On");
	
	Flight::route('GET /@FileName', function($FileName) {
		if($FileName === "index") {
			$sql = "SELECT `message`, `obj_id`, `created_time` FROM  `beauty_nttu`  WHERE `message` LIKE '%【正妹】%'";
			$conn = new ConnectDB();
			$link = $conn -> InitialDB();
			$data = $conn -> ProcessData($link, $sql, array(), "get-image-id");
			$conn -> ConnectClose($link);
			$str = "";
			$len = count($data);
		
			for($index=0;$index<$len;$index++) {
				$str .= '<li class="col-sm-3">';
				$str .= '<a title="' . $data[$index]["message"] . '" class="img-thumbnail swipebox" href="' . "https://graph.facebook.com/" + $data[$index]["object_id"] + "/picture?type=normal" . '">';
				$str .= '<img data-src="' . "https://graph.facebook.com/" + $data[$index]["object_id"] + "/picture?type=thumbnail" . '" class="img-responsive">';
				$str .= '</a>';
				$str .= '</li>';
			}
		
			//echo $str;
			Flight::render('index_view.php', array('data' => $str));
			//Flight::redirect('./index_view.php', 401);
		}
	});
	
	Flight::route('GET /bower_components/bootswatch/flatly/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /css/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /bower_components/jquery/dist/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /bower_components/bootstrap/dist/js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /bower_components/lazyloadxt/dist/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /bower_components/swipebox/src/js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::route('GET /js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::map('notFound', function() {
		header("HTTP/1.1 404 Not found");
		header("Content-Type: text/html; charset=utf-8");
		echo "<h2>Not found</h2>";
	});

	Flight::start();
?>