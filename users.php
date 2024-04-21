<?php

include("db.php");
$errors=array();
$username='';
$email='';
$password='';

if (isset($_POST['register-btn'])){
  global $conn;
    
  if(empty($_POST['username'])){
    array_push($errors, 'Username is required');
  }
  if(empty($_POST['email'])){
    array_push($errors, 'Email is required');
  }
  if(empty($_POST['password'])){
    array_push($errors, 'Password is required');
  }
  $existingUser= selectOne('users' ,['email'=> $_POST['email']]);
  if(isset($existingUser)){
    array_push($errors, 'email alredy existe');
  }
  if(count($errors) ==0 ){
    unset($_POST['register-btn']);
    $_POST['admin'] = 0;
    $_POST['password']= password_hash($_POST['password'],PASSWORD_DEFAULT);
    $user_id= create('users' ,$_POST);
    $user =selectOne('users', ['id' => $user_id]);

    //log in
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['admin'] = $user['admin'];
    $_SESSION['message'] = 'you are now logged in';
    $_SESSION['type'] = 'sucess';
    if ($_SESSION['admin']) {
      header('location: ../pages/login.php');
    }else {
      header('location: ../pages/index.php');
    }

    exit();
    //login end

  }else{
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
  }
}
if(isset($_POST["login-btn"])){

  if(empty($_POST['username'])){
    array_push($errors, 'Username is required');
  }
  if(empty($_POST['password'])){
    array_push($errors, 'Password is required');
  }
  if(count($errors) ==0 ){
    $user=selectOne('users',['username'=>$_POST['username']]);
    if ($user && password_verify($_POST['password'],$user['password'])) {
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['admin'] = $user['admin'];
      $_SESSION['message'] = 'you are now logged in';
      $_SESSION['type'] = 'sucess';
      if ($_SESSION['admin']) {
        header('location: ../pages/login.php');
      }else {
        header('location: ../pages/index.php');
      }
      exit();

    } else {
      array_push($errors,'Wrong password');
    }  
  }
  $username = $_POST['username'];
  $password = $_POST['password'];

}
?>