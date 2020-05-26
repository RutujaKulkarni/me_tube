<html>
<title>Me Tube</title>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="MeTubeStyle.css" />
	<?php
		ob_start();
		session_start();
		include_once 'connmysql.php';
		connect_db();

		// get all the videos

		$getmedia = "SELECT * FROM VIDEO_LIST WHERE user_id = '" .$_SESSION['userid']."'";
		$mediaTable = mysqli_query($mysqli, $getmedia);

		if (mysqli_num_rows($mediaTable) > 0) {

			$hasMedia = True;
		
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
		}
		else{
			$hasMedia = False;
		}
	?>



</head>
<body>
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
				  <input style="margin-left:10%;" type="text" class="form-control" placeholder="Search for a video" aria-label="Search" aria-describedby="basic-addon2">
				  <div class="input-group-append">
					<button class="btn btn-light" id="basic-addon2">Search</span>
				  </div>
				</div>
			</div>
			<br>
			<br>
			<p style="font-size:30px;">My Channel:<a href="update_profile.php" target="_self"> <?php echo $_SESSION['channel']; ?></a></p>
			<br>
			<br>
			

				<!-- starting cards -->
			<?php

			if ($hasMedia){
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
				}
			}
			else{
					echo "<p style='font-size:18px'>You have not uploaded any media files.</p>";
					echo "<br>";
					echo "<br>";
					echo "<a href='media_upload.php'>"."<p style='font-size:18px'>Upload a media file to MeTube</p>"."</a>";
				}
			?>
			<!-- ending cards-->
		</div>
	</div>
</body>

</html>
