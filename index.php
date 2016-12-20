<?php
	//ini_set("display_errors", "On");
	ini_set('include_path', '~/www/beauty-nttu');
	
	require "vendor/autoload.php";
	require "script/ConnectDB.php";
	
	function RequsetData($gender) {
		$sql = "SELECT `message`, `obj_id`, `created_time` FROM  `beauty_nttu`  WHERE `message` LIKE '%【" . $gender . "】%'";
		$conn = new ConnectDB();
		$link = $conn -> InitialDB();
		$data = $conn -> ProcessData($link, $sql, array(), "get-image-id");
		$conn -> ConnectClose($link);
		
		$str = '<h2>資料來源：<a href="https://www.facebook.com/BeautyNTTU/?fref=ts" target="_blank">表特東大</a></h2>
				<p>僅提供瀏覽，欣賞</p>';

		$len = count($data);

		for($index=0;$index<$len;$index++) {
			$message = $data[$index]["message"];
			$normal = "https://graph.facebook.com/" . $data[$index]["obj_id"] . "/picture?type=normal";
			$thumbnail = "https://graph.facebook.com/" . $data[$index]["obj_id"] . "/picture?type=thumbnail";
			
			$str .= '<a title="' . $message . '" class="swipebox" href="'. $normal . '">';
			$str .= '<img src="'. $thumbnail . '" alt="image" class="img-rounded">';
			$str .= '</a>';
		}
		
		return $str;
	}
	
	use flight\Engine;
	
	$app = new Engine();
	
	$app -> route('GET /', function() {
		
		$str = RequsetData("正妹");
		
		$MaleFemale = '<li>
			<a href="/beauty-nttu/man">型男版</a>
        </li>';
		
		Flight::render('index_view.php', array('data' => $str, 'MaleFemale' => $MaleFemale));
	});
	
	$app -> route('GET /@action', function($action) {
		$str = "";
		$MaleFemale = '<li>
			<a href="/beauty-nttu/man">型男版</a>
        </li>
		<li>
            <a href="/beauty-nttu/">正妹版</a>
        </li>';
		
		switch($action) {
			case "about":
				$str = '<h2>Blog: <a href="https://peterweb-uploadspace.rhcloud.com/" target="_blank">連結網址</a></h2>';
				break;
			case "contact":
				$str = '<h2>Github: <a href="https://github.com/peter279k" target="_blank">連結網址</a></h2>';
				$str .= '<h2>Email: <a href="mailto:peter279k@gmail.com">聯絡信箱</a></h2>';
				break;
			case "man":
				$str = RequsetData("型男");
				$MaleFemale = '<li>
					<a href="/beauty-nttu/man">正妹版</a>
				</li>';
				break;
			case "link":
				$str = '<h2>表特牆: <a href="https://beauty.nttu.biz/beauty/" target="_blank">連結網址</a></h2>';
				break;
		}
		
		Flight::render('index_view.php', array('data' => $str, 'MaleFemale' => $MaleFemale));
	});
	
	$app -> route('GET /bower_components/bootswatch/darkly/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /css/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /bower_components/jquery/dist/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /bower_components/bootstrap/dist/js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /bower_components/lazyloadxt/dist/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /bower_components/swipebox/src/js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	$app -> route('GET /js/@FileName', function($FileName) {
		$FileName = htmlentities($FileName, "utf-8");
		echo @file_get_contents($FileName);
	});
	
	Flight::map('notFound', function(){
		// Handle not found
		echo "Not found";
	});

	$app -> start();
?>