<?php
require_once("../inc/dbConnect.php");
$pdo = connectDb();

session_start();
require_once("../inc/verifyLogin.php");
verifyAdmin();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Vytvořit zaměstnance</title>
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
<h1>Vytvoření zaměstnance</h1><br>
<form action="/employee/create.php" method="POST">
<div class="form-group">
	<label for="username">Uživatelské jméno:</label><br>
	<input type="text" id="username" class="form-control" name="username"><br>
</div>
<div class="form-group">
	<label for="password">Heslo:</label><br>
	<input type="password" id="password" class="form-control" name="password"><br>
</div>
<div class="form-check">
	<label for="admin">Admin</label>
	<input type="checkbox" value="1" id="admin" class="form-check-input" name="admin"><br><br>
</div>
<div class="form-group">
	<label for="name">Jméno:</label><br>
	<input type="text" id="name" class="form-control" name="name"><br>
</div>
<div class="form-group">
	<label for="surname">Příjmení:</label><br>
	<input type="text" id="surname" class="form-control" name="surname"><br>
</div>
<div class="form-group">
	<label for="job">Zaměstnání:</label><br>
	<input type="text" id="job" class="form-control" name="job"><br>
</div>
<div class="form-group">
	<label for="wage">Plat:</label><br>
	<input type="number" id="wage" class="form-control" name="wage"><br>
</div>
<div class="form-group">
	<label for="rooms">Místnost:</label><br>
	<select id="rooms" class="form-control" name="rooms">

	<?php
	$stmt = $pdo->query('SELECT * FROM room ORDER BY name');
	if ($stmt->rowCount() == 0)
	{
		echo "Záznam neobsahuje žádná data";
	}
	else
	{
		$html = "";
		while ($row = $stmt->fetch()) {
			$html .= '<option value="'.$row["room_id"].'">'.$row["name"].'</option>';
		}
		echo $html;
	}
	unset($stmt);
	?>
	</select>
</div>
<br>
<div class="form-check">
	<label>Klíče:</label><br>
	<?php
	$stmt = $pdo->query('SELECT * FROM room ORDER BY name');
	if ($stmt->rowCount() == 0)
	{
		echo "Záznam neobsahuje žádná data";
	}
	else
	{
		$html = "";
		while ($row = $stmt->fetch()) {
			$html .= '<label>'.$row["name"].'<input type="checkbox" class="form-check-input" name="klice[]" value="'.$row["room_id"].'"></label><br>';
		}
		echo $html;
	}
	unset($stmt);
	?>
</div>
<a class='linkbutton' href='/employee/list.php'><div class='buttonstyle btn btn-danger'>Zrušit</div></a>
<button type="submit" class="btn btn-success">Vytvořit</button>
</form>
<?php
if(count($_POST) != 0){
	if($_POST["name"] != "" && $_POST["surname"] != "" && $_POST["job"] != "" && $_POST["wage"] != "" && $_POST["username"] != "" && $_POST["password"] != ""){
		$admin = $_POST["admin"] ?? 0;
		$pdo->query('INSERT INTO `employee`(`name`, `surname`, `job`, `wage`, `room`, `login`, `password`, `admin`) VALUES ("'.$_POST["name"].'", "'.$_POST["surname"].'", "'.$_POST["job"].'", "'.$_POST["wage"].'","'.$_POST["rooms"].'", "'.$_POST["username"].'", "'.password_hash($_POST["password"], PASSWORD_BCRYPT).'", "'.$admin.'")');

		$stmt = $pdo->query('SELECT * from employee ORDER BY employee_id DESC LIMIT 1');
		$zamestnanecId = $stmt->fetch();
		unset($stmt);

		foreach ($_POST["klice"] as $roomIDklic){ 
			$pdo->query('INSERT INTO `key` (`employee`, `room`) VALUES ("'.array_values($zamestnanecId)[0].'", "'.$roomIDklic.'")');
		}
		header("Location: /employee/list.php");
		die();
	}
	else{
		echo "<p class='error'>Špatně vyplněné nebo chybějící položky</p>";
	}
}
?>
</body>
</html>