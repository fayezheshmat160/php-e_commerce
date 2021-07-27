<?php
    ob_start();
    session_start();
    $pageTitle="Login";
    if(isset($_SESSION["user"])){
        header("Location: index.php");
    }
    include 'init.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['login'])) {
            $user = $_POST["username"];
            $pass = $_POST["password"];

            $hashedPass = sha1($pass);


            $stmt = $con->prepare("SELECT UserID, UserName, Password FROM users WHERE UserName = ? AND Password = ?");
            $stmt->execute(array($user, $hashedPass));

            $get=$stmt->fetch();

            $count = $stmt->rowCount();

            if ($count > 0) {
                $_SESSION["user"] = $user;

                $_SESSION['uid']=$get['UserID'];

                header("Location: index.php");

                exit();
            }
        }else{
            $formErrors=array();

            $username = $_POST["username"];
            $password = $_POST["password"];
            $password2 = $_POST["password2"];
            $email=$_POST['email'];


            if (isset($username)){

                $filterUser=filter_var($username,FILTER_SANITIZE_STRING);
                if(strlen($filterUser)<4){
                    $formErrors[]='Username Must Be Larger Than 4 Characters';
                }
            }
            if (isset($password) && isset($password2)){

                if(empty($_POST['password'])){

                    $formErrors[]='Sorry Password Can Be Empty';
                }

                if(sha1($password) !==  sha1($password2)){
                    $formErrors[]='Sorry Password Is Not Match';
                }
            }
            if (isset($email)){
                $filterUser=filter_var($email,FILTER_SANITIZE_EMAIL);

                if(filter_var($filterUser,FILTER_SANITIZE_EMAIL) != true){
                    $formErrors[]='This Email Is Not Valid';
                }
            }


            if(empty($formErrors)) {

                $check = checkItem("UserName", "users", $username);

                if ($check == 1) {

                    $formErrors[]='Sorry The Username Is Exist';

                } else {


                    $stmt = $con->prepare("INSERT INTO users(UserName, Password, Email, RegesterStutas,Date) VALUES (:zuser, :zpass, :zmail, 0,now())");
                    $stmt->execute(array(

                        'zuser' => $username,
                        'zpass' => sha1($password),
                        'zmail' => $email
                    ));
                    $successMsg='Congrats You Are Now Registered Username';
                }
            }
        }
    }
?>
    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span> </h1>
        <form class="login" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <div class="login-container">
                <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required>
            </div>
            <div class="login-container">
                 <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your User Password" required>
            </div>
                 <input class="btn btn-primary form-control" name='login'type="submit" value="Login">
        </form>


        <form class="signup" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <div class="login-container">
                 <input pattern=".{4,}" title="Username Must Be 4 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required>
            </div>
            <div class="login-container">
                 <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your User Password" required>
            </div>
            <div class="login-container">
                 <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a Password agine" required>
            </div>
            <div class="login-container">
                <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Type Your Email" required>
            </div>
                <input class="btn btn-success form-control btn-block" name="signup"type="submit" value="SignUp">
        </form>
        <div class="the-error text-center">
            <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error){
                        echo '<div class="msg error">'.$error . '</dic>';
                    }
                }
                if(isset($successMsg)){
                   echo  '<div class="msg success">' .$successMsg .'</div>';
                }
            ?>
        </div>
    </div>
<?php
    include $tpl . "footer.php";
    ob_end_flush();
?>