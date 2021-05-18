<?php

$db = mysqli_connect('localhost', 'root', '', 'eglrdc_org_db');

if (mysqli_connect_errno()) {

  echo 'Database connection failed with following error: '. mysqli_connect_error();

  die();
}
session_start();

function display_errors($errors){
  $display = '<ul class = "bg-danger">';
  foreach ($errors as $error) {
    $display .= '<li class="text-danger">'.$error.'</li>';
  }
  $display .='</ul>';
  return $display;
}

function sanitize($dirty){
 return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

//function for session
function login($user_id){
  $_SESSION['SBUser'] = $user_id;
  global $db;
  $date = date("Y-m-d H:i:s");
  $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
  $_SESSION['success_flash'] = 'You are now logged in!';
  header('Location: index.php');
}

function is_logged_in(){
  if (isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0) {
     return true;
  }
  return false;
}

function loggin_error_redirect($url = 'login.php'){
   $_SESSION['error_flash'] = 'You must be logged in to access that page.';
   header('Location: '.$url);
}
function permission_error_redirect($url = 'login.php'){
   $_SESSION['error_flash'] = 'You do not have permission to access that page.';
   header('Location: '.$url);
}

function has_permission($permission = 'admin'){
  global $user_data;
  $permissions = explode(',',$user_data['permissions']);
  if (in_array($permission, $permissions, true)) {
    return true;
  }
  return false;
}

function pretty_date($date){
  return date("M d, Y h:i A", strtotime($date));
}

//get user data
if (isset($_SESSION['SBUser'])) {
  $user_id = $_SESSION['SBUser'];
  $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
  $user_data = mysqli_fetch_assoc($query);
  $fn = explode(' ', $user_data['full_name']);
  $user_data['first']= $fn[0];
  $user_data['last']= $fn[1];
  $userFullName = $user_data['first'] .' '.$user_data['last'];
}

if (isset($_SESSION['success_flash'])) {
    echo '<div class="bg-success">
       <p class="text-success text-center">
        '.$_SESSION['success_flash'].'
       </p>
    </div>';
    unset($_SESSION['success_flash']);
}

if (isset($_SESSION['error_flash'])) {
    echo '<div class="bg-danger">
       <p class="text-danger text-center">
        '.$_SESSION['error_flash'].'
       </p>
    </div>';
    unset($_SESSION['error_flash']);
}
