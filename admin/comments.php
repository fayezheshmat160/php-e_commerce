<?php

session_start();
$pageTitle = "Comments" ;

if(isset($_SESSION["Username"])){

    include "init.php";
    $do =  isset($_GET['do']) ? $_GET['do'] :'Manage';

    if($do == 'Manage'){

        $stmt=$con->prepare("SELECT comments.*,items.Name, users.UserName FROM comments INNER JOIN items ON items.Item_ID=comments.Item_ID INNER JOIN  users ON users.UserID=comments.User_ID ORDER BY C_ID DESC");
        $stmt->execute();
        $rows= $stmt-> fetchAll();
        if(!empty($rows)){
        ?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>#ID</td>
                        <td>comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php

                    foreach ($rows as $row){
                        echo "<tr>";
                        echo "<td>" . $row['C_ID'] . "</td>";
                        echo "<td>" . $row['Comment'] . "</td>";
                        echo "<td>" . $row['Item_ID'] . "</td>";
                        echo "<td>" . $row['User_ID'] . "</td>";
                        echo "<td>" . $row['Comment_Date']."</td>";
                        echo "<td>
                                         <a href='comments.php?do=Edit&comid=".$row['C_ID']."' class=\"btn btn-success\"><i class='fa fa-edit'></i> Edit</a>
                                         <a href='comments.php?do=Delete&comid=".$row['C_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                        if($row['Status'] == 0){
                            echo "<a href='comments.php?do=Approve&comid=".$row['C_ID']."' class='btn btn-info act'><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</td>";
                        echo "<tr>";
                    }

                    ?>
                    </tr>
                </table>
            </div>
        </div>
        <?php }else{
            echo '<div class="container">';
            echo '<div class="alert alert-info">There Is No Record Show</div>';
            echo "</div>";
        }

        ?>

    <?php }
    elseif ($do == 'Edit'){


        $comid= isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;
        $stmt = $con -> prepare("SELECT  * FROM comments WHERE  C_ID = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count>0){ ?>

            <h1 class="text-center title">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>"/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-lg-6">
                            <textarea class="form-control" name="comment">
                                <?php echo $row['Comment']?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="save" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            <?php
        }
        else {
            echo "<div class='container'>";
            $errormsg= "<div class='alert alert-danger'> Theres No Such ID </div>";
            redirectHome($errormsg);
            echo "</div>";
        }
    }elseif($do=="Update"){


        echo "<h1 class='text-center title'>Update Comment</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];

                $stmt = $con->prepare("UPDATE comments SET Comment=? WHERE C_ID=?");
                $stmt->execute(array($comment, $comid));
                $theMsg='<div class="alert alert-success">' . $stmt->rowCount() . " Record Updated</div>";
                redirectHome($theMsg,'back');
        }
        else
        {

            $theMsg="<div class='alert alert-danger'>Sorry You Cant Brows This page Directly</div>";
            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif($do=='Delete'){
        echo " <h1 class='text-center title'>Delete Comment</h1>";
        echo "<div class='container'>";


        $comid= isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;
            $check=checkItem('C_ID', 'comments', $comid);
        if($check>0) {
            $stmt = $con->prepare('DELETE FROM comments WHERE C_ID=:zid');
            $stmt ->bindParam(":zid",$comid);
            $stmt->execute();
            $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Deleted</div>";
            redirectHome($theMsg,'back');
        }else{
            $theMsg = '<div class="alert alert-danger>"This ID Is Not Exist</div>';
            redirectHome($theMsg);
        }
        echo "</div>";


    }elseif($do=='Approve'){

        echo "<h1 class='text-center title'>Approve Comment</h1>";
        echo "<div class='container'>";


        $comid= isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;

        $check=checkItem('C_ID', 'comments', $comid);

        if($check>0) {
            $stmt = $con->prepare('UPDATE comments SET Status = 1 WHERE C_ID= ?');
            $stmt->execute(array($comid));
            $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Approved</div>";
            redirectHome($theMsg,'back');
        }else{
            $theMsg = '<div class="alert alert-danger>"This ID Is Not Exist</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    }

    include $tpl . "footer.php";

}
else{
    header("Location: index.php");
    exit();
}