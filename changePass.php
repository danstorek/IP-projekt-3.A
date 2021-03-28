<?php
session_start();
require_once("inc/verifyLogin.php");
verify();

require_once("inc/dbConnect.php");
$pdo = connectDb();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Změna hesla</title>
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
.linkbutton, .linkbutton:hover, .linkbutton:visited, .linkbutton:link, .linkbutton:active{
	text-decoration: none;
	color: white;
}
.error{
	color: red;
}
.success{
	color: lime;
}
</style>
</head>
<body>
<h1>Změna hesla</h1><br>
<form action="/changePass.php" method="POST">
<div class="form-group">
	<label for="passwordOld">Staré heslo:</label>
	<input type="password" id="passwordOld" class="form-control" name="passwordOld">
</div>
<div class="form-group">
	<label for="passwordNew">Nové heslo:</label>
	<input type="password" id="passwordNew" class="form-control" name="passwordNew">
</div>
<div class="form-group">
	<label for="passwordNew1">Opakujte heslo:</label>
	<input type="password" id="passwordNew1" class="form-control" name="passwordNew1">
</div><br>
<a class='linkbutton' href='/main.php'><div class='buttonstyle btn btn-danger'>Zrušit</div></a>
<button type="submit" class="btn btn-success">Změnit heslo</button>
</form>
<?php
if(count($_POST) == 3){
	if($_POST["passwordOld"] != "" && $_POST["passwordNew"] != "" && $_POST["passwordNew1"] != ""){
		if($_POST["passwordNew"] != $_POST["passwordNew1"])
		{
			echo "<p class='error'>Nová hesla se neshodují</p>";
		}
		else{
			if(password_verify($_POST["passwordOld"], $_SESSION["password"]))
			{
				$pdo->query('UPDATE employee SET password="'.password_hash($_POST["passwordNew"], PASSWORD_BCRYPT).'" WHERE `login` LIKE "'.$_SESSION["username"].'"');
				
				
				$_SESSION = [];
				session_destroy();
				echo "<p class='success'>Heslo bylo změněno</p></br>";
				echo "<p><a href='/index.php'>Klikni zde pro opětovné přihlášení</a></p>";
			}
			else{
				echo "<p class='error'>Zadal jste špatné staré heslo</p>";
			}
		}
	}
	else {
		echo "<p class='error'>Nejsou vyplněna všechna pole</p>";
	}
}
?>
</body>
</html>