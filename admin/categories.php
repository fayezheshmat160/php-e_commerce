<?php

    ob_start();

    session_start();

    if(isset($_SESSION['Username'])){

        include 'init.php';

        $do=isset($_GET['do'])?$_GET['do'] : 'Manage';

        if ($do == 'Manage'){

            $sort='ASC';
            $sort_array=array('ASC','DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort=$_GET['sort'];
            }
            $stmt2=$con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordring $sort");
            $stmt2->execute();
            $cats=$stmt2->fetchAll();
            if(!empty($cats)){
                ?>
            <h1 class="text-center">Manage Caregories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-Heading">
                        <i class="fa fa-edit"></i> Manage Caregories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering: [
                            <a class="<?php if($sort == 'ASC'){echo 'active';}?>" href="?sort=ASC">Asc </a> |
                            <a class="<?php if($sort == 'DESC'){echo 'active';}?>" href="?sort=DESC">Desc</a>]
                            <i class="fa fa-eye"></i> View: [
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">Classic</span>]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ($cats as $cat){
                            echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";
                                echo "<h3>" . $cat['Name'] . '</h3>';
                                echo "<div class='full-view'>";
                                    echo "<p>";if($cat['Description']==''){echo 'This category has no description';} else {echo $cat['Description'];}echo "</p>";
                                    if($cat['Visablty'==1]){echo '<span class="visiblity"><i class="fa fa-eye"></i> Hidden</span>';}
                                    if($cat['Allow_Comment'==1]){echo '<span class="commenting"><i class="fa fa-close"></i> Comment Dsabled</span>';}
                                    if($cat['Allow_Ads'==1]){echo '<span class="ads"><i class="fa fa-close"></i> Ads Dsabled</span>';}
                                echo "</div>";


                                    $childCats=getAllFrom('*', 'categories', "WHERE Parent = {$cat['ID']}" ,'', 'ID', 'ASD');
                                    if(! empty($childCats)){
                                        echo "<h4 class='child-head'>Child Categories</h4>";
                                        echo "<ul class='list-unstyled child-cats'>";
                                    foreach ($childCats as $childcat){
                                        echo "<li class='child-link'>
                                       
                                        <a href='categories.php?do=Edit&catid=" . $childcat['ID'] ."'>" . $childcat['Name'] . "</a>
                                        <a href='categories.php?do=Delete&catid=" . $childcat['ID'] ."' class='show-delete confirm'> Delete</a>

                                        </li>";
                                    }

                                    echo "</ul>";
                                }

                            echo "</div>";
                            echo "<hr>";

                        }
                        ?>
                    </div>
                </div>
                <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
            </div>
           <?php }else{
            echo '<div class="container">';
                echo '<div class="alert alert-info">There Is No Record Show</div>';
                echo  '<a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Category</a>';
            echo "</div>";
        }
            ?>

        <?php
        }elseif($do=="Add"){?>

            <h1 class="text-center title">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type='text' name="description" class="form-control">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text " name="ordering" class="form-control">
                        </div>
                    </div>

                      <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-lg-6">
                            <select name= "parent" class="form-control">
                                <option>None</option>
                                <?php 

                                    $allCats=getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID', 'ASC');

                                    foreach ($allCats as $cats) {
                                        
                                        echo "<option value='". $cats['ID']."'>" . $cats['Name']."</option>";

                                    }
                                ?>

                            </select>
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-lg-6">
                            <div>
                                 <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                 <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1">
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>



                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-lg-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1">
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-lg-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1">
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value=" Add Category" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>



            <?php

        }elseif($do=="Insert") {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center title'>Insert Categories</h1>";
                echo "<div class='container'>";


                $name        = $_POST['name'];
                $des         = $_POST['description'];
                $parent      = $_POST['parent'];
                $order       = $_POST['ordering'];
                $visible     = $_POST['visibility'];
                $commenting  = $_POST['commenting'];
                $ads         = $_POST['ads'];

                $check = checkItem("Name", "categories", $name);

                if ($check == 1) {
                    $theMsg = "<div class='alert alert-danger'>Sorry The Category Is Exist</div>";
                    redirectHome($theMsg, 'back');
                } else {


                    $stmt = $con->prepare("INSERT INTO categories
                        (Name, Description,Parent, Ordring, Visablty, Allow_Comment,Allow_Ads)
                        VALUES (:zName, :zDescription,:zParent, :zOrdring, :zVisablty , :zAllow_Comment,:zAllow_Ads)");
                    $stmt->execute(array(

                        'zName'           => $name,
                        'zDescription'    => $des,
                        'zParent'         => $parent,
                        'zOrdring'        => $order,
                        'zVisablty'       => $visible,
                        'zAllow_Comment'  => $commenting,
                        'zAllow_Ads'      => $ads
                    ));
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . " Record Inserted</div>";
                    redirectHome($theMsg, 'back');
                    echo "</div>";

                }
            } else {
                $errormsg = "<div class=\"alert alert-danger\">Sorry You Cant Brows This Page Directly</div>";
                redirectHome($errormsg, 'back');
            }
            echo "</div>";

        }elseif($do == 'Edit'){

            $catid= isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0;
            $stmt = $con -> prepare("SELECT  * FROM categories WHERE  ID = ?");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $count = $stmt->rowCount();

            if($count>0){ ?>

                <h1 class="text-center title">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-lg-6">
                                <input type="text" name="name" class="form-control" value="<?php echo $cat['Name']?>" autocomplete="off"  required="required" placeholder="Name of The Category">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-lg-6">
                                <input type="text" name="description" class="form-control"  value="<?php echo $cat['Description']?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-lg-6">
                                <input type="ordering" name="ordering"  value="<?php echo $cat['Ordring']?>" class="form-control">
                            </div>
                        </div>

                          <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10 col-lg-6">
                            <select name= "parent" class="form-control">
                                <option>None</option>
                                <?php 

                                    $allCats=getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID', 'ASC');

                                    foreach ($allCats as $cats) {
                                        
                                         echo "<option value='". $cats['ID']."'";

                                            if($cat['Parent'] == $cats['ID']){echo 'selected';}

                                         echo ">" . $cats['Name']."</option>";

                                    }
                                ?>

                            </select>
                        </div>
                    </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-lg-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visablty']== 0){echo 'checked';} ?>>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1"  <?php if($cat['Visablty']== 1){echo 'checked';} ?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-lg-6">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']== 0){echo 'checked';} ?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1"<?php if($cat['Allow_Comment']== 1){echo 'checked';} ?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-lg-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']== 0){echo 'checked';} ?>>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']== 1){echo 'checked';} ?>>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="save" class="btn btn-primary ">
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



        }elseif($do=='Update'){


            echo "<h1 class='text-center title'>Update Category</h1>";
            echo "<div class='container'>";
            if($_SERVER['REQUEST_METHOD']=='POST')
            {

                $id          = $_POST['catid'];
                $name        = $_POST['name'];
                $description = $_POST['description'];
                $ordering    = $_POST['ordering'];
                $parent      = $_POST['parent'];
                $visibility  = $_POST['visibility'];
                $commenting  = $_POST['commenting'];
                $ads         = $_POST['ads'];

                    $stmt = $con->prepare("UPDATE categories SET Name=? , Description=?, Ordring=?,Parent=?, Visablty =?, Allow_Comment=?, Allow_Ads=? WHERE ID=?");
                    $stmt->execute(array($name, $description, $ordering, $parent, $visibility, $commenting, $ads,$id));

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

            echo " <h1 class=\"text-center title\">Delete Category</h1>";
            echo "<div class='container'>";


            $catid= isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0;
            $stmt = $con -> prepare("SELECT  * FROM categories WHERE  ID = ?");
            $stmt->execute(array($catid));


            $count = $stmt->rowCount();


            if($stmt->rowCount()>0) {
                $stmt = $con->prepare('DELETE FROM categories WHERE ID=:zuser');
                $stmt ->bindParam(":zuser",$catid);

                $stmt->execute();
                $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . " Record Deleted</div>";
                redirectHome($theMsg);
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

