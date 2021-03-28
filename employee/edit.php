<?php
require_once("../inc/dbConnect.php");
$pdo = connectDb();

session_start();
require_once("../inc/verifyLogin.php");
verifyAdmin();

$editUser;
$keyList = [];

$zamestnanecId = (int) ($_GET["zamestnanecId"] ?? -1);

$stmt = $pdo->query('SELECT * FROM employee WHERE `employee_id`="'.$zamestnanecId.'"');
if ($stmt->rowCount() == 0){
	http_response_code (400);
}
else{
	while ($row = $stmt->fetch()) {
		$editUser = $row;
	}
}

$stmt = $pdo->query('SELECT * FROM `key` WHERE `key`.`employee` = '.$zamestnanecId);
if($stmt->rowCount() > 0){
	while ($row = $stmt->fetch()) {
		$keyList[$row["room"]] = 1;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Upravit zaměstnance</title>
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
<h1>Upravení zaměstnance</h1><br>
<form action="/employee/edit.php?zamestnanecId=<?php 
echo $_GET["zamestnanecId"];
?>" method="POST">
<div class="form-group">
	<label for="name">Jméno:</label><br>
	<input type="text" id="name" class="form-control" name="name" value="<?php
	echo $editUser["name"];
	?>"><br>
</div>
<div class="form-group">
	<label for="surname">Příjmení:</label><br>
	<input type="text" id="surname" class="form-control" name="surname" value="<?php
	echo $editUser["surname"];
	?>"><br>
</div>
<div class="form-group">
	<label for="job">Zaměstnání:</label><br>
	<input type="text" id="job" class="form-control" name="job" value="<?php
	echo $editUser["job"];
	?>"><br>
</div>
<div class="form-group">
	<label for="wage">Plat:</label><br>
	<input type="number" id="wage" class="form-control" name="wage" value="<?php
	echo $editUser["wage"];
	?>"><br>
</div>
<div class="form-group">
	<label for="rooms">Místnost:</label><br>
	<select id="rooms" class="form-control" name="rooms">
	<?php
	$stmt = $pdo->query('SELECT * FROM room ORDER BY name');
	if ($stmt->rowCount() == 0)
	{
		echo '<option value="Nic"></option>';
	}
	else
	{
		$html = "";
		while ($row = $stmt->fetch()) {
			if($editUser["room"] == $row["room_id"]){
				$html .= '<option selected="selected" value="'.$row["room_id"].'">'.$row["name"].'</option>';
			}
			else{
				$html .= '<option value="'.$row["room_id"].'">'.$row["name"].'</option>';
			}
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
			if(array_key_exists($row["room_id"], $keyList)){
				$html .= '<label>'.$row["name"].'<input type="checkbox" checked class="form-check-input" name="klice[]" value="'.$row["room_id"].'"></label><br>';
			}
			else{
				$html .= '<label>'.$row["name"].'<input type="checkbox" class="form-check-input" name="klice[]" value="'.$row["room_id"].'"></label><br>';
			}
		}
		echo $html;
	}
	unset($stmt);
	?>
</div>
<a class='linkbutton' href='/employee/list.php'><div class='buttonstyle btn btn-danger'>Zrušit</div></a>
<button type="submit" class="btn btn-success">Uložit</button>
</form>
<?php
if(count($_POST) != 0){
	if($_POST["name"] != "" && $_POST["surname"] != "" && $_POST["job"] != "" && $_POST["wage"] != ""){
		$pdo->query('UPDATE employee SET name="'.filter_var($_POST["name"]).'" WHERE `employee_id`="'.$zamestnanecId.'"');
		$pdo->query('UPDATE employee SET surname="'.filter_var($_POST["surname"]).'" WHERE `employee_id`="'.$zamestnanecId.'"');
		$pdo->query('UPDATE employee SET job="'.filter_var($_POST["job"]).'" WHERE `employee_id`="'.$zamestnanecId.'"');
		$pdo->query('UPDATE employee SET wage="'.filter_var($_POST["wage"]).'" WHERE `employee_id`="'.$zamestnanecId.'"');
		$pdo->query('UPDATE employee SET room="'.$_POST["rooms"].'" WHERE `employee_id`="'.$zamestnanecId.'"');
		$pdo->query('DELETE FROM `key` WHERE `key`.`employee` = '.$_GET["zamestnanecId"]);
		foreach ($_POST["klice"] as $roomIDklic){ 
			$pdo->query('INSERT INTO `key` (`employee`, `room`) VALUES ("'.$zamestnanecId.'", "'.$roomIDklic.'")');
		}
		header("Location: /employee/list.php");
		exit();
	}
	else{
		echo "<p class='error'>Špatně vyplněné nebo chybějící položky</p>";
	}
}
?>
</body>
</html>