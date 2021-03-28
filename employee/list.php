<?php
require_once("../inc/dbConnect.php");
require_once("../inc/verifyLogin.php");
require_once("../inc/delete.php");

session_start();
verify();
$razeni = (int) ($_GET["razeni"] ?? 0);
$odstraneni = (int) ($_GET["zamestnanecId"] ?? 0);
if($odstraneni != 0){
	deleteEmployee($odstraneni);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seznam zaměstnanců</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
.razeni {
	font-size: 20px;
	text-decoration: none;
}
.buttonstyle{
	display: inline-block;
}
main{
	width: 900px;
	margin: auto;
	border-style: outset;
}
.linkbutton, .linkbutton:hover, .linkbutton:visited, .linkbutton:link, .linkbutton:active{
	text-decoration: none;
	color: white;
}

</style>
</head>
<body>
<main>
<?php
$pdo = connectDb();
$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY surname';
if(isset($razeni) && $razeni != 0){
	if($razeni == 1){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY surname DESC';
	}
	else if($razeni == 2){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY roomName';
	}
	else if($razeni == 3){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY roomName DESC';
	}
	else if($razeni == 4){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY phone';
	}
	else if($razeni == 5){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY phone DESC';
	}
	else if($razeni == 6){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY job';
	}
	else if($razeni == 7){
		$sqlPrikaz = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS "roomName", room.phone FROM employee, room WHERE room.room_id = employee.room ORDER BY job DESC';
	}
}
$stmt = $pdo->query($sqlPrikaz);
if ($stmt->rowCount() == 0)
{
	echo "Záznam neobsahuje žádná data";
}
else
{
	$html = '<table class="table">';
	$html .= "<thead>";
	$html .= "<th>Jméno<a class=razeni href=/employee/list.php?razeni=0>🔼</a><a class=razeni href=/employee/list.php?razeni=1>🔽</a></th>"."<th>Místnost<a class=razeni href=/employee/list.php?razeni=2>🔼</a><a class=razeni href=/employee/list.php?razeni=3>🔽</a></th>"."<th>Telefon<a class=razeni href=/employee/list.php?razeni=4>🔼</a><a class=razeni href=/employee/list.php?razeni=5>🔽</a></th>"."<th>Pozice<a class=razeni href=/employee/list.php?razeni=6>🔼</a><a class=razeni href=/employee/list.php?razeni=7>🔽</a></th>";
	if($_SESSION["admin"] == 1){
		$html .= "<th></th>";
	}
	$html .= "</thead>";
	$html .= "<tbody>";
	while ($row = $stmt->fetch()) {
		$html .= "<tr>";
		$html .= "<td><a href='/employee/detail.php?zamestnanecId=".$row['employee_id']."'>".htmlspecialchars($row['surname'])." ".htmlspecialchars($row['name'])."</a></td>";//"<td>".htmlspecialchars($row['name'])." ".htmlspecialchars($row['surname'])."</td>";
		$html .= "<td>".htmlspecialchars($row['roomName'])."</td>";
		$html .= "<td>".htmlspecialchars($row['phone'])."</td>";
		$html .= "<td>".htmlspecialchars($row['job'])."</td>";
		if($_SESSION["admin"] == 1){
			$html .= "<td><a class='linkbutton' href='/employee/edit.php?zamestnanecId=".$row['employee_id']."'><div class='buttonstyle btn btn-primary'>Upravit</div></a>";
			$html .= "<a class='linkbutton' href='/employee/list.php?zamestnanecId=".$row['employee_id']."'><div class='buttonstyle btn btn-danger'>Smazat</div></a></td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<a class='linkbutton' href='/main.php'><div class='buttonstyle btn btn-primary'>Přejít na rozcestí</div></a>";
	if($_SESSION["admin"] == 1){
		$html .= "<a class='linkbutton' href='/employee/create.php'><div class='buttonstyle btn btn-success'>Přidat nového zaměstnance</div></a>";
	}
	echo $html;
}
unset($stmt);
?>
</main>
</body>
</html>