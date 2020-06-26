<?php 
		require('chaussure.php');
	require('Membre.php');

	session_start(); 

		 $p=new Membre();
		$me=$p->login($_POST['email'],$_POST['mdp']);
			 $_SESSION['me']=$me;
			 $nom=$_SESSION['me'];


			 
			  if (isset($_SESSION['me'])) {
			  	$_SESSION['me']=$me;
				 $nom=$_SESSION['me'];
				 $p=new Membre(); 
			}
				header('location:boots5.php');
			 
			  ?>
			
			

