<?php
session_start();

if (isset($_GET['Message'])) {
    $msg = $_GET['Message'];
    echo "<script>alert('$msg');</script>";
}

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media Upload</title>


</head>

<body>

<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >

  <p style="margin-top: 100px; margin-left: 25%; margin-right:25%;">
  <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
   Upload a Media file: <label style="color:#663399"><em> (Each file limit 10M)</em></label><br/>
   <br>
   <input  name="file" type="file" size="50" required />
   <br><br>
   <label for="caption">Media Caption: </label>
   <br><br>
   <input type="text" id="caption" name="caption" required>
   <br><br>
   <label for="caption">Media Tags: </label>
   <br><br>
   <input type="text" id="tags" name="tags" required> <label for="tags">Please enter tags seperated by spaces that describe the uploaded files</label>
   <br>
   </p>
   <div style="margin-left: 25%;">
   <p><b>Select Category</b></p>
        <input type="radio" id="Education" name="category" value="Education" required>
        <label for="Education">Education</label>
        <input type="radio" id="Animation" name="category" value="Animation">
        <label for="Animation">Animation</label>
        <input type="radio" id="Nature" name="category" value="Nature">
        <label for="Nature">Nature</label>
        <input type="radio" id="Music" name="category" value="Music">
        <label for="Music">Music</label>
        <input type="radio" id="Animal" name="category" value="Animal">
        <label for="Animal">Animals</label><br>
    </div>
   <br>
   <div style="margin-left: 25%;">
   <p><b>Select Audience to view media</b></p>

        <input type="radio" id="All" name="audience" value="All" required>
        <label for="All">All MeTube Users</label>
        <input type="radio" id="Friends" name="audience" value="Friends">
        <label for="Friends">Friends</label>
        <input type="radio" id="Family" name="audience" value="Family">
        <label for="Family">Family</label>
        <input type="radio" id="Favorite" name="audience" value="Favorite">
        <label for="Favorite">Favorites</label><br>
    </div>
   <br>

   <br>
	<input style="margin-left: 25%; margin-right:25%;" value="Upload" name="submit" type="submit" />

 </form>
</body>
</html>
