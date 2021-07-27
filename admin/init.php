<?php
    include "connect.php";


    $tpl ='include/template/';
    $func ="include/function/";


        include $func . "functions.php";
        include "include/languages/en.php";
        include $tpl . "header.php";

        if(!isset($nonavbar)){
            include $tpl . "Navbar.php";
        }



