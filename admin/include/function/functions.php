<?php


     function getAllFrom($field, $table, $where = NULL, $orderfield, $ordering = "DESC"){
        global $con;
        
        $getAll=$con->prepare("SELECT $field FROM $table $where ORDER BY $orderfield $ordering");

        $getAll->execute();

        $all=$getAll->fetchAll();
        
        return $all;
    }



    function getTitle(){

        global $pageTitle;
        if(isset($pageTitle)){
            echo $pageTitle;
        }else {
            echo "Default";
        }
    }
    function redirectHome($theMsg, $url=null, $seconds=3){
        if($url==null){
            $url = 'index.php';
        }else{
            if(isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER']!==''){
                $url=$_SERVER['HTTP_REFERER'];
            }else{
                $url='index.php';
            }

        }
        echo  $theMsg;
        echo  "<div class='alert alert-info'>You Will Be Redirected To HomePage After $seconds Seconds.</div>";
        header("refresh:$seconds;url=$url");
        exit();
    }

    function checkItem($select, $from, $value){
        global $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select=?");
        $statement->execute(array($value));
        $count =$statement->rowCount();
        return $count;
    }
    function countItems($item,$table){
        global $con;
        $stmt2=$con->prepare("SELECT COUNT($item)FROM  $table");
        $stmt2->execute();
        return $stmt2 ->fetchColumn();
    }
    function getLatest($select, $table ,$ord ,$limt = 5){
        global $con;
        $getstmt=$con->prepare("SELECT $select FROM $table ORDER BY $ord DESC LIMIT $limt");
        $getstmt->execute();
        $rows=$getstmt->fetchAll();
        return $rows;
    }


