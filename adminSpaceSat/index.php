<?php 
session_start();
$access = 'admin';
//------------- Déconnexion ------------//
if(isset($_GET['deconnexion'])) :
  unset($_SESSION['authenticate']);
        session_destroy();
        header('location:index.php');
endif ;

//Affichage erreur validation formulaire
function setClassErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'class="error"':'');  
}
function setBulleErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'<div id="bulleErreur" class="bulleErreur"><span><b>'.$GLOBALS["erreurs"][$champs].'</b></span></div>':'');  
}

// Vérification Formulaire avant soumission
if(isset($_POST['Envoie'])){

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : message non envoyé</div>';

     $login     =  htmlentities($_POST['login'],   ENT_QUOTES);
     $pwd       =  htmlentities($_POST['pwd'],  ENT_QUOTES);
     
     // Nom
     if(empty($login))   
            $GLOBALS["erreurs"]['login']='<b>Login</b> obligatoire';
     
     // Email
     if(empty($pwd))   
            $GLOBALS["erreurs"]['pwd']='<b>Password</b> obligatoire';

    

    //enregistrement Bdd
    if(!isset($GLOBALS["erreurs"])){
      //conexion Bdd
      include("../config/ConnexionBdd.php");
      // Vérification dans Bdd Login Mot de passe
      $result=$maBase->query("SELECT ID_utilisateur,NOM_utilisateur FROM cnamcp09_utilisateurs 
              WHERE LOGIN_utilisateur='{$login}' 
                  AND MDP_utilisateur='{$pwd}'") ;
      //--- Succès Authentification ---//
      if($result){
          if(!$user=$result->fetch()){ 
              $retourEnvoiForm = '<div class="retourEnvoiForm">Login et mot de passe incorrecte !</div>'; 
          }else{ 
              $_SESSION['authenticate']['id_user']  = $user['ID_utilisateur'] ;
              $_SESSION['authenticate']['user']     = $user['NOM_utilisateur'] ;
          }
      }

    }
}
?><!DOCTYPE HTML>
<!--
	Theory by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>

<!-- Head -->

   <?php include("../include/head.php"); ?>

	<body>

		<!-- LOGO --> <!-- Bannière -->
			<section id="titreAdmin">
        <div class="inner">
        <header class="align-center">
            <h2>Administration</h2>
            <p>Bienvenue dans l'interface d'administration du site.</p>
          </header>
        </div></section>



<!-- NAVBAR -->

<header id="header">
    <a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
  </div>
</header>


<!-- Form -->

<section id="contact" class="content-wrapper">
<div class="inner">


 <?php  //-----------------------------------//
        //--- Formulaire authentification ---//
        //-----------------------------------//
 if( !isset($_SESSION['authenticate']) ) :  ?>

        <h3>Authentification</h3>


        <?php // message retour formulaire envoyé
        echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>

        <form name="authentification" method="post" action="index.php">
          <div class="row uniform">
            <div class="6u 12u$(xsmall) formChamp">
              <?php setBulleErreur('login'); ?>
              <input type="text" name="login" id="login" value="<?php echo isset($login)?html_entity_decode($login):''; ?>" <?php setClassErreur('login'); ?> placeholder="Login" />

            </div>
            <div class="6u 12u$(xsmall) formChamp">
              <?php setBulleErreur('pwd'); ?>
              <input type="text" name="pwd" id="pwd" value="<?php echo isset($pwd)?html_entity_decode($pwd):''; ?>" <?php setClassErreur('pwd'); ?> placeholder="Password" />
            </div>


            <div class="12u$">
              <ul class="actions">
                <li><input type="submit" name="Envoie" value="Se connecter" /></li>
                <li><input type="reset" value="Effacer" class="alt" /></li>
              </ul>
            </div>
          </div>
        </form>

 <?php  //-------------------------//
        //--- User authentifié  ---//
        //-------------------------//
  else: ?>
            Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a>

            <section id="one" class="wrapper">

            <div class="inner">
              <nav id="nav">
                <a href="categories.php">Catégories</a>
                <a href="articles.php">Articles</a>
                <a href="portofolio.php">Portofolio</a>
                <a href="messages.php">Messages</a>
              </nav>
            </div>

          </section>

<?php endif; ?>


</section>

		<!-- Footer -->

		<?php include("../include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("../include/scripts.php"); ?>

	</body>
</html>