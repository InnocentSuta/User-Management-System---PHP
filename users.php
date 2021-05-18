<?php
require_once 'core/init.php';

if (!is_logged_in()) {
  loggin_error_redirect();
}

include "includes/head.php";
include "includes/navigation.php";

if (isset($_GET['delete'])) {
   $delete_id = sanitize($_GET['delete']);
   $db->query("DELETE FROM users WHERE id = '$delete_id'");
   $_SESSION['success_flash'] = 'User has been deleted!';
   header('Location: users.php');
}

//ADD NEW USER
if (isset($_GET['add']) || isset($_GET['edit'])) {
//add here..
$nameUser = ((isset($_POST['name']) && isset($_POST['name']) != '')?sanitize($_POST['name']):'');
$email = ((isset($_POST['email']) && isset($_POST['email']) != '')?sanitize($_POST['email']):'');
$password = ((isset($_POST['password']) && isset($_POST['password']) != '')?sanitize($_POST['password']):'');
$confirm = ((isset($_POST['confirm']) && isset($_POST['confirm']) != '')?sanitize($_POST['confirm']):'');
$permissions = ((isset($_POST['permissions']) && isset($_POST['permissions']) != '')?sanitize($_POST['permissions']):'');
$errors = array();

  //EDIT USER
  if (isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $editSql = $db->query("SELECT * FROM users WHERE id ='$edit_id'");
    $edit_row = mysqli_fetch_assoc($editSql);
    $nameUser = ((isset($_POST['name']) && isset($_POST['name']) != '')?sanitize($_POST['name']):$edit_row['full_name']);
    $email = ((isset($_POST['email']) && isset($_POST['email']) != '')?sanitize($_POST['email']):$edit_row['email']);
    $password = ((isset($_POST['password']) && isset($_POST['password']) != '')?sanitize($_POST['password']):$edit_row['password']);
    $confirm = ((isset($_POST['confirm']) && isset($_POST['confirm']) != '')?sanitize($_POST['confirm']):$edit_row['password']);
    $permissions = ((isset($_POST['permissions']) && isset($_POST['permissions']) != '')?sanitize($_POST['permissions']):$edit_row['permissions']);
  }

  if ($_POST) {
    $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
    $emailCount = mysqli_num_rows($emailQuery);
    if ($emailCount != 0) {
      $errors [] = 'The email ( '.$email.' ) already exist in our database';
    }

    $required = array('name', 'email', 'password', 'confirm', 'permissions');
    foreach ($required as $f) {
      if (empty($_POST[$f])) {
        $errors[] = 'You must fill out all fields';
        break;
      }
    }

    if (strlen($password) < 6) {
      $errors[] = 'Your password must be at least 6 characters.';
    }

    if ($password != $confirm ) {
    $errors[] = 'Your passwords do not match.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] ='You must enter a valid email.' ;
    }
    if (!empty($errors)) {
       display_errors($errors);
    }else {
      //add user to the database
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $insertQuery = "INSERT INTO users (`full_name`, `email`, `password`, `permissions`, `image`) VALUES ('$nameUser', '$email', '$hashed', '$permissions')";
      $_SESSION['success_flash'] = 'User has been added!';
      if (isset($_GET['edit'])) {
        $insertQuery = "UPDATE users SET full_name='$nameUser', email='$email', password='$hashed', permissions='$permissions' WHERE id = '$edit_id'";
        $_SESSION['success_flash'] = 'User has been edited!';
      }

      $db->query($insertQuery);
      header('Location: index.php');
    }
  }
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
       <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <!-- <div class="col-sm-6">
            <h1 class="m-0">Starter Page</h1>
          </div>/.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active"> Users </li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card card-primary">
              <div class="card-header">
                <h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit ':'Add A New '); ?> User</h2><hr>
              </div>
              <?= ((isset($errors) && !empty($errors))?display_errors($errors):''); ?>
              <form action="users.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post">
                <div class="card-body">
                  <div class="form-group">
                   <label for="name">Full Name:</label>
                   <input type="text" name="name" id="name" class="form-control" value="<?= $nameUser ;?>">
                  </div>

                  <div class='form-group'>
                   <label for="email">Email:</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?= $email;?>">
                  </div>

                  <div class='form-group'>
                  <label for="password">Password:</label>
                  <input type="password" name="password" id="password" class="form-control" value="<?= $password;?>">
                  </div>

                  <div class='form-group'>
                  <label for="confirm">Confirm Password:</label>
                  <input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm;?>">
                  </div>

                  <div class='form-group'>
                     <label for="permission">Permissions:</label>
                    <select class="form-control" name="permissions">
                      <option value=""<?= (($permissions == '')?' Selected':'');?>></option>
                      <option value="editor"<?= (($permissions == 'editor')?' Selected':'');?>>Editor</option>
                      <option value="admin,editor"<?= (($permissions == 'admin,editor')?' Selected':'');?>>Admin</option>
                    </select>
                  </div>
                  
                  <div class='form-group'>
                    <a href="users.php" class="btn btn-default">Cancel</a>
                    <input type="submit" value="<?= ((isset($_GET['edit']))?'Edit ':'Add A New ');?> User" class="btn btn-primary">
   
                  </div>  
                </div>
              </form>
          </div> 
        </div>
      </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div> <!-- /.content -->

<?php
}else {
$userQuery ="SELECT * FROM users ORDER BY full_name";
$userResult = $db->query($userQuery);
if (isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured= (int)$_GET['featured'];
  $featureSql="UPDATE users SET featured = '$featured' WHERE id = '$id'";
  $db->query( $featureSql);
  header('Location: users.php');
}
 ?>

<h2>Users</h2>
<a href="users.php?add=1" class="btn btn-success">Add New User</a>
<hr>
<table class="table table-bordered table-stripped table-condensed">
<thead>
  <th></th>
  <th>Name</th>
  <th>Email</th>
  <th>Join Date</th>
  <th>Last Login</th>
  <th>Permissions</th>
  <th>Featured</th>
</thead>
<tbody>
  <?php
  while ($user_row = mysqli_fetch_assoc($userResult)):
     $photoUser = $user_row['image'] ;
     $name = $user_row['full_name'] ;
     $email = $user_row['email'] ;
     $join_date = $user_row['join_date'] ;
     $last_login = $user_row['last_login'] ;
     $permissions = $user_row['permissions'] ;
     $featured = $user_row['featured'] ;
   ?>
  <tr>
  <td>
   <?php if($user_row['id'] != $user_data['id']): ?>
     <a href="users.php?edit=<?= $user_row['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
     <a href="users.php?delete=<?= $user_row['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
   <?php endif; ?>
  </td>
     <td><?= $name;?></td>
     <td><?= $email ;?></td>
     <td><?= pretty_date($join_date) ;?></td>
     <td><?=(($user_row['last_login'] == '0000-00-00 00:00:00')?'Never logged in':pretty_date($last_login));?></td>
     <td><?= $permissions ;?></td>
     <?php if($user_row['id'] != $user_data['id']): ?>
     <td><a href="users.php?featured=<?= (($featured == 0)?'1':'0');?>&id=<?= $user_row['id'];?>" class="btn btn-xs btn-default">
     <span class="glyphicon glyphicon-<?= (($featured == 1)?'minus':'plus'); ?>"></span>
   </a>&nbsp;<?= (($featured == 1)?'Featured Users':'');?>
     </td>
    <?php endif; ?>
  </tr>
<?php endwhile; ?>
</tbody>
</table>

<?php }  ?>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
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
