<?php

ob_start();
session_unset();
if(isset($_SESSION['UserName'])){
    include 'init.php';
    $do=isset($_GET['do'])?$_GET['do']:'Manage';
    if ($do == 'Manager'){

    }elseif($do=="Add"){

    }elseif($do=="Insert"){

    }elseif($do=='Edit'){

    }elseif($do=='Update'){

    }
    elseif($do=='Delete'){

    }elseif($do=='Activate'){

    }
    include $tpl . "footer.php";

} else{
    header("Location: index.php");
    exit();
}
ob_end_flush();
?>

