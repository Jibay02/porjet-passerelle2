<?php
    session_start();
    session_unset();
    session_destroy();

    setcookie('auth', '', time() - 1);

    header('location: ConnectionView.php');
    exit();