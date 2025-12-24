<?php
session_start();

if(!function_exists('isLoggedIn')){
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if(!function_exists('checkLogin')){
    function checkLogin() {
        if(!isLoggedIn()){
            header("Location: login.php");
            exit();
        }
    }
}

if(!function_exists('e')){
    function e($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
?>
