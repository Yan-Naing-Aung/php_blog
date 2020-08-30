<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){

    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = $_POST['id'];

    if($_FILES['image']['name']!=null){

      $type = $_FILES['image']['type'];
      if($type!="image/png" && $type!="image/jpg" && $type!="image/jpeg")
      {
        echo "<script>alert('Image type must be png,jpg,jpeg! ')</script>";
      }
      else
      {
        $imgname = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        move_uploaded_file($tmp_name, "../images/$imgname");

        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$imgname' WHERE id='$id'");
        $result = $stmt->execute();
        if($result){
          echo "<script>alert('Successfully Updated.');window.location.href='index.php';</script>";
        }
      }

    }else{

      $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
      $result = $stmt->execute();
      if($result){
        echo "<script>alert('Successfully Updated.');window.location.href='index.php';</script>";
      }

    }  
  }
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
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
                 <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?= $result['id']?>">
                  <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" value="<?= $result['title']?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Content</label><br>
                    <textarea class="form-control" name="content" rows="8" cols="80" required><?= $result['content']?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><br>
                    <img src="../images/<?= $result['image']?>" width="500"><br><br>
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
