<?php
require_once("../inc/dbConnect.php");
session_start();
require_once("../inc/verifyLogin.php");
verify();

$mistnostId = (int) ($_GET["mistnostId"] ?? -1);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php
if($mistnostId == -1)
{
	echo "Bad request";
}
else{
	$pdo = connectDb();
	$stmt = $pdo->prepare("SELECT * FROM room WHERE room_id=?");
	$stmt->execute([$mistnostId]);
	if ($stmt->rowCount() == 0) {
		echo "Not found";
	} else {
		$row = $stmt->fetch();
		echo $row['name'];
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
if($mistnostId == -1)
{
	echo "<h1>Error 400 Bad request</h1>";
	http_response_code(400);
	exit();
}
$stmt = $pdo->prepare("SELECT * FROM room WHERE room_id=?");
$stmt->execute([$mistnostId]);
$row = $stmt->fetch();
if ($stmt->rowCount() == 0) {
	echo "<h1>Error 404 Not found</h1>";
	http_response_code(404);
	exit();
}
$html = "<a class='linkbutton' href='/room/list.php'><div class='buttonstyle btn btn-primary'>Zpět na seznam</div></a>";
$html .= "<h1>Detail místnosti - ".$row['name']."</h1>";
$html .= "<strong>Číslo</strong>";
$html .= "<p>".htmlspecialchars($row['no'])."</p>";
$html .= "<strong>Název</strong>";
$html .= "<p>".htmlspecialchars($row['name'])."</p>";
$html .= "<strong>Telefon</strong>";
$html .= "<p>".htmlspecialchars($row['phone'])."</p>";
$html .= "<strong>Lidé</strong>";
$celkemmzda = 0;
$pocet = 0;
$stmt = $pdo->prepare('SELECT employee.name, employee.surname, employee.employee_id, employee.room, employee.wage FROM employee WHERE employee.room = ?');
$stmt->execute([$mistnostId]);
if ($stmt->rowCount() == 0)
{
	$html .= "<p>Nikdo, nebo se nepodařilo načíst seznam</p>";
}
else 
{
	foreach($stmt as $row)
	{
		$html .= "<p><a href=/employee/detail.php?zamestnanecId=".$row['employee_id'].">".$row['surname']." ".$row['name']."</a></p>";
		$pocet++;
		$celkemmzda = $celkemmzda + $row['wage'];
	}
}
$html .= "<strong>Průměrná mzda</strong>";
if($pocet == 0)
{
	$html .= "<p>V místnosti nejsou žádní lidé</p>";
}
else
{
	$html .= "<p>".($celkemmzda/$pocet)." Kč</p>";
}

$html .= "<strong>Klíče</strong>";
$stmt = $pdo->prepare('SELECT key.employee, key.room, employee.name, employee.surname FROM `key`, employee WHERE key.room = ? AND employee.employee_id = key.employee');
$stmt->execute([$mistnostId]);
if ($stmt->rowCount() == 0)
{
	$html .= "<p>Nikdo, nebo se nepodařilo načíst seznam</p>";
}
else 
{
	foreach($stmt as $row)
	{
		$html .= "<p><a href=/employee/detail.php?zamestnanecId=".$row['employee'].">".$row['surname']." ".$row['name']."</a></p>";
	}
}
echo($html);
unset($stmt);
?>
</body>
</html>