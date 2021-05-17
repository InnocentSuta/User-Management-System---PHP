<?php
 require_once 'core/init.php';
 include 'includes/head.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

$errors = array();
 ?>


<body class="hold-transition login-page">
<div class="login-box">
<div class="">
     <?php
            if ($_POST) {
              # form validation
              if (empty($_POST['email']) || empty($_POST['password']) ) {
                $errors[] = 'You must provide  email and password.';
              }

              //validate email
              if (!Filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[]= 'You must enter a valid email.';
              }

              //password is more than 6 characters
              if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
              }


              //check if email exist in the database
              $query = $db->query("SELECT * FROM users WHERE email = '$email' AND featured = 1");
              $user = mysqli_fetch_assoc($query);
              $userCount = mysqli_num_rows($query);
              if ($userCount < 1) {
                $errors[] ='That email doesn\'t exist in our database';
              }

              if (!password_verify($password, $user['password'])) {
                $errors[] = 'The password does not match our records. Please try again.';
              }

              //check for errors
              if (!empty($errors)) {
                echo display_errors($errors);
              }else {
                //log user in
                $user_id = $user['id'];
                login($user_id);
                
                header('Location: index.php');
                echo "<h3><a href='index.php'>Click to start</a></h3>";
                exit();
              }
            }
           ?>
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Admin</b>CITS</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">CITS | Sign in</p>

      <form action="login.php" method="post" id="login-form">
        <div class="input-group mb-3">
          <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?= $email;?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name ="password" id="password" class="form-control" placeholder="Password" value="<?= $password;?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name ="rem" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div> -->
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block" id="Login">Login In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register</a>
      </p> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="./Assets/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./Assets/js/adminlte.min.js"></script>

<script src="./Assets/js/cits.js"></script>

</body>
</html>
