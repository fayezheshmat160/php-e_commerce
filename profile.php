<?php
    ob_start();
    session_start();
    $pageTitle="Profile";
    include "init.php";
    if(isset($_SESSION['user'])){

        $getUser=$con ->prepare("SELECT * FROM users WHERE  UserName=?");
        $getUser->execute(array($sessionUser));
        $info=$getUser->fetch();
?>

<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Name</span> : <?php echo $info['UserName'] ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span> : <?php echo $info['Email'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span> : <?php echo $info['FullName'] ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span> : <?php echo $info['Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favourite Category</span> :
                    </li>
                </ul>
                <a href="#" class="btn btn-default my-button">Edit Information</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Item</div>
            <div class="panel-body">
                <?php
                    if (! empty(getItems('Member_ID',$info['UserID']))){
                        echo "<div class='row'>";
                        foreach (getItems('Member_ID',$info['UserID'] , 1) as $item){
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                    if($item['Approve']==0){
                                        echo "<span class='approve-status'>Watting Approve</span>";
                                    }
                                    echo '<span class="price-tag">$'.$item['Price'].'</span>';
                                    echo '<img class="img-responsive" src="img.jpg" alt="">';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="item.php?itemid='.$item['Item_ID'].'">' .$item['Name'].'</a></h3>';
                                        echo '<p>' . $item['Description'].'</p>';
                                        echo '<div class="date">' . $item['Add_Date'].'</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                        echo "</div>";
                    }else{
                        echo 'Sorry There No Ads To Show , Create <a href="newad.php">New Ad</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="my-comment block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Leatest</div>
            <div class="panel-body">
                <?php
                    $stmt=$con->prepare("SELECT Comment FROM comments WHERE  User_ID=?");
                    $stmt->execute(array($info['UserID']));
                    $rows= $stmt-> fetchAll();
                    if(!empty($rows)){
                        foreach ($rows as $row){
                            echo '<p>' . $row['Comment'] . '</p>';
                        }

                    }else{
                        echo 'There Is No Comments To Show';
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
