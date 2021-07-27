<?php

    $do =  isset($_GET['do']) ? $_GET['do'] :'manager';

    if ($do == 'Manager'){
        echo "Welcome You Are IN Manage Category Page";
        echo '<a href="?do=Insert">Add New Category+</a>';
    }elseif($do=="Add"){
        echo "Welcome You Are IN Add Category Page";
    }elseif($do=="Insert"){
        echo "Welcome You Are IN Insert Category Page";
    }else{
        echo "ERROR";
    }