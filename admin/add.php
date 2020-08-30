<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){
    
    $type = $_FILES['image']['type'];

    if($type!="image/png" && $type!="image/jpg" && $type!="image/jpeg")
    {
      echo "<script>alert('Image type must be png,jpg,jpeg! ')</script>";
    }
    else
    {
      $imgname = $_FILES['image']['name'];
      $tmp_name = $_FILES['image']['tmp_name'];

      $title = $_POST['title'];
      $content = $_POST['content'];

      $user_id=$_SESSION['id'];

      move_uploaded_file($tmp_name, "../images/$imgname");

      $stmt = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
      $result = $stmt->execute(
        array(
          ":title"=>$title,
          ":content"=>$content,
          ":image"=>$imgname,
          ":author_id"=>$user_id
        )
      );
      if($result){
        echo "<script>alert('Values is added.');window.location.href='index.php';</script>";
      }

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
                 <form action="add.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" required>
                  </div>
                  <div class="form-group">
                    <label for="">Content</label><br>
                    <textarea class="form-control" name="content" rows="8" cols="80" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><br>
                    <input type="file" name="image">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                    <a href="index.php" type="button" class="btn btn-warning">BACK</a>
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
