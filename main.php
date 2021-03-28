<?php
session_start();
require_once("inc/verifyLogin.php");
verify();

?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prohlížeč DB - Daniel Štorek</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
h1,h2,h3{
text-align: center;
}
h2{
margin-bottom: 50px;
}
</style>
</head>
<body>
<h1>Vítejte v databázi!</h1>
<h2>Aktuálně přihlášený uživatel: <?php
echo $_SESSION["fullname"];
?></h2>
<a href="/employee/list.php"><h3>Seznam zaměstanců</h3></a>
<a href="/room/list.php"><h3>Seznam místností</h3></a>
<a href="/changePass.php"><h3>Změna hesla</h3></a>
<a href="/index.php"><h3>Odhlášení</h3></a>
</body>
</html>