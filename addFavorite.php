<?php
	session_start();
	include_once 'connmysql.php';
	connect_db();
	$media_id = $_POST['media_id'];
	$userid = $_SESSION['userid'];
	//var_dump($res1);
	$removeFromPlaylistSQL = "INSERT INTO FAVORITE_LIST (user_id, video_id) VALUES ('".$userid."' ,'".$media_id."')";
	$res = mysqli_query($mysqli, $removeFromPlaylistSQL);
	if(! $res){
		echo '<script>alert("Sorry, coult not add this video to Favorites. Please try again later.");</script>';
	}
?>
