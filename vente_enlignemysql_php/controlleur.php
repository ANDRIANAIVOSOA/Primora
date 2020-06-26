<?php 

	require('Membre.php');
	require('chaussure.php');


if (isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['sexe']) && isset($_POST['pass']) && isset($_POST['Adresse'])){
	$nom = $_POST['nom'];
	$email = $_POST['email'];
	$sexe = $_POST['sexe'];
	$password = $_POST['pass'];
	$adress = $_POST['Adresse'];
	$m = new Membre();
	$m->insertionmembre($nom,$email,$password,$sexe,$adress);
	echo 'Insertion avec succes';
	header('location:index.php');
}
?>