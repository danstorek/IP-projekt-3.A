<?php
function deleteEmployee($zamestnanecIdIn){
	$zamestnanecId = (int) ($zamestnanecIdIn ?? -1);
	if($zamestnanecId == -1){
		$message = "Vyskytla se neznámá chyba";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
	else{
		verifyAdmin();
		if($_SESSION["id"] != $zamestnanecId)
		{		
			$pdo = connectDb();
			$pdo->query('DELETE FROM `key` WHERE `key`.`employee` = '.$zamestnanecId);
			$pdo->query('DELETE FROM `employee` WHERE `employee`.`employee_id` = '.$zamestnanecId);
		}
		else{
			$message = "Nemůžete odstranit sám sebe";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
}
function deleteRoom($mistnostIdIn){
	$mistnostId = (int) ($mistnostIdIn ?? -1);
	if($mistnostId == -1){
		$message = "Vyskytla se neznámá chyba";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
	else{
		verifyAdmin();
		$pdo = connectDb();
		$stmt = $pdo->query('SELECT * FROM `employee` WHERE `employee`.`room` = '.$_GET["mistnostId"]);
		if ($stmt->rowCount() == 0)
		{
			$pdo->query('DELETE FROM `key` WHERE `key`.`room` = '.$_GET["mistnostId"]);
			$pdo->query('DELETE FROM `room` WHERE `room`.`room_id` = '.$_GET["mistnostId"]);
		}
		else{
			$message = "Někdo má tuto místnost nastavenou jako hlavní";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
		unset($stmt);
	}
}
?>