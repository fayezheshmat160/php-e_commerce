<?php
    ob_start();

    session_start();

    if(isset($_SESSION["Username"])){
        $pageTitle = "Dashboart";
       include "init.php";

        $latest = 5;
        $theLatest = getLatest("*","users","UserID",$latest);

        $numItems=6;
        $latestItems=getLatest("*", "items", "Item_ID",$numItems);

        $numComments=4;



        ?>
        <div class="home-stats">
            <div class="container text-center">
                <h1>Dashboart</h1>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="stat st-members">
                            <i class="fa fa-users"></i>
                           <div class="info">
                               Total Members
                               <span><a href='members.php'><?php echo countItems('UserID','users')?></a></span>
                           </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat st-pending">
                            <i class="fa fa-plus"></i>
                           <div class="info">
                               Pending Members
                               <span><a href="members.php?do=Manage&page=pending"><?php echo checkItem('RegesterStutas', 'users','0')?></a></span>
                           </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat st-item">
                          <i class="fa fa-tag"></i>
                            <div class="info">
                                Total Item
                                <span><a href='items.php'><?php echo countItems('Item_ID','items')?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat st-comments">
                            <i class="fa fa-comments"></i>
                            <div class="info">
                                Total Comments
                                <span><a href='comments.php'><?php echo countItems('C_ID','comments')?></a></span>                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="latest">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Letest  <?php echo $latest?>Registerd Users
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php
                                if(!empty($latestItems)) {
                                    foreach ($theLatest as $user) {
                                        echo '<li>';
                                        echo $user['UserName'];
                                        echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                        echo '<span class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit"></i>  Edit';
                                        if ($user['RegesterStutas'] == 0) {
                                            echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' class='btn btn-info act pull-right activate'><i class='fa fa-check'></i> Activate</a>";

                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo "</li>";
                                    }
                                }else{
                                    echo "There Is No Record Show";
                                }
                                ?>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Letest <?php echo $numItems; ?>Items
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                        if(!empty($latestItems)) {
                                        foreach($latestItems as $item) {
                                            echo '<li>';
                                            echo $item['Name'];
                                            echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                            echo '<i class="fa fa-edit"></i>  Edit';
                                            if ($item['Approve'] == 0) {
                                                echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info act pull-right activate'><i class='fa fa-check'></i> Approve</a>";

                                            }
                                            echo '</span>';
                                            echo '</a>';
                                            echo "</li>";
                                        }
                                        }else{
                                            echo "There Is No Record Show";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start row-->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i>
                                Letest  <?php  echo $numComments; ?>Comments
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                               <?php
                                   $stmt=$con->prepare("SELECT comments.*,users.UserName FROM comments INNER JOIN  users ON users.UserID=comments.User_ID ORDER BY C_ID DESC LIMIT $numComments");
                                   $stmt->execute();
                                   $comments= $stmt-> fetchAll();
                                   if(!empty($comments)) {
                                       foreach ($comments as $comment) {
                                           echo '<div class="comment-box">';
                                           echo '<span class="member-n">' . $comment['UserName'] . '</span>';
                                           echo '<p class="member-c">' . $comment['Comment'] . '</p>';

                                           echo '</div>';
                                       }
                                   }else{
                                       echo "There Is No Record Show";
                                   }
                               ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row-->
            </div>
        </div>
        <?php
       include $tpl . "footer.php";
    }
    else{
        header("Location: index.php");
        exit();
    }
        ob_end_flush();
    ?>