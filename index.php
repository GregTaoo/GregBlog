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
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="logintip">
            <?php
                if(mysqli_connect_errno()) {
                    die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
                }
                echo "Hello! ".$_SESSION['email']."</h1><br>";
                $sql = "SELECT checked FROM user WHERE email=\"".$_SESSION['email']."\"";
                $row = mysqli_fetch_array(mysqli_query($connection,$sql));
                if(!$row['checked']&&$_SESSION['logged']){
                    echo "<a href=\"quit.php\">登出</a><br>";
                    die("<h1>你的账号还未被管理员确认</h1>");
                }
                $sql = "SELECT * FROM data WHERE 1";
                $result = mysqli_query($connection,$sql);
                if($row = mysqli_fetch_array($result)){
                    $blognum = $row['blognum'];
                }else{
                    die("获取博客数据失败");
                }
                $sql = "SELECT * FROM blog WHERE id>=".($blognum-20);
                $result = mysqli_query($connection,$sql);
                echo "<h1>博客推荐</h1><table>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                    echo "<th><a href=\"showblog.php?blogid=".$row['id']."\" style=\"font-size:20px\">".$row['title']."</a></th>";
                    echo "<th style=\"font-size:5px\">".substr($row['text'],0,100)."</th>";
                    echo "<th style=\"font-size:8px\">".$row['time']."<br>".$row['owner']."</th>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div><br>
    </body>
</html>