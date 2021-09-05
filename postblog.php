<?php 
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            发布你的博客
        </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']) ?>
        <div class="blogTip">
            <?php
                if(!$_SESSION['logged']){
                    $msg = "<h1>你未登录！你3秒后会被重定向至登录页</h1>";
                    redirect("login.php",$msg);
					die();
                }else if ($conn->connect_error) {
                    die("连接数据库失败，请联系管理员");
                }else if($_GET['step']==1){
                    if(strlen($_POST['title'])<=0||strlen($_POST['text'])<=0){
                        die("标题/内容不能为空");
                    }
                    $sql = "SELECT * FROM data WHERE 1";
                    $result = mysqli_query($connection,$sql);
                    if($row = mysqli_fetch_array($result)){
						$blognum = $row['blognum']+1;
						$datetime = new DateTime();
						$time = $datetime->format("Y/m/d H:i:s");
						$sql = "INSERT INTO `blog`(`id`, `title`, `owner`, `likes`, `time`, `text`) VALUES (".$blognum.",'".$_POST['title']."','".$_SESSION['email']."',0,'".$time."','".substr($_POST['text'],0,80)."')";
						if(!mysqli_query($connection,$sql)){
							die("更新数据失败！id=2");
						}else{
                            $fp = fopen("./blogs/id".$blognum.".md", "w");
                            $flag = fwrite($fp,$_POST['text']);
                            if(!$flag){
                                die("更新数据失败！id=3");
                            }
                            fclose($fp);
							$sql = "UPDATE data SET blognum=".$blognum." WHERE 1";
							if(!mysqli_query($connection,$sql)){
								die("更新数据失败！id=1");
							}
							redirect("showblog.php?blogid=".$blognum,"<h1>博客发布成功！正在跳转...</h1>");
							die();
						}
					}else{
						die("获取数据失败！");
					}
                }
            ?>
            <h1>发送你的博客(实际效果可能略有不同)</h1>
            <form action="postblog.php?step=1" method="post">
                标题：<input type="text" name="title" class="text"><br>
                <link rel="stylesheet" href="editormd/css/editormd.css" />
                <div id="test-editor">
                    <textarea style="display:none;" name="text"></textarea>
                </div>
                <script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
                <script src="./editormd/editormd.min.js"></script>
                <script type="text/javascript">
                    $(function() {
                        var editor = editormd("test-editor", {
                            width  : "80%",
                            height : "60%",
                            path: "editormd/lib/"
                        });
                    });
                </script>
                <input type="submit" value="确认" class="button">
            </form>
        </div>
    </body>
</html>