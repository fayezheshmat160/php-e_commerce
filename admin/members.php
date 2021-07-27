<?php

session_start();
$pageTitle = "Members" ;

if(isset($_SESSION["Username"])){

    include "init.php";
    $do =  isset($_GET['do']) ? $_GET['do'] :'Manage';

    if($do == 'Manage'){

        $query= '';
        if(isset($_GET['page']) && $_GET['page'] == "pending"){
            $query = 'AND RegesterStutas=0';
        }



        $stmt=$con->prepare("SELECT * FROM users WHERE GroupID !=1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows= $stmt-> fetchAll();
        if(!empty($rows)){
        ?>

        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Fullname</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>
                    <?php

                        foreach ($rows as $row){
                            echo "<tr>";
                                echo "<td>" . $row['UserID'] . "</td>";
                                echo "<td>" . $row['UserName'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date']."</td>";
                                echo "<td>
                                         <a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                         <a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                      if($row['RegesterStutas'] == 0){
                                          echo "<a href='members.php?do=Activate&userid=".$row['UserID']."' class='btn btn-info act'><i class='fa fa-check'></i> Activate</a>";
                                      }
                                      echo "</td>";
                            echo "<tr>";
                        }


                    ?>
                    </tr>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>
        </div>
        <?php }else{
            echo '<div class="container">';
                echo '<div class="alert alert-info">There Is No Record Show</div>';
                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
            echo "</div>";
        }

        ?>


    <?php }
    elseif($do=="Add"){?>

        <h1 class="text-center title">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="email" name="email" class="form-control" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="full" class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">User Avatar</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="file" name="avatar" class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>

        <?php

    }elseif ($do=="Insert"){

        if($_SERVER['REQUEST_METHOD']=='POST'){
        echo " <h1 class='text-center'>Insert Member</h1>";
        echo "<div class='container'>";


            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp  = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtension= array('jpeg', 'jpg','png', 'gif');

            $stringone=explode('.', $avatarName);

            $string=end($stringone);

            $Extension = strtolower($string);


            $user  = $_POST['username'];
            $pass  = $_POST['password'];
            $email = $_POST['email'];
            $name  = $_POST['full'];


            $hashpass=sha1($_POST['password']);

            $formError = array();

            if(empty($user)){
                $formError[]= 'Username cant Be Empty';
            }
            if(empty($pass)){
                $formError[]= 'password cant Be Empty';
            }
            if(strlen($user)<4){
                $formError[]= 'Username cant Be Leas Than 4 character';
            }
            if(empty($email)){
                $formError[]= 'Email cant Be Empty';
            }
            if(empty($name)) {
                $formError[] = 'Name cant Be Empty';
            }
            if(strlen($name )< 4){
                $formError[]='Name Is Shorter Than';
            }
            if(! empty($avatarName) && ! in_array($Extension, $avatarAllowedExtension)){

                 $formError[]='This Extension Is Not Allowed';
            }

             if(empty($avatarName)){

                 $formError[]='Avatar is Required';
            }

             if($avatarSize > 6194304 ){

                 $formError[]='Avatar Cant Be Larger Thin 6MB';
            }


            foreach($formError as $error){
                echo "<div class='alert alert-danger'>". $error . "</div";
            }

            if(empty($formError)) {

                $avatar = rand(0 ,100000000000) . '_'  .$avatarName;
                move_uploaded_file($avatarTmp,"uplodes\avater\\" .$avatar);

                $check = checkItem("UserName", "users", $user);

                if ($check == 1) {
                    $theMsg= "<div class='alert alert-danger'>Sorry The Username Is Exist</div>";
                    redirectHome($theMsg,'back');
                } else {


                    $stmt = $con->prepare("INSERT INTO users(UserName, Password, Email, FullName, RegesterStutas,Date, Avatar) VALUES (:zuser, :zpass, :zmail, :zname , 1,now(),:zavatar)");
                    $stmt->execute(array(

                        'zuser'     => $user,
                        'zpass'     => $hashpass,
                        'zmail'     => $email,
                        'zname'     => $name,
                        'zavatar'   => $avatar

                    ));
                    echo "<div class='container'>";
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Inserted</div>";
                    redirectHome($theMsg,'back');
                     echo "</div>";
                }
            }
        }
        else
        {
            $errormsg="<div class='alert alert-danger'>Sorry You Cant Brows This Page Directly</div>";
            redirectHome($errormsg);
        }
        echo "</div>";
    }
    elseif ($do == 'Edit'){


        $userid= isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
        $stmt = $con -> prepare("SELECT  * FROM users WHERE  UserID = ?");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($stmt->rowCount()>0){ ?>

        <h1 class="text-center title">Edit Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>"/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="username" class="form-control" value="<?php echo $row['UserName']?>" autocomplete="off" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="hidden" name="oldPassword" value="<?php echo $row['password'] ?>"  autocomplete="new-password">
                        <input type="Password" name="newPassword" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="email" name="email"  value="<?php echo $row['Email'] ?>" class="form-control" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="full"  value="<?php echo $row['FullName'] ?>" class="form-control" required="required">
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


        echo " <h1 class='text-center title'>Update Member</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            $pass=empty($_POST['newPassword'])? $_POST['oldPassword']:sha1($_POST['newPassword']);

            $formError = array();

            if(empty($user)){
                $formError[]= 'Username cant Be Empty';
            }
            if(strlen($user)<4){
                $formError[]= 'Username cant Be Leas Than 4 character';
            }
            if(empty($email)){
                $formError[]= 'Email cant Be Empty';
            }
            if(strlen($email)< 4){
                $formError[]= 'Email cant Be Leas Than 4 character Empty';
            }
            if(empty($name)) {
                $formError[] = 'Name cant Be Empty';
            }
            if(strlen($name )< 4){
                $formError[]='Name Is Shorter Than';
            }

            foreach($formError as $error){
                echo "<div class='alert alert-danger'>" .$error . "</div";
            }

            if(empty($formError)) {
                $stmt2=$con -> prepare('SELECT * FROM users WHERE UserName=? AND UserID!=?');
                $stmt2->execute(array($user,$id));
                $count=$stmt2->rowCount();
                if($count==1){
                    $theMsg= '<div class="alert alert-danger">This User Is Exist</div>';
                    redirectHome($theMsg,'back');

                }else{

                $stmt = $con->prepare("UPDATE users SET Username=?, Email=?, FullName=?,password=? WHERE UserID=?");
                $stmt->execute(array($user, $email, $name, $pass, $id));
                $theMsg='<div class="alert alert-success">' . $stmt->rowCount() . " Record Updated</div>";
                redirectHome($theMsg,'back');
            }
            }
        }
        else
        {

            $theMsg="<div class='alert alert-danger'>Sorry You Cant Brows This page Directly</div>";
            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif($do=='Delete'){
        echo " <h1 class=\"text-center title\">Delete Member</h1>";
        echo "<div class='container'>";


            $userid= isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
            $stmt = $con -> prepare("SELECT  * FROM users WHERE  UserID = ?");
            $stmt->execute(array($userid));


            $count = $stmt->rowCount();


            if($stmt->rowCount()>0) {
                $stmt = $con->prepare('DELETE FROM users WHERE UserId=:zuser');
                $stmt ->bindParam(":zuser",$userid);
                $stmt->execute();
                $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Deleted</div>";
                redirectHome($theMsg);
            }else{
                $theMsg = '<div class="alert alert-danger>"This ID Is Not Exist</div>';
                redirectHome($theMsg,'back');
            }
            echo "</div>";


            }elseif($do=='Activate'){

                echo "<h1 class='text-center title'>Activate Member</h1>";
                echo "<div class='container'>";


                $userid= isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
                $stmt = $con -> prepare("SELECT  * FROM users WHERE  UserID = ?");
                $stmt->execute(array($userid));


                $count = $stmt->rowCount();


                if($stmt->rowCount()>0) {
                    $stmt = $con->prepare('UPDATE users SET RegesterStutas = 1 WHERE UserID= ?');
                    $stmt->execute(array($userid));
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Updated</div>";
                    redirectHome($theMsg);
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