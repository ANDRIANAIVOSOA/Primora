<?php

		session_start();

		$nom=false;
	 if (isset($_SESSION['me'])) { 
		$nom=$_SESSION['me'];
		}	



	require_once 'connexion.php';
	
	if(isset($_POST['btnsave']))
	{
		$libelle = $_POST['libelle'];
		$prix = $_POST['prix'];
		$marque = $_POST['marque'];
		$description = $_POST['description'];
		$idmembre = $_POST['idmembre'];
		
		$imgFile = $_FILES['image']['name'];
		$tmp_dir = $_FILES['image']['tmp_name'];
		$imgSize = $_FILES['image']['size'];
		
		
		if(empty($libelle)){
			$errMSG = "Please Enter your libelle.";
			echo $errMSG;
		}
		else if(empty($marque)){
			$errMSG = "Please Enter Your marque.";
			echo $errMSG;
		}
		else if(empty($prix)){
			$errMSG = "Please Enter Your prix.";
			echo $errMSG;
		}
		else if(empty($description)){
			$errMSG = "Please Enter Your description.";
			echo $errMSG;
		}
		else if(empty($idmembre)){
			$errMSG = "Please Enter Your idmembre";
			echo $errMSG;
		}
		else if(empty($imgFile)){
			$errMSG = "Please Select Image File.";
			echo $errMSG;
		}
		else
		{
			$upload_dir = 'IMAGE/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$userpic = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = connexion()->prepare('INSERT INTO chaussure (image,libelle,prix,marque,description,idmembre) VALUES (:image, :libelle, :prix, :marque,  :description, :idmembre)');
			$stmt->bindParam(':image',$userpic);
			$stmt->bindParam(':libelle',$libelle);
			$stmt->bindParam(':prix',$prix);
			$stmt->bindParam(':marque',$marque);
			$stmt->bindParam(':description',$description);
			$stmt->bindParam(':idmembre',$idmembre);
			
			
			if($stmt->execute())
			{
				$successMSG = "new record succesfully inserted ...";
				header("refresh:5;boots5.php"); // redirects image view page after 5 seconds.
			}
			else
			{
				$errMSG = "error while inserting....";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Upload, Insert, Update, Delete an Image using PHP MySQL - Coding Cage</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

</head>
<body>

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
 
        <div class="navbar-header">
            <a class="navbar-brand" href="accueil.php">Exemple</a>
            <a class="navbar-brand" href="addnew.php">Insertion</a>
            <a class="navbar-brand" href="">Modification</a>
            <a class="navbar-brand" href="">Suppression</a>
        </div>
 
    </div>
</div>

<div class="container">


	<div class="page-header">
    	<h1 class="h2">AJOUTER DES NOUVEAU CHAUSSURE. <a class="btn btn-default" href="accueil.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; VISER </a></h1>
    </div>
    

	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">LIBELLE.</label></td>
        <td><input class="form-control" type="text" name="libelle" placeholder="Enter libelle"  /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">MARQUE.</label></td>
        <td><input class="form-control" type="text" name="marque" placeholder="Your marque"  /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">PRIX</label></td>
        <td><input class="input-group" type="number" name="prix" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">DESCRIPTION</label></td>
        <td><input class="form-control" type="text" name="description" placeholder="Your description"  /></td>
    </tr>
     <tr>
    	<td><label class="control-label">IDMEMBRE</label></td>
        <td><input class="form-control" type="number" name="idmembre" placeholder="Your idmembre"  /></td>
    </tr>
    <tr>
    	<td><label class="control-label">PROFIL IMAGE</label></td>
        <td><input class="input-group" type="file" name="image" accept="image/*" /></td>
    </tr>
    
    
    <tr>
        <td colspan="2"><button type="submit" name="btnsave" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> &nbsp; ENREGISTRER
        </button>
        </td>
    </tr>
    
    </table>
    
</form>



<div class="alert alert-info">
    <strong>tutorial link !</strong> <a href="">Coding Cage</a>!
</div>

    

</div>

<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>



</body>
</html>