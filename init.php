<?php

    ini_set('display_errors','On');
    error_reporting(E_ALL);
    include "admin/connect.php";

    $sessionUser = '';
    if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }



    $tpl ='include/template/';
    $func ="include/function/";


        include $func . "functions.php";
        include "include/languages/en.php";
        include $tpl . "header.php";






