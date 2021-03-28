<?php
function verify(){
	if(!array_key_exists("username", $_SESSION)){
		header("Location: /index.php");
		exit();
	}
}

function verifyAdmin(){
	if(!array_key_exists("username", $_SESSION) || $_SESSION["admin"] != 1){
		header("Location: /index.php");
		exit();
	}
}
?>