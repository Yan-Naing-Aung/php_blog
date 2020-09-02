<?php
  session_start();
  require '../config/config.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  $user_id=$_SESSION['id'];

  if(isset($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
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
              <div class="card-header">
                <h3 class="card-title">Blog Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <h6 style="display: inline;margin-right:5px">New Blog Post</h6>
                  <a href="add.php" type="button" class="btn btn-success">Create +</a>
                </div>
                <br>
                <?php
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecs = 5;
                  $offset = ($pageno - 1) * $numOfRecs;

                  if(empty($_POST['search']) && empty($_COOKIE['search'])){
                    $pdostatement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $blogs = $pdostatement->fetchAll();
                    
                  }else{
                    $searchkey = isset($_POST['search'])?$_POST['search']:$_COOKIE['search'];
                    $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $blogs = $pdostatement->fetchAll();
                    
                  }
                ?>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php

                      if($blogs){
                        $i=1;
                        foreach ($blogs as $blog) {
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= $blog['title'] ?></td>
                          <td><?= substr($blog['content'],0,50)."..." ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="edit.php?id=<?= $blog['id'] ?>" type="button" class="btn btn-warning" >Edit</a>
                              </div>
                              <div class="container">
                                <a href="delete.php?id=<?= $blog['id'] ?>" 
                                  onclick="return confirm('Are you sure you want to delete this blog?')" 
                                  type="button" class="btn btn-danger" >Delete</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                    <?
                          $i++;
                        }
                      }
                    ?>
                    
                  </tbody>
                </table>
                <br>
                <nav aria-label="Page navigation example" style="float: right;">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{echo '?pageno='.($pageno-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link " href="<?php if($pageno >= $total_pages){echo '#';}else{echo '?pageno='.($pageno+1);}?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->
              
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
