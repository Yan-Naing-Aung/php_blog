<?php
require "config/config.php";
require 'config/common.php';

if(isset($_POST['submit'])){

  if(empty($_POST['name']) || empty($_POST['email']) ||empty($_POST['password']) || strlen($_POST['password'])<4 ){
      if(empty($_POST['name'])){
        $nameError = "<p style='color:red'>*Name cannot be null</p>";
      }
      if(empty($_POST['email'])){
        $emailError = "<p style='color:red'>*Email cannot be null</p>";
      }
      if(empty($_POST['password'])){
        $passError = "<p style='color:red'>*Password cannot be null</p>";
      }elseif(strlen($_POST['password'])<4){
        $passError = "<p style='color:red'>*Password should be at least 4 characters</p>";
      }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $pdostatement = $pdo->prepare("SELECT * FROM users where email=:email");
    $pdostatement->bindValue(':email',$email);
    $pdostatement->execute();
    $result = $pdostatement->fetch(PDO::FETCH_ASSOC);
    
    if($result){
      echo "<script>alert('There is account already created with this email!')</script>";
    }else{
      $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:pass,:role)");
        $result = $stmt->execute(
          array(
            ":name"=>$name,
            ":email"=>$email,
            ":pass"=>$pass,
            ":role"=>0
          )
        );
      if($result){
        echo "<script>alert('Register Successfully. You can now login :)');window.location.href='login.php'</script>";
      }

    }
  }


}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</b> Register</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register New Account</p>

      <form action="register.php" method="post">
        <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
        <?= empty($nameError)?'':$nameError;?>
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div><br>
        </div>
        <?= empty($emailError)?'':$emailError;?>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <?= empty($passError)?'':$passError;?>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <input type="submit" name="submit" class="btn btn-primary btn-block" value="Sign Up">
            <a href="login.php" type="button" class="btn btn-default btn-block">Login</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

<!--  <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
