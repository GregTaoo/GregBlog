<?php
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            blogactions
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']); ?>
        <div class="blogtip">
            <?php
                if(!$_SESSION['logged']){
                    redirect("login.php","你还未登录！");
                    die();
                }else if(mysqli_connect_errno()) {
                    die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
                }
                echo "Hello! ".$_SESSION['email']."</h1><br>";
                $sql = "SELECT checked FROM user WHERE email=\"".$_SESSION['email']."\"";
                $row = mysqli_fetch_array(mysqli_query($connection,$sql));
                if(!$row['checked']&&$_SESSION['logged']){
                    echo "<a href=\"quit.php\">登出</a><br>";
                    die("<h1>你的账号还未被管理员确认</h1>");
                }
                $sql = "SELECT * FROM blog WHERE id=".$_GET['blogid'];
                if($row = mysqli_fetch_array(mysqli_query($connection,$sql))){
                    if(!strcmp($row['owner'],$_SESSION['email'])||$_SESSION['isadmin']){
                        if(!strcmp($_GET['action'],"deleteblog")){
                            $sql = "DELETE FROM blog WHERE id=".$_GET['blogid'];
                            if(mysqli_query($connection,$sql)&&unlink("./blogs/id".$_GET['blogid'].".md")){
                                die("<h1>操作成功</h1>");
                            }else{
                                die("<h1>操作失败</h1>");
                            }
                        }else if(!strcmp($_GET['action'],"editblog")){
                            if($_GET['step']==1){
                                $sql = "UPDATE blog SET title=\"".$_POST['title']."\",text=\"".substr($_POST['text'],0,80)."\" WHERE id=".$_GET['blogid'];
                                $fp = fopen("./blogs/id".$_GET['blogid'].".md", "w");
                                $flag = fwrite($fp,$_POST['text']);
                                if(!$flag){
                                    die("更新数据失败！errid=3");
                                }
                                fclose($fp);
                                if(mysqli_query($connection,$sql)){
                                    die("<h1>修改成功</h1>");
                                }else{
                                    die("<h1>修改失败</h1>");
                                }
                            }
                            $sql = "SELECT * FROM blog WHERE id=".$_GET['blogid'];
                            $result = mysqli_query($connection,$sql);
                            if($row = mysqli_fetch_array($result)){
                                $file = "./blogs/id".$_GET['blogid'].".md";
                                if(!file_exists($file)){
                                    die("找不到文件");
                                }
                                $fp = fopen($file, "r");
                                while(!feof($fp)){
                                    $text = $text.fgets($fp)."\n";
                                }
                                fclose($fp);
                            ?>
                            <form action="blogactions.php?action=editblog&step=1&blogid=<?php echo $_GET['blogid']; ?>" method="post">
                                标题：<input type="text" name="title" class="text" value="<?php echo $row['title']; ?>"><br>
                                <link rel="stylesheet" href="editormd/css/editormd.css" />
                                <div id="test-editor">
                                    <textarea style="display:none;" name="text"><?php echo $text; ?></textarea>
                                </div>
                                <script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
                                <script src="editormd/editormd.min.js"></script>
                                <script type="text/javascript">
                                    $(function() {
                                        var editor = editormd("test-editor", {
                                            width  : "80%",
                                            height : "58%",
                                            path: "editormd/lib/"
                                        });
                                    });
                                </script>
                                <input type="submit" value="确认" class="button">
                            </form>
                            <?php
                            }else{
                                die("<h1>获取数据失败</h1>");
                            }
                        }
                    }else{
                        die("<h1>你不是本人或管理员</h1>");
                    }
                }else{
                    die("<h1>获取数据失败</h1>");
                }
            ?>
        </div><br>
    </body>
</html>