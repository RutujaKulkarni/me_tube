<?php
	session_start();
	include_once 'connmysql.php';
	connect_db();
	$media_id = $_POST['media_id'];
	$userid = $_SESSION['userid'];
	$type = $_POST['type'];
	if($type == "playlist"){
		$removeFromPlaylistSQL = "DELETE FROM PLAY_LIST WHERE video_id = '".$media_id."' AND user_id='".$userid."' ";
		$res = mysqli_query($mysqli, $removeFromPlaylistSQL);
	}
	if($type == "favorite"){
		$removeFromPlaylistSQL = "DELETE FROM FAVORITE_LIST WHERE video_id = '".$media_id."' AND user_id='".$userid."' ";
		$res = mysqli_query($mysqli, $removeFromPlaylistSQL);
	}
//	var_dump($res);
	if(! $res){
		echo '<script>alert("Sorry, coult not remove this video from playlist. Please try again later.");</script>';
	}
?>
