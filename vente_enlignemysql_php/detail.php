<!DOCTYPE html>
  
  <?php 
	require('chaussure.php');
	require('Membre.php');

		session_start();

		$nom=false;
	 if (isset($_SESSION['me'])) { 
		$nom=$_SESSION['me'];
	
	
	$c=new chaussure();
	$c->getOne($_GET['un']);


	
	
	$a=new chaussure();
	$me=$a->memeCategorie($c->getmarque());

	?>
<html>
<head>
	<title>BOOTSTRAP5</title>
	<title>BOOTSTRAP5</title>
	<meta name='viewport' content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.css">
  <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>

	<style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      padding: 25px;
      background-color: lightgray;
    }
  </style>

</head>
<body>

<div class="jumbotron">
  <div class="container text-center">
    <h2>SHOES.COM</h2>
    <h1>Online Store</h1>      
    <p>Vente,Echange,etc.....</p>
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">LOGO</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
       <?php if($nom){ ?>
      <ul class="nav navbar-nav">
        <li class="active"><a href="boots5.php">ACCUEIL</a></li>
        <li><a href="addnew.php">Ajouter un Chaussure</a></li>
        <li><a href="accueil.php">Membre</a></li>
        <div class="dropdown">
          <span><br>MARQUES</span>
          <div class="dropdown-content">
             <li><a href="meme.php?caractere=Nike">Nike</a></li><br>
             <li><a href="meme.php?caractere=Adidas">Adidas</a></li><br>
              <li><a href="meme.php?caractere=Puma">Puma</a></li><br>
             <li><a href="meme.php?caractere=Stan">Stan</a></li><br>
             <li><a href="liste.php">Tous</a></li>
          </div>
        </div>
      </ul>
      <?php }else{?>
           <ul class="nav navbar-nav">
        <li class="active"><a href="boots5.php">ACCUEIL</a></li>
        <li><a href="accueil.php">Membre</a></li>
        <div class="dropdown">
          <span><br>MARQUES</span>
          <div class="dropdown-content">
             <li><a href="meme.php?caractere=Nike">Nike</a></li><br>
             <li><a href="meme.php?caractere=Adidas">Adidas</a></li><br>
              <li><a href="meme.php?caractere=Puma">Puma</a></li><br>
             <li><a href="meme.php?caractere=Stan">Stan</a></li><br>
             <li><a href="liste.php">Tous</a></li>
          </div>
        </div>
      </ul>
     <?php } ?>
      <ul class="nav navbar-nav navbar-right">
        <?php if ($nom) {?>

                    <div class="dropdown">
                        <span><li><a href="#"><?php echo $nom->getnom(); ?></a></li></span>
                        <div class="dropdown-content">
                          <ul> <li><a href="deconnexion.php"><span class="glyphicon glyphicon-user"></span>Deconnecter</a></li> </ul>
                        </div>
                    </div>
                     <li><a href="inscrit.php"><span class="glyphicon glyphicon-shopping-cart"></span>S'inscrire</a></li>
                 
                  <?php }else{?>

                   <li><a href="index.php"><span class="glyphicon glyphicon-user"></span>Connecter</a></li>
                   <li><a href="inscrit.php"><span class="glyphicon glyphicon-shopping-cart"></span>S'inscrire</a></li>

                <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container"> 

	 <div class="row">    
    <div class="col-sm-4">
      <div class="panel panel-primary">
        <div class="panel-heading"><strong><?php echo $c->getlibelle().'<br>';?> </strong></div>
        <div class="panel-body"><img  src="IMAGE/<?php echo $c->getimage();?>"></div>
        <div class="panel-footer">

            <b><?php echo "Crée par : ". $c->getnom().'<br>';?></b>
            <b><?php echo $c->getlibelle().'<br>';?></b>
            <b><?php echo $c->getid().'<br>';?></b>
            <b><?php echo $c->getprix().'<br>';?></b>
            <b><a href="detail.php?un=<?php echo $c->getid();?> "><?php echo $c->getmarque().'<br>';?></a></b>
            <b><?php echo $c->getdescription().'<br>';?></b><br>
           
        </div>


      </div>
    </div>
  </div>

    <div class="row">
      
        <h1>CHAUSSURE DE MEME CATEGORIE</h1>
   
  <?php 

   for ($i=0; $i <count($me) ; $i++) {  

    
  ?>

    <div class="col-sm-4">
      <div class="panel panel-primary">
        <div class="panel-heading"><strong><?php echo $me[$i]->getlibelle().'<br>';?> </strong></div>
        <div class="panel-body"><img  src="IMAGE/<?php echo $me[$i]->getimage();?>"></div>
        <div class="panel-footer">

            <b><?php echo "Crée par : ". $me[$i]->getnom().'<br>';?></b>
            <b><?php echo $me[$i]->getlibelle().'<br>';?></b>
            <b><?php echo $me[$i]->getid().'<br>';?></b>
            <b><?php echo $me[$i]->getprix().'<br>';?></b>
            <b><a href="detail.php?un=<?php echo $me[$i]->getid();?> "><?php echo $me[$i]->getmarque().'<br>';?></a></b>
            <b><?php echo $me[$i]->getdescription().'<br>';?></b><br>
        
           
        </div>


      </div>
    </div>
     <div class="col-sm-4"></div>
    <div class="col-sm-4"></div>
     <div class="col-sm-4"></div>
      <div class="col-sm-4"></div>
       <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
    <div class="col-sm-4"></div>
     <div class="col-sm-4"></div>
      <div class="col-sm-4"></div>
       
        
    <?php }?>
    </div>
</div><br>
<footer class="container-fluid text-center">
  <p>Online Store Copyright</p>  
  <form  method="POST" action="boot.php">MARQUE DE CHAUSSURE
    <input type="text" name="marque"  placeholder="entrez une marque" style="width: 300px; border-radius: 3px;">
    <input type="submit" name="ok" value="VALIDER" style="width:100px; background-color: red;">
  </form>
<footer class="container-fluid text-center">
  <p>Online Store Copyright</p>  
  <form  method="POST" action="parprix.php">FILTRE PAR PRIX
    <input type="number" name="chiffre"  placeholder="entrez votre prix" style="width: 300px; border-radius: 3px;">
    <input type="submit" name="ok" value="VALIDER" style="width:100px; background-color: blue;">
  </form>
</footer>
</footer>
<?php }else{
  header('location:boots5.php');
} ?>

</body>
</html>