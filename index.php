

<?php
	ob_start();
    session_start();
    $pageTitle="HomePage";
    include "init.php";
?>

<div class="container">
    <h1 class="text-center">Show Category</h1>
    <div class="row">
    <?php
    	$allAds=getAllFrom('*', 'items', 'where Approve = 1', '', 'Item_ID');
        foreach ($allAds as $item){
            echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                    echo '<span class="price-tag">$'.$item['Price'].'</span>';
                    echo '<img class="img-responsive" src="img.jpg" alt="">';
                    echo '<div class="caption">';
                        echo '<h3><a href="item.php?itemid='.$item['Item_ID'].'">' .$item['Name'].'</a></h3>';
                        echo '<p>' . $item['Description'].'</p>';
                        echo '<div class="date">' . $item['Add_Date'] . '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

    ?>
    </div>
</div>





<?php 
	include $tpl . "footer.php"; 
	ob_end_flush();
?>
