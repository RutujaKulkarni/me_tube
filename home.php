
<html>
<title>Me Tube</title>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->

	<link rel="stylesheet" type="text/css" href="MeTubeStyle.css" />
	<script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
	<?php
		ob_start();
		session_start();
		include_once 'connmysql.php';
		connect_db();

		$getmedia = "";
		global $mediaTable;
			if(isset($_SESSION['searchText'])){
				$searchTxt = $_SESSION['searchText'];
				$getmedia = "SELECT * FROM VIDEO_LIST WHERE caption LIKE '%$searchTxt%' OR category LIKE '%$searchTxt%' OR tags LIKE '%$searchTxt%' LIMIT 20";
				echo'<script>alert('.$getmedia.')</script>';
				$_SESSION['searchText'] = '';
				$mediaTable = mysqli_query($mysqli, $getmedia);
				if(! $mediaTable){
					$getmedia = "SELECT * FROM VIDEO_LIST LIMIT 20";
					$mediaTable = mysqli_query($mysqli, $getmedia);
				}
			}else{
				$getmedia = "SELECT * FROM VIDEO_LIST LIMIT 20";
				$mediaTable = mysqli_query($mysqli, $getmedia);

			}
			while ($row = mysqli_fetch_array($mediaTable)) {
				$data_item['user_id'] = $row['user_id'];
				$data_item['media_type'] = $row['file_type'];
				$data_item['file_name'] = $row['file_name'];
				$data_item['video_url'] = $row['video_url'];
				$data_item['caption'] = $row["caption"];
				$data_item['uploaded_date'] = $row["uploaded_date"];

				$media_details[] = $data_item;
				// storing physical media paths
				if ($data_item['media_type'] == 'video'){
					$media_paths[] = 'uploads/'.$data_item['user_id'].'/'.$data_item['media_type'].'/'.$data_item['file_name'].'#t=0.5';
				}
				else{
					$media_paths[] = 'uploads/'.$data_item['user_id'].'/'.$data_item['media_type'].'/'.$data_item['file_name'];
				}
			}

	 ?>
<style>

hr {

    margin-top: 0.5%;
    margin-bottom: 0.5%;
}

</style>
</head>
<body>
	<script type="text/javascript" language="javascript">
			$(document).ready(function(){

				$('#searchBtn').click(function() {
					var searchtxt = $('#searchText').val();
					var myData = {"search" : searchtxt};
					var request = $.ajax({ //call settimeout here
						url: "searchVideo.php",
						type: "post",
						data: myData
					});
					request.done(function (response, textStatus, jqXHR){
							console.log("Request completed!");
							location.reload();
					});
					request.fail(function (jqXHR, textStatus, errorThrown){
							console.error(
									"Could not complete the request. The following error occured: "+
									textStatus, errorThrown
							);
					});
				});
		});
	</script>
	<div class="container-fluid" style="margin-top:1%;" >
		<div class="col-sm-2">
			<div style="display: grid;">
				<?php
					echo '<button type="button" class="btn btn-link" onClick="location.href=\'home.php\'">Me Tube</button>'.
						 '<button type="button" class="btn btn-link" onClick="location.href=\'trending.php\'">Trending</button>';

					if(isset($_SESSION['username'])){
						echo '<button type="button" class="btn btn-link" onClick="location.href=\'contactList.php\'">Contacts</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'update_profile.php\'">Profile</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'media_upload.php\'">Upload</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'chats.php\'">Chat</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'myChannel.php\'">My Channel</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'playlist.php\'">My Playlist</button>';
					}
					else{
						echo '<button type="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Contacts</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Profile</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Upload</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Chat</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">My Channel</button>'.
						'<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">My Playlist</button>';
					}
				?>
			</div>
		</div>

	<div class="col-sm-10">
		<div class="row" style="display:flex;">
			<div class="input-group col-sm-8" style="display:flex;">

					<input style="margin-left:10%;" type="text" class="form-control" placeholder="Search for a video"
					aria-label="Search" id="searchText" aria-describedby="searchBtn">
				  <div class="input-group-append">
				    <button class="btn btn-light" id="searchBtn">Search</span>
				  </div>

			</div>
			<div class="col-sm-4">
				<?php
					if(isset($_SESSION['username'])){
						echo '<button style="margin-left:70%" type="button" name="button" class="btn btn-primary" onClick="location.href=\'logout.php\'">Sign Out</button> ';
					}else{
						echo '<button style="margin-left:70%" type="button" name="button" class="btn btn-primary" onClick="location.href=\'loginPage.php\'">Sign In</button>';
					}
				?>
			</div>
		</div>
		</br>
		<div class="row">


		<select style="margin-left: 75%;" id="media_date">

			<option label="Date">Date</option>
			<option value="Latest">Latest</option>
			<option value="Oldest">Oldest</option>
		</select>

		<select id="media_type">
			<option label="Media Type">Media Type</option>
			<option value="Videos">Videos</option>
			<option value="Audios">Audios</option>
			<option value="Images">Images</option>
		</select>

		<select id="media_category">
			<option label="Category">Category</option>
			<option value="Education">Education</option>
			<option value="Animation">Animation</option>
			<option value="Nature">Nature</option>
			<option value="Music">Music</option>
			<option value="Animal">Animal</option>
		</select>

		<hr align="right" width="1230px">

		<!-- starting cards -->
		<?php
		for ($x = 0; $x < count($media_details); $x++) {

			$m_url = $media_details[$x]['video_url'];
			$m_caption = $media_details[$x]['caption'];
			$m_type = $media_details[$x]['media_type'];
			$m_format = substr(strrchr($media_details[$x]['file_name'], '.'), 1 );

			if ($m_type == 'video'){

				$href_url = "play_video.php?url=".urlencode($m_url);
				$m_format = "video/".$m_format;

				echo "<div class='col-md-3'>" .
						"<a href='$href_url'>".
							"<div class='card' style='width:90%;'>" .
								"<div class='image' style='height:85%'>".
									"<video preload='metadata'>".
										"<source src='$media_paths[$x]' type='$m_format'>".
									"</video>".
								"</div>".
								"<div class='text' >".
									"<p style='text-align: center;'>$m_caption</p>".
								"</div>".
								"</a>".
							"</div>".
					 "</div>";

			}
			elseif ($m_type == 'audio'){
				$href_url = "play_audio.php?url=".urlencode($m_url);
				$m_format = "audio/".$m_format;

				echo "<div class='col-md-3'>" .
						"<a href='$href_url'>".
							"<div class='card' style='width:90%;'>" .
								"<div class='image' style='height:85%'>".

									"<audio>".
										"<source src='$media_paths[$x]' type='$m_format'>".
									"</audio>".

								"</div>".
								"<div class='text' >".
									"<p style='text-align: center;'>$m_caption</p>".
								"</div>".
								"</a>".
							"</div>".
					 "</div>";
			}

			elseif ($m_type == 'image'){
				$href_url = "show_image.php?url=".urlencode($m_url);

				echo "<div class='col-md-3'>" .
						"<a href='$href_url'>".
							"<div class='card' style='width:90%;'>" .
								"<div class='image' style='height:85%'>".

									"<img style='object-fit: scale-down' src='$media_paths[$x]'>".

								"</div>".
								"<div class='text' >".
									"<p style='text-align: center;'>$m_caption</p>".
								"</div>".
								"</a>".
							"</div>".
					 "</div>";
			}

		}
		?>
		<!-- ending cards-->
	</div>
	</div>
	<br>
	</div>
</body>
</html>
