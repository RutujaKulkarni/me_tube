<!DOCTYPE html>
<html>
<head>
<?php
  include_once 'connmysql.php';
  connect_db();
  session_start();
?>
<title>Contacts-Family</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
<script src="toastify.js"></script>
<link rel="stylesheet" type="text/css" href="MeTubeStyle.css" />
<link rel="stylesheet" type="text/css" href="toastify.css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* body {
  font-family: "Lato", sans-serif;
} */

.sidenav {
  height: 100%;
  width: 160px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: rgba(245, 245, 245, 0.4117647058823529);
  overflow-x: hidden;
  padding-top: 20px;
}

.sidenav a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 18px;
  color: #337ab7;
  display: block;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.main {
  margin-left: 160px; /* Same as the width of the sidenav */
/*  font-size: 28px; /* Increased text to enable scrolling */
  padding: 0px 10px;
  padding-top: 20px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

table {
  border-collapse: separate;
  border-spacing: 28px 15px;
}

</style>
</head>
<body>
  <script type="text/javascript" language="javascript">
    $(document).ready(function(){
      $('.btn-link').click(function() {
        var rowid =  $(this).data('row');
        var email_cell_id = '#'+rowid + '2';
        var dropdown_cell_id = '#'+rowid + '3';

        var emailID = $(email_cell_id).text();
        var selectedGroup = $(dropdown_cell_id).find('select option:selected').val();

        var groupData = {"email" : emailID, "group" : selectedGroup};
        var request = $.ajax({
          url: "updateGroup.php",
          type: "post",
          data: groupData
        });
        request.done(function (response, textStatus, jqXHR){
            console.log("Request completed!");
            var myToast = Toastify({
                 text: "Contacts updated successfully",
                 duration: 5000
            });
            myToast.showToast();
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
<div class="sidenav">
  <a style="font-size:25px;" onClick="location.href='home.php';">Me Tube</a>
  <br>
  <a id="all" onclick="location.href='contactList.php';">All</a>
  <a id="friends" onclick="location.href='friends.php';">Friends</a>
  <a id="family" onclick="location.href='family.php';">Family</a>
  <a id="favorites" onclick="location.href='favorites.php';">Favorites</a>
  <a id="blocked" onclick="location.href='blocked.php';">Blocked</a>
</div>



<div class="main">
  <a style="font-size:33px;"><b>My Contacts &nbsp;&nbsp;</b></a>
  <a style="font-size:28px;" id="friends">Family</a>


<?php

$username=$_SESSION['username'];
$userid=$_SESSION['userid'];

// function to get contacts from family group
function get_contacts_family(){

    global $mysqli, $username;

    $grp_id_sql = "select get_USER_LIST_id('".$username."', 'Family')";
    $grp_id = mysqli_query($mysqli, $grp_id_sql);

    $grp_no = '';

    echo '<br>';
    echo '<br>';
    echo "<table>".
        "<tr>".
            "<th>Name</th>". "<th>Email</th>"."<th colspan='2'>Update group</th>".
        "</tr>";

    while ( $row = $grp_id->fetch_array(MYSQLI_NUM) ) {
        $grp_no .= $row[0];
    }

    // get all users for family group
    $family_sql = "select title from CONTACT_LIST where parent_id = '".$grp_no."'";
    $family = mysqli_query($mysqli, $family_sql);

    if (mysqli_num_rows($family) > 0){
      $rowCount = 0;
        while ( $frow = $family -> fetch_row() ) {
            $rowCount++;
            $friend_sql = "select user_id, first_name, last_name from USER_ACCOUNT where email_id = '".$frow[0]."'";
            $friend = mysqli_query($mysqli, $friend_sql);
            while ( $fr = $friend -> fetch_row() ) {
                echo "<tr>".
                        "<td id='".$rowCount."1' >".$fr[1]." ".$fr[2]."</td>".
                        "<td id='".$rowCount."2'>$frow[0]</td>".
                        "<td id='".$rowCount."3'><select id='group'>".
                                "<option value='None'>None</option>".
                                "<option value='Friends'>Friends</option>".
                                "<option value='Family' selected>Family</option>".
                                "<option value='Favorites'>Favorites</option>".
                                "<option value='Blocked'>Blocked</option>".
                            "</select>".
                        "</td>".
                        "<td id='".$rowCount."4'><button type='button' id='update_grp' data-row='".$rowCount."' class='btn btn-link' name='group'>Update Group</button>".
                        "</td>".
                     "</tr>";
            }

            $friend -> free_result();
        }
    }
    else {
	    echo "You don't have any contact added in the family list.";
    }

    $grp_id -> free_result();
    $family -> free_result();
}

get_contacts_family();

?>

</div>

</body>
</html>
