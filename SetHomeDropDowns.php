<?php
  session_start();
  include_once 'connmysql.php';
  connect_db();
  $_SESSION['media_date'] = $_POST['val'];

  if($_POST['date_val']){
    $_SESSION['media_date'] = $_POST['date_val'];
  }
  if($_POST['media_type_val']){
    $_SESSION['media_type_val'] = $_POST['media_type_val'];
  }
  if($_POST['media_category_val']){
    $_SESSION['media_category_val'] = $_POST['media_category_val'];
  }
  if($_POST['clear']){
    //this means user clicked on clear filters button
    unset($_SESSION['media_date']);
    unset($_SESSION['media_type_val']);
    unset($_SESSION['media_category_val']);
  }
  echo '<script>alert("Done setting session vars");</script>';
?>
<!--
set session vars for dropdown when clicked on them.
clear session vars when clicked on clear filter

SELECT * FROM VIDEO_LIST
                                clause_added = 0
WHERE                           Hence add where to the string

(caption LIKE '%$searchTxt%' OR category LIKE '%$searchTxt%' OR tags LIKE '%$searchTxt%')  clause_added = 1

AND                             Hence add AND to the string and a clause
FILE_TYPE = 'SELECT_MENU_FILE_TYPE'
AND
CATEGORY = 'SELECT_MENU_CATEGORY'

ORDER  BY uploaded_date DESC

LIMIT 20;


caption LIKE '%$searchTxt%' OR category LIKE '%$searchTxt%' OR tags LIKE '%$searchTxt%'

 -->
