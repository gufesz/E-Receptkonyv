<?php
sessiom_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

?>