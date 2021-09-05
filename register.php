<?php
    require_once('include.php');
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            注册
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="loginTip">
            <?php
                if(mysqli_connect_errno()) {
                    die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
                }else if($_GET['step']==1){
                    if($_POST['password']!=$_POST['repassword']||strlen($_POST['email'])<=1||strlen($_POST['username'])<=0){
                        redirect("register.php","<h1>密码重复错误，请重新注册</h1>");
                        die();
                    }
                    if(strlen($_POST['password'])<6){
                        die("<h1>密码长度小于6!</h1>");
                    }
                    $sql = "SELECT checked FROM user WHERE email=\"".$_POST['email']."\"";
                    $result = mysqli_query($connection,$sql);
                    while($row = mysqli_fetch_array($result)){
                        if($row['checked']){
                            die("<h1>这个邮箱已经被注册了</h1>");
                        }
                    }
                    $sql = "DELETE FROM user WHERE checked=0";
                    mysqli_query($connection,$sql);
                    $passport= md5(uniqid());
                    $sql = "INSERT INTO user (email,username,password,checked,isadmin,passport) VALUES (\"".$_POST['email']."\", \"".$_POST['username']."\", \"".$_POST['password']."\",false,false,\"".$passport."\")";
                    if(mysqli_query($connection,$sql)){
                        echo "<h1>注册成功，请等待管理员确认</h1><br>";
                        sendRegMsg($_POST['email'],$passport);
                        redirect("login.php","重定向至登录页....");
                    }else{
                        die("<h1>创建账号失败，请重试</h1>");
                    }
                }
            ?>
            <h1>注册</h1>
            <form action="register.php?step=1" method="post">
                邮&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp箱：<input type="email" name="email" class="text"><br>
                密&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp码：<input type="password" name="password" class="text"><br>
                重复密码：<input type="password" name="repassword" class="text"><br>
                用户名称：<input type="text" name="username" class="text"><br>
                <input type="submit" value="确认" class="button">
            </form>
            <a href="login.php">已有账号？登录</a>
        </div>
    </body>
</html>