<?php
  include_once 'connmysql.php';
  connect_db();
  session_start();
?>

<html lang = "en">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <title>Register</title>
</head>

<body> <!--  onclick="openTab('loginContainer')" -->
  <?php
      if (isset($_POST['regButton']) && !empty($_POST['email']) && !empty($_POST['pwd'])) {
        if($_POST['pwd'] != $_POST['confirm_pwd']){
          echo '<script>alert("Passwords must match")</script>';
        }
        else{
          $insertSql = "INSERT INTO USER_ACCOUNT (first_name, last_name, email_id, password, gender, dob, channel) VALUES ('" .$_POST['firstName']. "','" .$_POST['lastName']. "','" .$_POST['email']. "','" .sha1($_POST['pwd']). "','" .$_POST['gender']."','" .$_POST['dob']. "','" .$_POST['firstName']. "')";

          if (mysqli_query($mysqli, $insertSql)) {
              
              $useridSql = "SELECT user_id, channel FROM USER_ACCOUNT WHERE email_id = '" .$_POST['email'] ."'";
              $resultSql = mysqli_query($mysqli, $useridSql);
              $row = mysqli_fetch_assoc($resultSql);

              echo '<script>alert("New account created successfully")</script>';
              echo '<script>location.href="loginPage.php"</script>';

          } else {
              echo '<script>alert("Error occured while creating your account. Please make sure to use a unique username. ' .$mysqli->error .'")</script>';
          }
        }
      }

      //close_db_connection();
   ?>
  <ul class="nav justify-content-center" style="display:flex; margin-left: 45%;">
    <li class="nav-item">
      <a class="nav-link active" >Register</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" onclick="location.href='loginPage.php';">Log in</a>
    </li>
  </ul>
  <!-- This is registration container -->
  <div class="container" name="regContainer" id="regContainer" style="width:50%;">
    <form method="post"> <!-- onsubmit="location.href='home.php'; return false;" -->
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>
      <div class="form-group">
        <label for="pwd">Password</label>
        <input type="password" class="form-control" id="pwd" name="pwd" minlength="8" required>
      </div>
      <div class="form-group">
        <label for="confirm_pwd">Confirm password</label>
        <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd" minlength="8" required>
      </div>
      <div class="form-group">
        <label for="firstName">First name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" required>
      </div>
      <div class="form-group">
        <label for="lastName">Last name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" required>
      </div>
      <div class="form-group">
        <label for="gender">Gender</label><br>
        <input type="radio" id="male" name="gender" value="M" required>
        <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value="F">
        <label for="female">Female</label><br>
        <input type="radio" id="other" name="gender" value="O">
        <label for="other">Other</label>
      </div>
      <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" required>
      </div>
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
        <label class="form-check-label" for="exampleCheck1">I confirm that above information is correct.</label>
      </div>
      <button type="submit" class="btn btn-primary" name="regButton">Register</button>
      <button type="button" class="btn btn-primary" name="home" onclick="location.href='home.php';">Home</button>
    </form>
  </div>

</body>

</html>


<!-- 1. Redirect to home page after submit
<timestamp> ~ <username1> ~ <msg1>
B: msg2
A: msg3
B: msg4
______________________________________________

1. start chat
2. create new log file/ open an existing log
3. fetch chats into the DOM till now
4. if new message sent, append into log (how to handle concurrency?)
5. keep polling for changes to log file. If updated, refresh chats

-->
