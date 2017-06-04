<?php 
	
	$db_host = "localhost";
	$db_name = "Web_Shop_DB";
	$db_user = "root";
	$db_pass = "";

	$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

	if (mysqli_connect_errno()) {
		echo "Database connection failed with following errors: ". mysqli_connect_error();
		die();
	}
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/WebShop/config.php';
	require_once BASEURL.'helpers/helpers.php';


 ?>