<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>E_</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	 <meta name='viewport' content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.css">
</head>
<body style="background-color:#0CF; ">
<div class="container">
	<div class="col-lg-4"></div>
	<div class="col-lg-4">
		<div class="jumbotron" style="margin-top: 150px;">
			<h3>PLEASE LOGIN</h3>
			<form method="POST" action="traitement.php">
				<div class="form-group">
					<input type="text" name="email"  placeholder="enter Username">
				</div>
				<div class="form-group">
					<input type="password" name="mdp" placeholder="enter password">
				</div>
				<div class="checkbox">
					<label>
					<input type="checkbox">
					Remember me
					</label>
				</div>
					<a href="inscrit.php">Inscrivez-vous et connectez <span class="glyphicon glyphicon-thumbs-up"></span>
        </button> </a>
				<div> 
				</div><br>
				<button type="Submit" class="btn btn-primary form-control">Login</button>
				
			</form>
		</div>
	</div>
</div>
</body>

