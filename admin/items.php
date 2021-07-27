<?php

    ob_start();
    session_start();
    $pageTitle='Items';
if(isset($_SESSION['Username'])){

    include 'init.php';

    $do=isset($_GET['do'])?$_GET['do']:'Manage';

    if ($do == 'Manage'){

        $stmt=$con->prepare("SELECT items.* ,
                                                    users.UserName AS user_name,categories.Name AS Category_Name FROM items
                                                    INNER JOIN users ON users.UserID=items.Member_ID
                                                    INNER JOIN categories ON categories.ID=items.Cat_ID ORDER BY Item_ID DESC;
                            ");

        $stmt->execute();

        $items= $stmt-> fetchAll();
        if(!empty($items)){
        ?>

        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>
                    <?php

                    foreach ($items as $item){
                        echo "<tr>";
                        echo "<td>" . $item['Item_ID'] . "</td>";
                        echo "<td>" . $item['Name'] . "</td>";
                        echo "<td>" . $item['Description'] . "</td>";
                        echo "<td>" . $item['Price'] . "</td>";
                        echo "<td>" . $item['Add_Date']."</td>";
                        echo "<td>" . $item['Category_Name']."</td>";
                        echo "<td>" . $item['user_name']."</td>";
                        echo "<td>
                                         <a href='items.php?do=Edit&itemid=".$item['Item_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                         <a href='items.php?do=Delete&itemid=".$item['Item_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                         if ($item['Approve'] == 0){
                                             echo "<a href='items.php?do=Approve&itemid=".$item['Item_ID']."'class='btn btn-info act'><i class='fa fa-check'></i>  Approve </a>";
                                         }
                        echo "</td>";
                        echo "<tr>";
                    }


                    ?>
                    </tr>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
        </div>
        <?php }else{
            echo '<div class="container">';
                echo '<div class="alert alert-info">There Is No Record Show</div>';
                echo  '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
            echo "</div>";
        }

    }elseif($do=="Add"){?>

        <h1 class="text-center title">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="description" class="form-control" placeholder="Description Of The Item">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="price" class="form-control" placeholder="Price Of The Item">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="country" class="form-control" placeholder="Country Of The Made">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-lg-6">
                       <select name="status" class="form-control">
                           <option value="0">...</option>
                           <option value="1">New</option>
                           <option value="2">Like New</option>
                           <option value="3">Used</option>
                           <option value="4">Very Old</option>
                       </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-lg-6">
                        <select name="member" class="form-control">
                            <option value="0">...</option>
                            <?php
                                $allMembers= getAllFrom('*', 'users', '' , '', 'UserID');
                                foreach($allMembers as $user){
                                    echo  "<option value='" .$user['UserID']."'>" .$user['UserName'] ."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-lg-6">
                        <select name="cat" class="form-control">
                            <option value="0">...</option>
                            <?php
                                $allcategories= getAllFrom('*', 'categories', 'WHERE Parent = 0' , '', 'ID');
                                foreach($allcategories as $cat){
                                     echo  "<option value='" .$cat['ID']."'>" .$cat ['Name'] ."</option>";

                                     $childCats= getAllFrom('*', 'categories', "WHERE Parent = {$cat['ID']}" , '', 'ID');

                                     foreach ($childCats as $child) {
                                        echo  "<option value='" .$child['ID']."'>-- " .$child ['Name'] ."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                 <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-lg-6">
                        <input type="text" name="tags" class="form-control" placeholder="Tags Describr Your Ads">
                    </div>
                </div>



                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary ">
                    </div>
                </div>
            </form>
        </div>

        <?php


    }elseif($do=="Insert"){


        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo " <h1 class='text-center'>Insert Items</h1>";

            echo "<div class='container'>";

            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $member      =$_POST['member'];
            $cate        =$_POST['cat'];
            $tags        =$_POST['tags'];

            $formError = array();

            if(empty($name)){
                $formError[]= 'Name cant Be <strong>Empty</strong>';
            }
            if(empty($description)){
                $formError[]= 'description cant Be <strong>Empty</strong>';
            }
            if(empty($price)){
                $formError[]= 'price cant Be <strong>Empty</strong>';
            }
            if(empty($country)){
                $formError[]= 'country cant Be <strong>Empty</strong>';
            }
            if($status == 0){
                $formError[]= 'You Must Choose The <strong>Status</strong>';
            }
            if($member == 0){
                $formError[]= 'You Must Choose The <strong>Member</strong>';
            }
            if($cate == 0){
                $formError[]= 'You Must Choose The <strong>Category</strong>';
            }
            foreach($formError as $error){
                echo "<div class='alert alert-danger'>". $error . "</div";
            }

            if(empty($formError)) {

                    $stmt = $con->prepare("INSERT INTO items( Name , Description, Price, Country_Made, Status,Add_Date,Cat_ID ,Member_ID, Tags)
                                                    VALUES (:zname, :zdec, :zprice, :zcont , :zstat,now(),:zcat,:zmember :ztags)");
                    $stmt->execute(array(

                        'zname'     => $name,
                        'zdec'      => $description,
                        'zprice'    => $price,
                        'zcont'     => $country,
                        'zstat'     => $status,
                        'zcat'      => $cate,
                        'zmember'   => $member,
                        'ztags'     => $tags

                    ));
                    echo "<div class='container'>";
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Inserted</div>";
                    redirectHome($theMsg,'back');
                    echo "</div>";
            }
        }
        else
        {
            $errormsg="<div class='alert alert-danger'>Sorry You Cant Brows This Page Directly</div>";
            redirectHome($errormsg);
        }
        echo "</div>";
    }elseif($do=='Edit'){

        $itemid= isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        $stmt = $con -> prepare("SELECT  * FROM items WHERE  Item_ID = ?");
        $stmt->execute(array($itemid));

        $item= $stmt->fetch();

        $count = $stmt->rowCount();

        if($stmt->rowCount()>0){ ?>

            <h1 class="text-center title">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="name"  class="form-control" required="required" placeholder="Name Of The Item" value="<?php echo $item['Name'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="description" class="form-control" placeholder="Description Of The Item" value="<?php echo $item['Description'] ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="price" class="form-control" placeholder="Price Of The Item" value="<?php echo $item['Price'] ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="country" class="form-control" placeholder="Country Of The Made" value="<?php echo $item['Country_Made'] ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-lg-6">
                            <select name="status" class="form-control">
                                <option value="0">...</option>
                                <option value="1"  <?php  if ($item['Status']==1){echo 'selected';}?>>New</option>
                                <option value="2"  <?php  if ($item['Status']==2){echo 'selected';}?>>Like New</option>
                                <option value="3"  <?php  if ($item['Status']==3){echo 'selected';}?>>Used</option>
                                <option value="4"  <?php  if ($item['Status']==4){echo 'selected';}?>>Very Old</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-lg-6">
                            <select name="member" class="form-control">
                                <?php
                                $stmt=$con->prepare("SELECT * FROM users");
                                $stmt ->execute();
                                $users=$stmt->fetchAll();
                                foreach($users as $user){
                                    echo  "<option value='" .$user['UserID']."'";
                                    if ($item['Member_ID'] == $user['UserID'] ){echo 'selected';}
                                    echo ">" .$user['UserName'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-lg-6">
                            <select name="cat" class="form-control">
                                <?php
                                $stmt2=$con->prepare("SELECT * FROM categories");
                                $stmt2 ->execute();
                                $cats=$stmt2->fetchAll();
                                foreach($cats as $cat){
                                    echo  "<option value='" .$cat['ID']."'";
                                    if ($item['Cat_ID'] == $cat['ID'] ){echo 'selected';}
                                    echo ">" .$cat ['Name'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                     <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="tags" class="form-control" placeholder="Tags Describr Your Ads"  value="<?php echo $item['Tags'] ?>" >
                        </div>
                     </div>


                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Sava Item" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <?php
                $stmt=$con->prepare("SELECT comments.*,users.UserName FROM comments INNER JOIN  users ON users.UserID=comments.User_ID WHERE  Item_ID=?");
                $stmt->execute(array($itemid));
                $rows= $stmt-> fetchAll();

                if(!empty($rows)){
                ?>

                <h1 class="text-center">Manage [ <?php echo $item['Name']?> ] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table table table-bordered text-center">
                            <tr>
                                <td>comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                                foreach ($rows as $row){
                                    echo "<tr>";

                                    echo "<td>" . $row['Comment'] . "</td>";
                                    echo "<td>" . $row['User_ID'] . "</td>";
                                    echo "<td>" . $row['Comment_Date']."</td>";
                                    echo "<td>
                                             <a href='comments.php?do=Edit&comid=".$row['C_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                             <a href='comments.php?do=Delete&comid=".$row['C_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                             if($row['Status'] == 0){echo "<a href='comments.php?do=Approve&comid=".$row['C_ID']."' class='btn btn-info act'><i class='fa fa-check'></i> Approve</a>";}
                                    echo "</td>";
                                    echo "<tr>";}
                            ?>
                        </tr>
                    </table>
                </div>
                <?php }?>
            </div>
    <?php
        } else {

            echo "<div class='container'>";
            $errormsg= "<div class='alert alert-danger'> Theres No Such ID </div>";
            redirectHome($errormsg);
            echo "</div>";
        }
    }elseif($do=='Update'){

        echo " <h1 class=\"text-center title\">Update Item</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $des        = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $Status     = $_POST['status'];
            $cat        = $_POST['cat'];
            $member     = $_POST['member'];
            $tags       = $_POST['tags'];



            $formError = array();

            if(empty($name)){
                $formError[]= 'Name cant Be <strong>Empty</strong>';
            }
            if(empty($des)){
                $formError[]= 'description cant Be <strong>Empty</strong>';
            }
            if(empty($price)){
                $formError[]= 'price cant Be <strong>Empty</strong>';
            }
            if(empty($country)){
                $formError[]= 'country cant Be <strong>Empty</strong>';
            }
            if($Status == 0){
                $formError[]= 'You Must Choose The <strong>Status now</strong>';
            }
            if($member == 0){
                $formError[]= 'You Must Choose The <strong>Member</strong>';
            }
            if($cat == 0){
                $formError[]= 'You Must Choose The <strong>Category</strong>';
            }
            foreach($formError as $error){
                echo "<div class='alert alert-danger'>". $error . "</div";
            }
            if(empty($formError)) {

                $stmt = $con->prepare("UPDATE items SET Name=?, Description=?, Price=?,Country_Made=?,Status=?, Cat_ID=?,Member_ID=?,Tags=? WHERE Item_ID=?");

                $stmt->execute(array($name, $des, $price, $country, $Status , $cat ,$member,$tags, $id));

                $theMsg='<div class="alert alert-success">' . $stmt->rowCount() . " Record Updated</div>";

                redirectHome($theMsg,'back');
            }
        }
        else
        {

            $theMsg="<div class='alert alert-danger'>Sorry You Cant Brows This page Directly</div>";
            redirectHome($theMsg);
        }

    }
    elseif($do=='Delete'){
        echo " <h1 class='text-center title'>Delete Item</h1>";
        echo "<div class='container'>";


        $itemid= isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
      $chack=checkItem('Item_ID', 'items', $itemid);

        if($chack>0) {
            $stmt = $con->prepare('DELETE FROM items WHERE Item_ID=:zid');
            $stmt ->bindParam(":zid",$itemid);
            $stmt->execute();
            $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Deleted</div>";
            redirectHome($theMsg,'back');
        }else{
            $theMsg = '<div class="alert alert-danger>"This ID Is Not Exist</div>';
            redirectHome($theMsg);
        }
        echo "</div>";


    }elseif($do=='Approve'){

        echo "<h1 class='text-center title'>Approve Item</h1>";
        echo "<div class='container'>";


        $itemid= isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;

        $chach=checkItem('Item_ID', 'items', $itemid);


        if($chach>0) {
            $stmt = $con->prepare('UPDATE items SET Approve = 1 WHERE Item_ID= ?');
            $stmt->execute(array($itemid));
            $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Updated</div>";
            redirectHome($theMsg,'back');
        }else{
            $theMsg = '<div class="alert alert-danger>"This ID Is Not Exist</div>';
            redirectHome($theMsg);
        }
        echo "</div>";


    }
    include $tpl . "footer.php";

} else{
    header("Location: index.php");
    exit();
}
ob_end_flush();
?>
