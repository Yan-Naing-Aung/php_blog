<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){

      $name = $_POST['name'];
      $email = $_POST['email'];
      $pass = $_POST['pass'];
      
      if(!empty($_POST['admin'])){
        $admincheck = 1;
      }else{
         $admincheck = 0;
      }

      $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:pass,:role)");
      $result = $stmt->execute(
        array(
          ":name"=>$name,
          ":email"=>$email,
          ":pass"=>$pass,
          ":role"=>$admincheck
        )
      );
      if($result){
        echo "<script>alert('New account is added.');window.locaiton.href='users.php'</script>";
      }

    

  }
?>

<?php
  include 'header.html';
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                 <form action="user_add.php" method="post" >
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><br>
                    <input type="email" class="form-control" name="email" required>
                  </div>  
                  <div class="form-group">
                    <label for="">Password</label>
                    <input type="Password" class="form-control" name="pass" required>
                  </div>
                  <div class="form-group" style="margin-bottom: 1.5rem">
                    Admin
                    <div class="form-check" style="display: inline;margin-left: 7px">
                      <input class="form-check-input position-static" type="checkbox" name="admin" id="blankCheckbox" value="admin" aria-label="...">
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                    <a href="users.php" type="button" class="btn btn-warning">BACK</a>
                  </div>
                </form>
              </div>              
            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php 
  include 'footer.html';
?>
