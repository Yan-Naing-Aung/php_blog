<?php
  session_start();
  require 'config/config.php';

  if(empty($_SESSION['id']) && empty($_SESSION['logged_in'])){
    header("location: login.php");
  }

  $post_id = $_GET['id'];

  if(isset($_POST['comment']) && !empty($_POST['comment']) ){
   
      $comment = $_POST['comment'];
      $userid = $_SESSION['id'];

      $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
      $result = $stmt->execute(
        array(
          ":content"=>$comment,
          ":author_id"=>$userid,
          ":post_id"=>$post_id
        )
      );
    
  }

  ####Blog
  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$post_id);
    $stmt->execute();
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
 
  ####Comment
  $stmtcmt = $pdo->prepare("SELECT users.name,comments.* FROM users,comments,posts WHERE users.id=comments.author_id AND posts.id=comments.post_id AND posts.id=$post_id");
    $stmtcmt->execute();
    $comments = $stmtcmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="">

    <!-- Main content -->
    <section class="content">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                 <div style="float:none; text-align: center" class="card-title">
                  <h4><?= $blog['title']?></h4>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="images/<?= $blog['image']?>" alt="Photo" width="100%">
                <br><br>
                <p><?= $blog['content']?></p>
                <h5>Comments</h5><hr>
                <a href="index.php" type="button" class="btn btn-default">Go Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                
                  <?php
                    foreach ($comments as $comment) {
                  ?>
                  <div class="card-comment">
                    <div class="comment-text" style="margin-left: 0px !important">
                      <span class="username">
                        <?=$comment['name']?>
                        <span class="text-muted float-right"><?= $comment['created_at']?></span>
                      </span><!-- /.username -->
                      <?= $comment['content'] ?>
                    </div>
                   </div>
                  <?php
                    }
                  ?>
                  
                  <!-- /.comment-text -->

            </div>

            <div class="card-footer">
                <form action="" method="post">
                  
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>

            <!-- /.card -->
          </div>
          <!-- /.col -->

     
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

   <footer class="main-footer" style="margin-left: 0px !important">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" >Log out</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">A programmer</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
