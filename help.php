<html>
<head>
<meta charset="utf-8"> 
<title>help</title> 
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width,initial-scale=0.8">
</head>
<body>

<?php 
    session_start();
    include_once 'include.php';
    displayMenu($_SESSION['logged']);
?>

<div class="help">
<h1>帮助文档</h1>
<p>请咨询管理员</p>
</div>

</body>
</html>