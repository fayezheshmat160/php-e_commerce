<?php
    ob_start();
    session_start();
    $pageTitle="Profile";
    include "init.php";

        $itemid= isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        $stmt = $con -> prepare("SELECT items.* ,
                                            users.UserName AS user_name,categories.Name AS Category_Name FROM items
                                            INNER JOIN users ON users.UserID=items.Member_ID
                                            INNER JOIN categories ON categories.ID=items.Cat_ID WHERE  Item_ID = ? AND Approve = 1 ");
        $stmt->execute(array($itemid));

        $conut=$stmt->rowCount();

        if($conut>0){
              $item= $stmt->fetch();



      

?>

    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnall center-block"  src="img.jpg" alt="">
            </div>
            <div class="col-md-9 item-info">
                <h2> <?php echo $item['Name'] ?></h2>
                <p> <?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span> Added Date </span><?php echo $item['Add_Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span> Price</span>: <?php echo $item['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span> Made In</span>: <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span>:
                         <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Category_Name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span> Added By</span>: <a href="#"><?php echo $item['user_name'] ?></a>
                    </li>
                     <li class="tags-items">
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags</span>:
                        <?php 

                          $allTags=explode(',' , $item['Tags']);

                          foreach ($allTags as $tag) {
                            $tag=str_replace(' ', '', $tag);

                            $lowertag=strtolower($tag);
                            if(!empty($tag)){
                            
                              echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';  
                          }

                        }

                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if(isset($_SESSION['user'])){ ?>
        <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='.$item['Item_ID'] ?>" method="POST">
                        <textarea name="comment" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment">
                    </form>
                    <?php 
                      if($_SERVER['REQUEST_METHOD']=='POST'){
                            $comment =filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $itemid  =$item['Item_ID'];
                            $userid  =$_SESSION['uid'];
                           if (!empty($comment)){
                               $stmt=$con->prepare("INSERT INTO comments(Comment, Status , Comment_Date, Item_ID, User_ID)
                                                            VALUES  (:zcomment, 0, NOW(), :zitemid, :zuserid)");
                               $stmt->execute(array(
                                       'zcomment'=> $comment,
                                       'zitemid' => $itemid,
                                       'zuserid' => $userid
                               ));
                               if($stmt){
                                   echo '<div class="alert alert-success">Comment Added</div>';
                               }
                           }
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php } else{
            echo '<a href="login.php">Login</a> or <a href="login.php">Register </a> To Add Comment';
        }?>
        <hr class="custom-hr">
            <?php
                $stmt=$con->prepare("SELECT comments.*, users.UserName AS Member
                FROM comments  INNER JOIN  users ON users.UserID=comments.User_ID 
                WHERE Item_ID = ? AND Status = 1 ORDER BY C_ID DESC");
                $stmt->execute(array($item['Item_ID']));
                $comments= $stmt-> fetchAll();
                ?>

        <?php
            foreach ($comments as $comment){?>
                <div class="comment-box">
                     <div class="row">
                         <div class="col-sm-2" text-center>
                             <img class="img-responsive img-thumbnall img-circle center-block" src="img.jpg" alt="">
                             <?php echo $comment['Member'] ?>
                         </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo $comment['Comment'] ?></p>
                        </div>
                     </div>
                 </div>
                 <hr class="custom-hr">
          <?php  }

        ?>
<?php
    }else{
        echo '<div class="container">';

             echo '<div class="alert alert-danger">There Is No Such ID Or This Item Is Watting Approved</div>';
             
        echo "</div>";     
    }
    include $tpl . "footer.php";
    ob_end_flush();
?>
