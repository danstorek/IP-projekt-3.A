<?php
require_once("../inc/dbConnect.php");
require_once("../inc/verifyLogin.php");
require_once("../inc/delete.php");

session_start();
verify();
$razeni = (int) ($_GET["razeni"] ?? 0);
$odstraneni = (int) ($_GET["mistnostId"] ?? 0);
if($odstraneni != 0){
	deleteRoom($odstraneni);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seznam místností</title>
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
$sqlPrikaz = 'SELECT * FROM room ORDER BY name';
if(isset($razeni) && $razeni != 0){
	if($razeni == 1){
		$sqlPrikaz = 'SELECT * FROM room ORDER BY name DESC';
	}
	else if($razeni == 2){
		$sqlPrikaz = 'SELECT * FROM room ORDER BY no';
	}
	else if($razeni == 3){
		$sqlPrikaz = 'SELECT * FROM room ORDER BY no DESC';
	}
	else if($razeni == 4){
		$sqlPrikaz = 'SELECT * FROM room ORDER BY phone';
	}
	else if($razeni == 5){
		$sqlPrikaz = 'SELECT * FROM room ORDER BY phone DESC';
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
	$html .= "<th>Název<a class=razeni href=/room/list.php?razeni=0>🔼</a><a class=razeni href=/room/list.php?razeni=1>🔽</a></th>"."<th>Číslo<a class=razeni href=/room/list.php?razeni=2>🔼</a><a class=razeni href=/room/list.php?razeni=3>🔽</a></th>"."<th>Telefon<a class=razeni href=/room/list.php?razeni=4>🔼</a><a class=razeni href=/room/list.php?razeni=5>🔽</a></th>";
	if($_SESSION["admin"] == 1){
		$html .= "<th></th>";
		$html .= "<th></th>";
	}
	$html .= "</thead>";
	$html .= "<tbody>";
	while ($row = $stmt->fetch()) {
		$html .= "<tr>";
		$html .= "<td><a href='/room/detail.php?mistnostId=".$row['room_id']."'>".htmlspecialchars($row['name'])."</a></td>";
		$html .= "<td>".htmlspecialchars($row['no'])."</td>";
		$html .= "<td>".htmlspecialchars($row['phone'])."</td>";
		if($_SESSION["admin"] == 1){
			$html .= "<td><a class='linkbutton' href='/room/edit.php?mistnostId=".$row['room_id']."'><div class='buttonstyle btn btn-primary'>Upravit</div></a>";
			$html .= "<a class='linkbutton' href='/room/list.php?mistnostId=".$row['room_id']."'><div class='buttonstyle btn btn-danger'>Smazat</div></a></td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<a class='linkbutton' href='/main.php'><div class='buttonstyle btn btn-primary'>Přejít na rozcestí</div></a>";
	if($_SESSION["admin"] == 1){
		$html .= "<a class='linkbutton' href='/room/create.php'><div class='buttonstyle btn btn-success'>Přidat novou místnost</div></a>";
	}
	echo $html;
}
unset($stmt);
?>
</main>
</body>
</html>