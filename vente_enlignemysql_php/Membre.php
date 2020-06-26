<?php

 class Membre{
 	private $_id;
	private $_nom;
	private $_mail;
	private $_password;
	private $_sexe;
	private $_adress;
	private $_image;
	private $_libelle;
	private $_prix;
	private $_categorie;
	private $_description;
	private $_id_membre;


	public function getid(){//Declaren le attribut anaty vetement anaovana jointure
		return $this->_id;
	}
	public function getimage(){
		return $this->_image;
	}
	public function getlibelle(){
		return $this->_libelle;
	}
	public function getprix(){
		return $this->_prix;
	}
	public function getcategorie(){
		return $this->_categorie;
	}
	public function getdescription(){
		return $this->_description;
	}
	public function getid_membre(){
		return $this->_id_membre;
	}

	public function getnom(){
	  return $this->_nom;
	}
	public function getmail(){
		return $this->_mail;
	}
	public function getpassword(){
		return $this->_password;
	}
	public function getsexe(){
		return $this->_sexe;
	}
	public function getadress(){
		return $this->_adress;
	}
	

	function __construct(){
	}

	public function getAll(){
		$requet = 'SELECT * FROM membre';
		$query =connexion()->query($requet);
		$membres = array();
		while ($membre = $query->fetch()) {
			$user = new Membre();
				$user->_id = $membre['id'];
				$user->_mail = $membre['mail'];
				$user->_password = $membre['password'];
				$user->_nom = $membre['nom'];
				$user->_sexe = $membre['sexe'];
				$user->_adress = $membre['adress'];
			array_push($membres, $user);
		}
		return $membres;

	}

	public function Login($log,$mp){
		$a=new Membre();
		$us =$a->getAll();
			for ($j=0; $j <count($us) ; $j++) { 
				if ($us[$j]->getmail()==$log && $us[$j]->getpassword()==$mp) {
						return $us[$j];
				}
			}
		}	
		
	public function modificationmembre($nom,$mail,$password,$sexe,$adress,$id){
			$requete = "UPDATE membre SET   nom='%s',mail='%s',password='%s',sexe='%s',adress='%s'  WHERE id=%s";
			$requete = sprintf($requete,$nom,$mail,$password,$sexe,$adress,$age,$id);
			$query = connexion()->exec($requete);
		}

	public function insertionmembre($nom,$mail,$password,$sexe,$adress){
			$requet = 'INSERT INTO membre (nom,mail,password,sexe,adress) VALUES (:nom,:email,:password,:sexe,:adress)';
			$query=connexion()->prepare($requet);
		
			$query->bindValue(':nom', $nom, PDO::PARAM_STR);
			$query->bindValue(':email', $mail, PDO::PARAM_STR);
			$query->bindValue(':password', $password, PDO::PARAM_STR);
			$query->bindValue(':sexe', $sexe, PDO::PARAM_STR);
			$query->bindValue(':adress', $adress, PDO::PARAM_STR);
			

		$query->execute();


	}

	public function Tousjoin($id){//AS id anle vetement ian no ampesain ary amban
			$requete = "SELECT v.image,v.libelle,v.prix,v.marque,v.description,v.id_membre,v.id AS id,m.mail,m.password,m.nom,m.sexe,m.adress from chaussure v  JOIN membre m on v.id_membre=m.id where v.id_membre = %s";
			$requete = sprintf($requete,$id);
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			$resultat = array();
			while ($entrés = $query->fetch()) {
				$reponse = new Membre();

				$reponse->_id = $entrés['id'];
				$reponse->_image = $entrés['image'];
				$reponse->_libelle = $entrés['libelle'];
				$reponse->_prix = $entrés['prix'];
				$reponse->_marque = $entrés['marque']; 
				$reponse->_description = $entrés['description'];
				$reponse->_id_membre = $entrés['id_membre'];
				$reponse->_mail = $entrés['mail'];
				$reponse->_password = $entrés['password'];
				$reponse->_nom = $entrés['nom'];
				$reponse->_sexe = $entrés['sexe'];
				$reponse->_adress = $entrés['adress'];
				


				array_push($resultat, $reponse);
			}
			return $resultat;
		} 
}

 ?>