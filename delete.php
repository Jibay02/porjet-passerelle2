<?php 
session_start();
require_once('src/option.php');


if(!isset($_SESSION['connect'])) {
    header('location: ConnectionView.php');
    exit();
}

if(isset($_SESSION['connect']) && $_SESSION['id']!= 1 && isset($_COOKIE['auth'])){
    header('location: TestimonialsView.php');
    exit();
}

if(isset($_GET['edit'])){
    require('src/connection.php');

    $delete_id = htmlspecialchars($_GET['edit']);

    $delete= $bdd->prepare('DELETE FROM blog WHERE id= ?');
    $delete->execute([$delete_id]);
    
    header('location: TestimonialsView.php');
    exit();
}