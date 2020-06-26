<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>INSCRIVEZ vous</title>
	<meta name='viewport' content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
 	 <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.css">
 	 <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
<div class="total">
	<div id="signup"><br>
			<h3>Registration</h3><br><br>
		<form method="post" action="controlleur.php" name="signup">
			<label>Name</label>
			<input type="text" name="nom" autocomplete="off" />
			<label>Email</label>
			<input type="text" name="email" autocomplete="off" />
			<label>Sexe</label>
			<input type="text" name="sexe" autocomplete="off" />
			<label>Password</label>
			<input type="password" name="pass" autocomplete="off"/>
			<label>Adress</label>
			<input type="text" name="Adresse" autocomplete="off" />
			<div class="errorMsg"></div>
			<input type="submit" class="button" name="signupSubmit" value="Signup">
		</form>
	</div>
</div>
</body>
</html>