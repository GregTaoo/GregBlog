<?php
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            账户设置
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="loginTip">
            <?php
                if($_GET['action']=="forgetpassword"){
                    die("<h1>正在开发ing....</h1>");
                }
                if(!$_SESSION['logged']){
                    redirect("login.php","你尚未登录，即将跳转至登录页面");
                    die();
                }else if (mysqli_connect_errno()) {
                    die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
                }
                echo "Hello! ".$_SESSION['email']."</h1><br>";
                $sql = "SELECT checked FROM user WHERE email=\"".$_SESSION['email']."\"";
                $row = mysqli_fetch_array(mysqli_query($connection,$sql));
                if(!$row['checked']){
                    echo "<a href=\"quit.php\">登出</a><br>";
                    die("<h1>你的账号还未被管理员确认</h1>");
                }else if($_GET['action']=="changepassword"){
                    if($_GET['step']==0){
            ?>
            <form action="userset.php?action=changepassword&step=1" method="post">
                <?php echo "原密码&nbsp&nbsp&nbsp:" ?><input type="password" name="password" class="text"><br>
                <?php echo "新密码&nbsp&nbsp&nbsp:" ?><input type="password" name="newpassword" class="text"><br>
                <?php echo "重复密码:" ?><input type="password" name="repassword" class="text"><br>
                <input type="submit" value="确认" class="button">
            </form>
            <?php 
                    }else if($_GET['step']==1){
                        if($_POST['newpassword']!=$_POST['repassword']){
                            die("<h1>输入的新密码与重复的密码不符，请重新提交</h1>");
                        }
                        $sql = "SELECT password FROM user WHERE email=\"".$_SESSION['email']."\"";
                        $result = mysqli_query($connection,$sql);
                        if($row = mysqli_fetch_array($result)){
                            if($_POST['password']!=$row['password']){
                                die("<h1>原密码错误，请重试</h1>");
                            }
                            $sql = "UPDATE user SET password=\"".$_POST['newpassword']."\" WHERE email=\"".$_SESSION['email']."\"";
                            if(mysqli_query($connection,$sql)){
                                session_destroy();
                                die("<h1>修改密码成功，请重新登录</h1>");
                            }else{
                                die("<h1>操作失败，请重试</h1>");
                            }
                        }else{
                            die("<h1>找不到该账户或您尚未登录</h1>");
                        }
                    }
                }
            ?>
            <br>
        </div>
    </body>
</html>