<?php
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            确认
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="loginTip">
            <?php
                if(!$_SESSION['logged']){
                    redirect("login.php","你尚未登录，即将跳转至登录页面");
                    die();
                }else if(mysqli_connect_errno()) {
                    die("<h1>连接数据库失败，请联系管理员</h1>" . $connection->connect_error);
                }else{
                    $sql = "SELECT * FROM user WHERE email=\"".$_GET['email']."\"";
                    $result = mysqli_query($connection,$sql);
                    if($row = mysqli_fetch_array($result)){
                        if($row['checked']==true){
                            die("<h1>该账号已经被确认</h1>");
                        }else if($row['passport']!=$_GET['passport']){
                            die("<h1>密钥有误</h1>");
                        }else if(!$_SESSION['isadmin']){
                            die("<h1>你不是管理员, ".$_SESSION['email']."</h1>");
                        }
                        $sql = "UPDATE user SET checked=true WHERE email=\"".$_GET['email']."\"";
                        if(mysqli_query($connection,$sql)){
                            $title = "你的账号已经被确认";
                            $body = "你的账号被管理员确认，by ".$_SESSION['email'];
                            sendemail($_GET['email'],$title,$body);
                            die("<h1>成功执行操作</h1>");
                        }else{
                            die("<h1>操作失败，请重试</h1>");
                        }
                    }else{
                        die("<h1>无法找到拥有该邮箱的用户 ".$_GET['email']."</h1>");
                    }
                }
            ?>
        </div>
    </body>
</html>