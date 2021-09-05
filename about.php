<html>
<head>
<meta charset="utf-8"> 
<title>about</title> 
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
<h1>About 关于本站</h1>
<p>Copyright(c) 2021</p>
<p>解释权完全归GregTao所有</p>
</div>

</body>
</html>