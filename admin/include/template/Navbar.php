 <nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#app" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
             <a class="navbar-brand" href="dashboard.php"><?php echo lang("HOME_ADMIN")?></a>
        </div>
            <div class="collapse navbar-collapse" id="app">
                <ul class="nav navbar-nav">
                    <li><a href="categories.php" class="active"><?php echo lang("Categories")?></a></li>
                    <li><a href="items.php"><?php echo lang("Items")?></a></li>
                    <li><a href="members.php"><?php echo lang("Members")?></a></li>
                    <li><a href="comments.php"><?php echo lang("Comments")?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../index.php">Visit Shob</a></li>
                            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
                            <li><a href="#">Setting</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
    </div>
</nav>
