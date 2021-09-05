<?php
    require_once('include.php');
    session_start();
    $connection = mysqli_connect($root_name,$username,$password,$blanks);
?>
<html>
    <head>
        <title>
            AWARD - blog
        </title>
        <link rel="stylesheet" href="./codehighlight/styles/tomorrow-night-bright.min.css">
        <script src="./codehighlight/highlight.min.js"></script>
        <script>
            hljs.initHighlightingOnLoad();
        </script>
        <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width,initial-scale=0.8">
    </head>
    <body>
        <?php displayMenu($_SESSION['logged']);
			if(mysqli_connect_errno()) {
                die("<h1>连接数据库失败! 请联系管理员</h1>" . $connection->connect_error);
            }
			if(strlen($_GET['blogid'])>6){
				die("id过长！");
			}
			$sql = "SELECT * FROM blog WHERE id='".$_GET['blogid']."'";
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
				showBlog($row['owner'],$row['title'],$row['time'],$text,$_GET['blogid']);
			}else{
				die("该博客不存在!");
			}
		?>
    </body>
</html>