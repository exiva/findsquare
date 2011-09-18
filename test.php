<?
// session_start();

// $_SESSION['oauth'] = $_GET['token'];

// print $_SESSION;

setcookie('test',$_GET['token']);

if(isset($_COOKIE['test']))
 { 
 $last = $_COOKIE['test']; 
 echo "Welcome back! <br> You last visited on ". $last; 
 } 
 else 
 { 
 echo "Welcome to our site!"; 
 } 


?>