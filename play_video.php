﻿<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="MeTubeStyle.css" />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
    <?php
      session_start();
      include_once 'connmysql.php';
      connect_db();
  ?>

  <title><?php echo $video_caption?></title>
    <style>
    .w3-container {
        margin-left  : 120px;
        margin-right : 16px;
        margin-top   : 16px;
        margin-bottom: 16px;
    }
    hr {
        margin-top: 1%;
        margin-bottom: 1%;
    }
  </style>
  </head>
<body>
  <div class="col-sm-1">
    <div style="display: grid;">
      <?php
        echo '<button type="button" class="btn btn-link" onClick="location.href=\'home.php\'">Me Tube</button>'.
           '<button type="button" class="btn btn-link" onClick="location.href=\'trending.php\'">Trending</button>';

        if(isset($_SESSION['username'])){
          echo '<button type="button" class="btn btn-link" onClick="location.href=\'contactList.php\'">Contacts</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'editProfile.php\'">Profile</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'update_profile.php\'">AProfile</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'media_upload.php\'">Upload</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'chats.php\'">Chat</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'myChannel.php\'">My Channel</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'playlist.php\'">My Playlist</button>';
        }
        else{

          echo '<button type="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Contacts</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Profile</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">AProfile</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Upload</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">Chat</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">My Channel</button>'.
          '<button type="button" name="button" class="btn btn-link" onClick="location.href=\'loginPage.php\'">My Playlist</button>';
        }
      ?>
    </div>
  </div>
  <?php
    if (isset($_GET['url'])) {
        $msg = $_GET['url'];
    }
    $userid = '';
    $mediatype = '';
    global $filename ;
    $video_caption = '';
    $rating = '';
    $views = '';
    $rated_by = '';
    $video_id = '';
    $commentsResult;
    $verifySql = "SELECT * FROM VIDEO_LIST WHERE video_url = '" .$msg."'";
    $resultPass = mysqli_query($mysqli, $verifySql);
    if (mysqli_num_rows($resultPass) == 1) {
        $row = mysqli_fetch_assoc($resultPass);
        $video_id = $video_id.$row["video_id"];
        $userid = $userid.$row["user_id"];
        $mediatype = $mediatype.$row["file_type"];
        $filename = $filename.$row["file_name"];
        $video_caption = $video_caption.$row["caption"];
        $views = $views.$row["view_count"];
        $rating = $rating.$row["rating"];
        $rated_by = $rated_by.$row["rated_by"];
    }

    $fetchCommentsSQL = "SELECT * FROM VIDEO_COMMENTS WHERE VIDEO_ID = $video_id ORDER BY TS DESC";
    $commentsResult = mysqli_query($mysqli, $fetchCommentsSQL);
    $views = $views+1;
    $add_view_sql = "UPDATE VIDEO_LIST SET view_count = '".$views."' WHERE video_url = '" .$msg."'";
    $view_add_sql = mysqli_query($mysqli, $add_view_sql);
    $play_video_path = 'uploads/'.$userid.'/'.$mediatype.'/'.$filename;
    // rounding off the rating value to int
    $rating_int = round($rating);
    if ($rating_int == 2){
      $rating_int = 4;
    }
    else if ($rating_int == 1){
      $rating_int = 5;
    }
    else if ($rating_int == 4){
      $rating_int = 2;
    }
    else if ($rating_int == 5){
      $rating_int = 1;
    }
    else if ($rating_int == 3){
      $rating_int = 3;
    }
    else {
      $rating_int = 4;
    }
    //new code ends
    // Rating
    if (isset($_POST['rate_btn'])) {
        $selected_rating = $_POST['rating'];
        $rating_int = $selected_rating;

        if ($selected_rating == 2){
            $selected_rating = 4;
        }
        else if ($selected_rating == 1){
            $selected_rating = 5;
        }
        else if ($selected_rating == 4){
            $selected_rating = 2;
        }
        else if ($selected_rating == 5){
            $selected_rating = 1;
        }
        else if ($selected_rating == 3){
            $selected_rating = 3;
        }
        else {
            $selected_rating = 4;
        }

        $updateRating = "call rate_media('".$msg."', '".$selected_rating."')";
        if (mysqli_query($mysqli, $updateRating)) {
            echo '<script>alert("Media rated successfully.")</script>';
        }
        else {
            echo '<script>alert("Error occured while rating media. Please try again.")</script>';
        }
    }
    // Add to Playlist
    if (isset($_POST['playlist_btn'])) {

        $check_playlist_sql = "SELECT EXISTS(SELECT * FROM PLAY_LIST
            WHERE user_id = '" .$_SESSION['userid']. "' AND video_id = '" .$video_id. "')";

        $check_pl = mysqli_query($mysqli, $check_playlist_sql);
        $check_playlist = $check_pl -> fetch_row();
        $check_playlist = $check_playlist[0];

        if ($check_playlist){

            echo '<script>alert("Media already added to your Playlist")</script>';
    	}
        else{

            $add_playlist_slq = "insert into PLAY_LIST (user_id, video_id)
            values('" .$_SESSION['userid']. "','" .$video_id. "')";

            if (mysqli_query($mysqli, $add_playlist_slq)){
                echo '<script>alert("Media added to your Playlist")</script>';
    		    }
            else {
                echo '<script>alert("Error occured while adding media to your Playlist. Please try again.")</script>';
            }
    	}
    }

    // Comment
    if (isset($_POST['comment_btn'])) {
        $comment = $_POST['comment'];
        $insertCommentSQL = "INSERT INTO VIDEO_COMMENTS (VIDEO_ID, EMAIL, 	COMMENT) ".
        " VALUES ('".$video_id."' , '".$_SESSION['username']."', '".$comment."')";
        $res = mysqli_query($mysqli, $insertCommentSQL);
        var_dump($res);
        if($res){
          header("Refresh:0");
        }else{
          echo '<script>alert("Sorry, but we could not post your comment.")</script>';
        }
    }

  ?>
  <div class="col-sm-8">
    <div class="w3-container" style="max-width:800px; margin-left: 5%;">
      <form method="post" style="display:contents;">
        <div>
          <video width="800px" height="500px" style="margin: 0 auto; border: 5px solid #ddd;" controls>
            <source src=<?php echo $play_video_path?> type="video/mp4">
            <source src=<?php echo $play_video_path?> type="video/ogg">
            <source src=<?php echo $play_video_path?> type="video/webm">
            Your browser does not support the video tag.
          </video>
        </div>
        <span style="font-size:22px;"><?php echo $video_caption?></span>
        <span style="margin-left: 55%;"><small style="font-size:15px; margin-left: 10px; vertical-align:center;"><?php echo $views?> views</small></span>
        <button style="float: right;" class="btn btn-link" name="playlist_btn">Add to Playlist</button>
        <hr align="left" width="800px">

        <textarea rows="2" id="commentTextArea" cols="50" name="comment" placeholder="Enter your comment"></textarea>
        <button for="commentTextArea" style="margin-bottom:1.5%;" type="submit" class="btn btn-link" name="comment_btn">Comment</button>

        <span style="margin-left: 18.5%;" class="starRating">
          <input id="rating1" type="radio" name="rating" value="1"  <?php echo ($rating_int=='1')?'checked':'' ?>>
          <label for="rating1">1</label>
          <input id="rating2" type="radio" name="rating" value="2"  <?php echo ($rating_int=='2')?'checked':'' ?>>
          <label for="rating2">2</label>
          <input id="rating3" type="radio" name="rating" value="3"  <?php echo ($rating_int=='3')?'checked':'' ?>>
          <label for="rating3">3</label>
          <input id="rating4" type="radio" name="rating" value="4"  <?php echo ($rating_int=='4')?'checked':'' ?>>
          <label for="rating4">4</label>
          <input id="rating5" type="radio" name="rating" value="5"  <?php echo ($rating_int=='5')?'checked':'' ?>>
          <label for="rating5">5</label>
        </span>
        <button type="submit" class="btn btn-link" style="margin-bottom:1.5%;" name="rate_btn">Rate</button>
        <br>
      </form>
      <a href=<?php echo $play_video_path ?> download>
        <button class="btn btn-primary" id="download_btn" name="download_btn"><i class="fa fa-download"></i>Download</button>
      </a>
        <hr align="left" width="800px">
      <div class="container">
        <?php
          while ($row = mysqli_fetch_array($commentsResult)) {
            if($row['COMMENT'] != ''){
              echo '<div class="row">' .
                  '<div class="col-sm-5">'.
                    '<div class="panel panel-default">'.
                      '<div class="panel-heading">'.
                        '<strong>'.$row['EMAIL'].'</strong> <span class="text-muted">commented on '.$row['TS'].'</span>'.
                      '</div>'.
                      '<div class="panel-body">'.
                        $row['COMMENT'].
                      '</div>'.
                    '</div>'.
                  '</div>'.
                  '</div>';
            }
          }
         ?>

      </div>
    </div>
  </div>
</body>
</html>
