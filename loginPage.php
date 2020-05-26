
<?php
  include_once 'connmysql.php';
  connect_db();
  session_start();
?>

<html lang = "en">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <title>Log in</title>
</head>

<body>
  <?php
      if (isset($_POST['loginButton']) && !empty($_POST['email1']) && !empty($_POST['pwd1'])) {
        $verifySql = "SELECT user_id, first_name, password, channel FROM USER_ACCOUNT WHERE email_id = '" .$_POST['email1'] ."'";
        $resultPass = mysqli_query($mysqli, $verifySql);
        if (mysqli_num_rows($resultPass) == 1) {
            $row = mysqli_fetch_assoc($resultPass);

            if(sha1($_POST['pwd1']) == $row["password"]){

              //log the user in, set session variables
              $useridSql = "SELECT user_id, first_name, channel FROM USER_ACCOUNT WHERE email_id = '" .$_POST['email1'] ."'";
              $resultSql = mysqli_query($mysqli, $useridSql);
              $row = mysqli_fetch_assoc($resultSql);

              $_SESSION['username'] = $_POST['email1'];
              $_SESSION['userid'] = $row["user_id"];

              $_SESSION['firstname'] = $row["first_name"];
              $_SESSION['channel'] = $row["channel"];

              $_SESSION['valid'] = true;
              echo '<script>location.href="home.php"</script>';
            }else{
              echo '<script>alert("Incorrect password.")</script>';
            }
        } else {
            echo '<script>alert("Username does not exist.")</script>';
         }
      }
    //  close_db_connection();
   ?>
  <ul class="nav justify-content-center" style="display:flex; margin-left: 45%;">
    <li class="nav-item">
      <a class="nav-link active" onclick="location.href='registrationForm.php';">Register</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" >Log in</a>
    </li>
  </ul>
  <!-- This is log in container -->
  <div class="container" name="loginContainer" id="loginContainer" style="width:50%;">
    <form method="post">
      <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="email1" name="email1" aria-describedby="emailHelp" required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="pwd1" name="pwd1" required>
      </div>
      <button type="submit" class="btn btn-primary" name="loginButton">Log in</button>
      <button type="button" class="btn btn-primary" name="home" onclick="location.href='home.php';">Home</button>
    </form>
  </div>
</body>

</html>
