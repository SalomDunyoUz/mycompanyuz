<?php
	mb_internal_encoding("UTF-8");
	require_once "lib/database_class.php";
	require_once "lib/frontpagecontent_class.php";
	
	$db = new Database();
	$view = $_GET["view"];
	switch ($view) {
		case "":
			$content = new FrontPageContent($db);
			break;
			
		case "section":
			$content = new Section($db);
			break;
		default: exit;
	}
	echo $content->getContent();
?>