<?php
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            AWARD - 主页
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']); ?>
        <div style="text-align: center;">
            <div class="help"> 
                <?php
                    displaySideMenu(); 
                    if(mysqli_connect_errno()) {
                        die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
                    }else if(!$_SESSION['logged']){
                        redirect("login.php","你尚未登录！");
                        die();
                    }
                    $sql = "SELECT checked FROM user WHERE email=\"".$_SESSION['email']."\"";
                    $row = mysqli_fetch_array(mysqli_query($connection,$sql));
                    if(!$row['checked']&&$_SESSION['logged']){
                        die("<h1>你的账号还未被管理员确认</h1>");
                    }
                    $sql = "SELECT title,time,id FROM blog WHERE owner=\"".$_SESSION['email']."\"";
                    $result = mysqli_query($connection,$sql);
                    echo "<h1>博客列表</h1><table border=1>";
                    echo "<tr>";
                    echo "<th>ID</th><th>TITLE</th><th>TIME</th>";
                    echo "</tr>";
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<th>".$row['id']."</th>";
                        echo "<th><a href=\"showblog.php?blogid=".$row['id']."\">".$row['title']."</a></th>";
                        echo "<th>".$row['time']."</th>";
                        echo "</tr>";
                    }
                    echo "</table>";
                ?>
            </div>
        </div><br>
    </body>
</html>