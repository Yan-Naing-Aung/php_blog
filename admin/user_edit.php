<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $id = $_POST['id'];

    if(!empty($_POST['admin'])){
      $admincheck = 1;
    }else{
      $admincheck = 0;
    }


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->execute(array(':email'=>$email,':id'=>$id));
    $user = $stmt->fetchAll();
    
    if($user){
      echo "<script>alert('Email Duplicated!');</script>";
    }else{
      
      $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$admincheck' WHERE id='$id'");
      $result = $stmt->execute();
      if($result){
        echo "<script>alert('Successfully Updated.');window.location.href='users.php';</script>";
      }
    }
     
  }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

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
                 <form action="" method="post">
                  <input type="hidden" name="id" value="<?= $result['id']?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="<?= $result['name']?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><br>
                    <input type="email" class="form-control" name="email" value="<?= $result['email']?>" required>
                  </div>  
                  <div class="form-group" style="margin-bottom: 1.5rem">
                    <label>Role</label>
                    <div class="form-check" style="display: inline;margin-left: 7px">
                      <input class="form-check-input position-static" type="checkbox" name="admin" id="blankCheckbox" value="admin" aria-label="..." <?php echo ($result['role']==1)?'checked':'';?>  >
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
