<?php
session_start();
require_once("inc/login.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
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
.success{
	color: lime;
}
</style>
</head>
<body>
<h1>Přihlášení do databáze</h1><br>
<form action="/index.php" method="POST">
	<div class="form-group">
		<label for="username">Přihlašovací jméno:</label>
		<input type="text" id="username" class="form-control" name="username">
	</div>
	<div class="form-group">
		<label for="password">Heslo:</label>
		<input type="password" class="form-control" id="password" name="password">
	</div>
	<br>
	<button type="submit" class="btn btn-primary">Přihlásit se</button>
</form>
<?php
Login();
?>
</body>
</html>