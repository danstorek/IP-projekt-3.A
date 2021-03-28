<?php
function Login(){
	if(array_key_exists("username",$_POST)){
		require_once("inc/dbConnect.php");
		$pdo = connectDb();
		$stmt = $pdo->query('SELECT * FROM employee WHERE `login`="'.filter_var($_POST["username"]).'"');
		if ($stmt->rowCount() == 0){
			echo "<p class='error'>Neznámý uživatel</p>";
		}
		else{
			while ($row = $stmt->fetch()) {
				if(password_verify($_POST["password"], $row["password"])){
					$_SESSION["username"] = $row["login"];
					$_SESSION["password"] = $row["password"];
					$_SESSION["id"] = $row["employee_id"];
					$_SESSION["admin"] = $row["admin"];
					$_SESSION["fullname"] = $row["name"]." ".$row["surname"];
					header("Location: /main.php");
					die();
				}
				else {
					echo "<p class='error'>Špatné heslo</p>";
				}
			}
		}
	}
	else {
		$_SESSION = [];
		session_destroy();
	}
}
?>