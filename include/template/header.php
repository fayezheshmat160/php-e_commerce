<DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title> <?php echo getTitle(); ?></title>
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
            <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">-->
          <!--  <link rel="stylesheet" href="layout/css/bootstrap.min.css">-->
            <link rel="stylesheet" href="layout/css/frontend.css">

        </head>
        <body>
            <div class="upper-bar">
                <div class="container">
                    <?php
                        if(isset($_SESSION["user"])){?>
                            <img class="my-image img-thumbnall img-circle" src="img.jpg">
                                
                                <span class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                    <?php echo "$sessionUser";?>
                                    <span class="caret"></span>
                                </span>
                                <ul class="dropdown-menu">
                                    <li><a href="profile.php">My Profile</a></li>
                                    <li><a href="newad.php">New Item</a></li>
                                    <li><a href="profile.php#my-ads">My Item</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            <?php
                    }else{
                    ?>
                    <a href="login.php">
                        <span class="pull-right">Login/Signup</span>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <nav class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-header">
                        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#app" aria-expanded="false">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">Home Page</a>
                    </div>
                    <div class="collapse navbar-collapse" id="app">
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                                    $allFrom=getAllFrom('*', 'categories', 'WHERE Parent = 0','', 'ID', 'ASC');
                                    foreach ($allFrom as $cat){
                                        echo
                                         '<li>
                                                <a href="categories.php?pageid='.$cat['ID'].'">
                                                '.$cat['Name'].'
                                                </a>
                                          </li>';
                                    }
                            ?>

                        </ul>
                    </div>
                </div>
            </nav>

