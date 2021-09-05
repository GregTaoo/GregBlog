<?php 
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            登录
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="loginTip">
            <?php
                if($_SESSION['logged']){
                    $msg = "<h1>你已经登录！你3秒后会被重定向至起始页</h1>";
                    redirect("index.php",$msg);
                }else if ($conn->connect_error) {
                    die("连接数据库失败，请联系管理员");
                }else if($_GET['step']==1){
                    $sql = "SELECT username,password,isadmin FROM user WHERE email=\"".$_POST['email']."\"";
                    $result = mysqli_query($connection,$sql);
                    $row = mysqli_fetch_array($result);
                    if(!strcmp($row['password'],$_POST['password'])&&strlen($_POST['email'])>0&&strlen($_POST['password'])>0){
                        $_SESSION['email']=$_POST['email'];
                        $_SESSION['logged']=true;
                        $_SESSION['isadmin']=$row['isadmin'];
                        $msg = "成功登录，正在重定向";
                        redirect(strlen($_GET['from'])>0?$_GET['from']:"index.php",$msg);
                    }else{
                        echo "<h1>密码错误或账号不存在</h1>";
                    }
                }
            ?>
            <h1>登录</h1>
            <form action="login.php?step=1" method="post">
                <?php echo "邮箱：" ?><input type="email" name="email" class="text"><br>
                <?php echo "密码：" ?><input type="password" name="password" class="text"><br>
                <input type="submit" value="确认" class="button">
            </form>
            <a href="register.php">没有账号？注册</a>
        </div>
    </body>
</html>