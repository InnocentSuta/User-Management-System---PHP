<?php
require_once 'core/init.php';

if (!is_logged_in()) {
   header('Location: login.php');
}

include "includes/head.php";
include "includes/navigation.php";

$userQuery ="SELECT * FROM users ORDER BY full_name";
$userResult = $db->query($userQuery);
if (isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured= (int)$_GET['featured'];
  $featureSql="UPDATE users SET featured = '$featured' WHERE id = '$id'";
  $db->query( $featureSql);
  header('Location: index.php');
}
?>

 
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">USERS LIST</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Users </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
  <section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="">
      <a href="users.php?add=1" class="btn btn-success">Add New User</a>
        <table class="table table-bordered table-stripped table-condensed">
          <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Join Date</th>
            <th>Roles</th>
            <th>Featured</th>
            <th> Actions</th>
          </thead>
          <tbody>
  <?php
  while ($user_row = mysqli_fetch_assoc($userResult)):
     $name = $user_row['full_name'] ;
     $email = $user_row['email'] ;
     $join_date = $user_row['join_date'] ;
     $last_login = $user_row['last_login'] ;
     $permissions = $user_row['permissions'] ;
     $featured = $user_row['featured'] ;
   ?>
  <tr>
     <td><?= $name;?></td>
     <td><?= $email ;?></td>
     <td><?= pretty_date($join_date) ;?></td>
     <td><?= $permissions ;?></td>
     <?php if($user_row['id'] != $user_data['id']): ?>
     <td><a href="users.php?featured=<?= (($featured == 0)?'1':'0');?>&id=<?= $user_row['id'];?>" class="btn btn-xs btn-default">
     <span class="glyphicon glyphicon-<?= (($featured == 1)?'minus':'plus'); ?>"></span>
   </a>&nbsp;<?= (($featured == 1)?'Featured Users':'');?>
     </td>
    <?php endif; ?>
 <td>
   <?php if($user_row['id'] != $user_data['id']): ?>
     <a href="users.php?edit=<?= $user_row['id'];?>" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit </a></a>
     <a href="users.php?delete=<?= $user_row['id'];?>" class="btn btn-danger btn-sm"> <i class="fas fa-trash"></i>Delete </a></a>
   <?php endif; ?>
  </td>
  </tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>
</div>
</section>

    
    <!-- /.content -->
  </div>


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="#">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="Assets/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="Assets/js/adminlte.min.js"></script>
</body>
</html>
