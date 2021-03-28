<?php
session_start();
require_once("../inc/dbConnect.php");
$pdo = connectDb();
require_once("../inc/verifyLogin.php");
verifyAdmin();

$editRoom;

$mistnostId = (int) ($_GET["mistnostId"] ?? -1);

$stmt = $pdo->query('SELECT * FROM room WHERE `room_id`="'.$mistnostId.'"');
if ($stmt->rowCount() == 0){
	http_response_code (400);
}
else{
	while ($row = $stmt->fetch()) {
		$editRoom = $row;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Upravit místnost</title>
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
<h1>Upravení místnosti</h1><br>
<form action="/room/edit.php?mistnostId=<?php 
echo $_GET["mistnostId"];
?>" method="POST">
<div class="form-group">
	<label for="no">Číslo:</label><br>
	<input type="number" id="no" class="form-control" name="no" value="<?php
		echo $editRoom["no"];
		?>"><br>
</div>
<div class="form-group">
	<label for="name">Název:</label><br>
	<input type="text" id="name" class="form-control" name="name" value="<?php
		echo $editRoom["name"];
		?>"><br>
</div>
<div class="form-group">
	<label for="phone">Telefon:</label><br>
	<input type="number" id="phone" class="form-control" name="phone" value="<?php
		echo $editRoom["phone"];
		?>"><br>
</div>
<a class='linkbutton' href='/room/list.php'><div class='buttonstyle btn btn-danger'>Zrušit</div></a>
<button type="submit" class="btn btn-success">Uložit</button>
</form>
<?php
if(count($_POST) != 0){
	if($_POST["no"] != "" && $_POST["name"] != "" && $_POST["phone"] != ""){
		$stmt = $pdo->query('UPDATE room SET no="'.$_POST["no"].'" WHERE `room_id`="'.$mistnostId.'"');
		$stmt = $pdo->query('UPDATE room SET name="'.$_POST["name"].'" WHERE `room_id`="'.$mistnostId.'"');
		$stmt = $pdo->query('UPDATE room SET phone="'.$_POST["phone"].'" WHERE `room_id`="'.$mistnostId.'"');
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