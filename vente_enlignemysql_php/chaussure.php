<?php 

		include('connexion.php');


	class chaussure{
		private $_id;
		private $_image;
		private $_libelle;
		private $_prix;
		private $_marque;
		private $_description;
		private $_idmembre;
		private $_nom;
	public function getnom(){
		return $this->_nom;
	}
	public function getid(){
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
	public function getmarque(){
		return $this->_marque;
	}
	public function getdescription(){
		return $this->_description;
	}
	public function getidmembre(){
		return $this->_idmembre;
	}

	function __construct()
		{
		}

	public function getAll(){
			$requete = "SELECT c.image,c.libelle,c.prix,c.marque,c.description,c.idmembre,c.id AS id,m.nom from chaussure c  JOIN membre m on c.idmembre=m.id";
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			$resultat = array();
			while ($entre = $query->fetch()) {
				$reponse = new chaussure();

				$reponse->_id = $entre['id'];
				$reponse->_image = $entre['image']; 
				$reponse->_libelle = $entre['libelle'];
				$reponse->_prix = $entre['prix']; 
				$reponse->_marque = $entre['marque'];
				$reponse->_description = $entre['description'];
				$reponse->_idmembre = $entre['idmembre'];
				$reponse->_nom = $entre['nom'];
				
				array_push($resultat, $reponse);
			}
			return $resultat;
		} 


	public function insertionvetement($image,$libelle,$prix,$marque,$description,$idmembre){
			$requete = "INSERT INTO chaussure (image,libelle,prix,categorie,marque,idmembre) VALUES ('%s','%s', %s,'%s','%s',%s)";
			$requete = sprintf($requete,$image,$libelle,$prix,$marque,$description,$idmembre);
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();			
		}
		
		public function memeCategorie($id){
			$requete = "SELECT c.image,c.libelle,c.prix,c.marque,c.description,c.idmembre,c.id AS id,m.nom from chaussure c  JOIN membre m on c.idmembre=m.id WHERE marque = '%s'  ";
			$requete = sprintf($requete,$id);
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			$resultat = array();
			while ($entre = $query->fetch()) {
				$reponse = new chaussure();

				$reponse->_id = $entre['id'];
				$reponse->_image = $entre['image']; 
				$reponse->_libelle = $entre['libelle'];
				$reponse->_prix = $entre['prix']; 
				$reponse->_marque = $entre['marque'];
				$reponse->_description = $entre['description'];
				$reponse->_idmembre = $entre['idmembre'];
				$reponse->_nom = $entre['nom'];

				
				array_push($resultat, $reponse);
			}
			return $resultat;
		} 
		public function rechercheParmarque($id){
			$requete = "SELECT c.image,c.libelle,c.prix,c.marque,c.description,c.idmembre,c.id AS id,m.nom from chaussure c  JOIN membre m on c.idmembre=m.id WHERE libelle = '%s' ";
			$requete = sprintf($requete,$id);
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			$resultat = array();
			while ($entree = $query->fetch()) {
				$reponse = new chaussure();

				$reponse->_id = $entree['id'];
				$reponse->_image = $entree['image'];
				$reponse->_libelle = $entree['libelle'];
				$reponse->_prix = $entree['prix']; 
				$reponse->_marque = $entree['marque'];
				$reponse->_description = $entree['description'];
				$reponse->_idmembre = $entree['idmembre'];
				$reponse->_nom = $entree['nom'];
				
			
				array_push($resultat, $reponse);
			}
			return $resultat;
		}

		public function inferieurMax($max){
			$requete = "SELECT c.image,c.libelle,c.prix,c.marque,c.description,c.idmembre,c.id AS id,m.nom from chaussure c  JOIN membre m on c.idmembre=m.id  WHERE prix < %s";
			$requete = sprintf($requete,$max);
			$query = connexion()->prepare($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			$resultat = array();
			while ($entree = $query->fetch()) {
				$reponse = new Chaussure();

				$reponse->_id = $entree['id'];
				$reponse->_image = $entree['image'];
				$reponse->_libelle = $entree['libelle'];
				$reponse->_prix = $entree['prix']; 
				$reponse->_marque = $entree['marque'];
				$reponse->_idmembre = $entree['idmembre'];
				$reponse->_description = $entree['description'];
				$reponse->_nom = $entree['nom'];
				

				array_push($resultat, $reponse);
			}
			return $resultat;
		}

		public function getOne($id){
			$requete = "SELECT c.image,c.libelle,c.prix,c.marque,c.description,c.idmembre,c.id AS id,m.nom from chaussure c  JOIN membre m on c.idmembre=m.id WHERE c.id=%s";
			$requete = sprintf($requete,$id);
			$query = connexion()->query($requete);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();

			while ($entree = $query->fetch()) {
				$this->_id = $entree['id'];
				$this->_image= $entree['image'];
				$this->_libelle = $entree['libelle'];
				$this->_prix = $entree['prix'];
				$this->_marque = $entree['marque'];
				$this->_description = $entree['description'];
				$this->_idmembre = $entree['idmembre'];
				$this->_nom = $entree['nom'];
			}
		}

}


 ?>