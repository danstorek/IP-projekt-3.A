<?php
require_once("../inc/dbConnect.php");
session_start();
require_once("../inc/verifyLogin.php");
verifyAdmin();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Vytvořit místnost</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
form{
	width: 400px;
	margin: auto;
}
h1{
	text-align: center;
}
p{
	text-align: center;
	font-size: 20px;
}
.error{
	color: red;
}
</style>

</head>
<body>
<h1>Vytvořit místnost</h1><br>
<form action="/room/create.php" method="POST">
<div class="form-group">
	<label for="no">Číslo:</label><br>
	<input type="number" id="no" class="form-control" name="no"><br>
</div>
<div class="form-group">
	<label for="name">Název:</label><br>
	<input type="text" id="name" class="form-control" name="name"><br>
</div>
<div class="form-group">
	<label for="phone">Telefon:</label><br>
	<input type="number" id="phone" class="form-control" name="phone"><br>
</div>
<a class='linkbutton' href='/room/list.php'><div class='buttonstyle btn btn-danger'>Zrušit</div></a>
<button type="submit" class="btn btn-success">Vytvořit</button>
</form>
<?php
if(count($_POST) != 0){
	$pdo = connectDb();
	
	if($_POST["no"] != "" && $_POST["name"] != "" && $_POST["phone"] != ""){
		$pdo->query('INSERT INTO `room`(`no`, `name`, `phone`) VALUES ("'.$_POST["no"].'", "'.$_POST["name"].'", "'.$_POST["phone"].'")');
		
		header("Location: /room/list.php");
		die();
	}
	else{
		echo "<p class='error'>Špatně vyplněné nebo chybějící položky</p>";
	}
}
?>
</body>
</html>