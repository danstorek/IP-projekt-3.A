<?php
require_once("../inc/dbConnect.php");
session_start();
require_once("../inc/verifyLogin.php");
verify();

$zamestnanecId = (int) ($_GET["zamestnanecId"] ?? -1);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php
if($zamestnanecId == -1)
{
	echo "Bad request";
}
else{
	$pdo = connectDb();
	$stmt = $pdo->prepare("SELECT employee.surname, employee.name FROM employee WHERE employee_id=?");
	$stmt->execute([$zamestnanecId]);
	if ($stmt->rowCount() == 0) {
		echo "Not found";
	} else {
		$row = $stmt->fetch();
		echo $row['surname']." ".$row['name'];
	}
}
?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
p {
margin-left:45px;
}
.linkbutton, .linkbutton:hover, .linkbutton:visited, .linkbutton:link, .linkbutton:active{
	text-decoration: none;
	color: white;
}
.buttonstyle{
	display: inline-block;
}
</style>
</head>
<body>
<?php
if($zamestnanecId == -1)
{
	echo "<h1>Error 400 Bad request</h1>";
	http_response_code(400);
	exit();
}
$stmt = $pdo->prepare('SELECT employee.name, employee.surname, employee.job, employee.room, employee.wage, room.name AS "roomName" FROM employee, room WHERE employee_id=? AND room.room_id = employee.room');
$stmt->execute([$zamestnanecId]);
$row = $stmt->fetch();
if ($stmt->rowCount() == 0) {
	echo "<h1>Error 404 Not found</h1>";
	http_response_code(404);
	exit();
}
$html = "<a class='linkbutton' href='/employee/list.php'><div class='buttonstyle btn btn-primary'>Zpět na seznam</div></a>";
$html .= "<h1>Detail zaměstnance - ".$row['surname']." ".$row['name']."</h1>";
$html .= "<strong>Jméno</strong>";
$html .= "<p>".htmlspecialchars($row['name'])."</p>";
$html .= "<strong>Příjmení</strong>";
$html .= "<p>".htmlspecialchars($row['surname'])."</p>";
$html .= "<strong>Pozice</strong>";
$html .= "<p>".htmlspecialchars($row['job'])."</p>";
$html .= "<strong>Mzda</strong>";
$html .= "<p>".htmlspecialchars($row['wage'])."Kč"."</p>";
$html .= "<strong>Místnost</strong>";
$html .= "<p><a href=/room/detail.php?mistnostId=".$row['room'].">".$row['roomName']."</a></p>";
$html .= "<strong>Klíče</strong>";
$stmt = $pdo->prepare('SELECT key.employee, key.room, room.name FROM `key`, room WHERE employee = ? AND room.room_id = key.room');
$stmt->execute([$zamestnanecId]);
if ($stmt->rowCount() == 0)
{
	$html .= "<p>Nepodařilo se načíst klíče</p>";
}
else
{
	foreach($stmt as $row)
	{
		$html .= "<p><a href=/room/detail.php?mistnostId=".$row['room'].">".$row['name']."</a></p>";
	}
}
echo($html);
unset($stmt);
?>
</body>
</html>