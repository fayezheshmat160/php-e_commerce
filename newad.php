<?php
    ob_start();
    session_start();
    $pageTitle="Create New Item";
    include "init.php";
    if(isset($_SESSION['user'])){
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            $formErrors =array();

            $name       =filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $desc       =filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      =filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $country    =filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     =filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   =filter_var($_POST['cat'],FILTER_SANITIZE_NUMBER_INT);
            $tags       =filter_var($_POST['tags'],FILTER_SANITIZE_STRING);


            if(strlen($name)<4){
                $formErrors[]= 'Item Title Must Be Least 4 Characters';
            }
            if(strlen($desc)<10){
                $formErrors[]= 'Item Description Must Be Least 10 Characters';
            }
            if(empty($price)){
                $formErrors[]= 'The Price Must Be Not Empty';
            }
            if(strlen($country)<2){
                $formErrors[]= 'The Title Must Be Least 2 Characters';
            }
            if(empty($status)){
                $formErrors[]= 'The Status Must Be Not Empty';
            }
            if(empty($category)){
                $formErrors[]= 'The Category Must Be Not Empty';
            }

             if(empty($formErrors)) {

                    $stmt = $con->prepare("INSERT INTO items( Name , Description, Price, Country_Made, Status,Add_Date,Cat_ID ,Member_ID, Tags)
                                                    VALUES (:zname, :zdec, :zprice, :zcont , :zstat,now(),:zcat,:zmember,:ztags)");
                    $stmt->execute(array(

                        'zname'     => $name,
                        'zdec'      => $desc,
                        'zprice'    => $price,
                        'zcont'     => $country,
                        'zstat'     => $status,
                        'zcat'      => $category,
                        'zmember'   => $_SESSION['uid'],
                        'ztags'     => $tags

                    ));
               if($stmt){
                   $successMsg = 'Item Has Been Add';
               }
             }
        }
    ?>

<h1 class="text-center">Create New Item</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Item</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-lg-9">
                                    <input pattern=".{4,}" title="This Field Require At Least 4 Characters" type="text" name="name" class="form-control live-name" required placeholder="Name Of The Item">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-lg-9">
                                    <input pattern=".{10,}" title="This Field Require At Least 10 Characters" type="text" name="description" class="form-control live-description" required placeholder="Description Of The Item">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-lg-9">
                                    <input type="text" name="price" class="form-control live-price" required placeholder="Price Of The Item">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-10 col-lg-9">
                                    <input type="text" name="country" class="form-control" required placeholder="Country Of The Made">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-10 col-lg-9">
                                    <select name="status" class="form-control" required>
                                        <option value="0">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Very Old</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-lg-9">
                                    <select name="cat" class="form-control" required>
                                        <option value="0">...</option>
                                        <?php
                                        $stmt2=$con->prepare("SELECT * FROM categories");
                                        $stmt2 ->execute();
                                        $cats=$stmt2->fetchAll();
                                        foreach($cats as $cat){
                                            echo  "<option value='" .$cat['ID']."'>" .$cat ['Name'] ."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-sm-10 col-lg-9">
                                    <input type="text" name="tags" class="form-control" placeholder="Tags Describr Your Ads">
                                </div>
                            </div>


                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="Add Item" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">$0</span>
                            <img class="img-responsive" src="img.jpg" alt="">
                            <div class="caption">
                                <h3>Name</h3>
                                <p>Description</p>
                             </div>
                        </div>
                    </div>
                </div>
                <?php

                    if(!empty($formErrors)){
                        foreach ($formErrors as $erorr){
                            echo '<div class="alert alert-danger">'.$erorr . '</div>';
                        }
                    }

                    if(isset($successMsg)){
                         echo  '<div class="alert alert-success">' .$successMsg .'</div>';
                        }


                ?>
            </div>
        </div>
    </div>
</div>

<?php
    }else{
        header('Location:login.php');
        exit();
    }
    include $tpl . "footer.php";
        ob_end_flush();
?>
