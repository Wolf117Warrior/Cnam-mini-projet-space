<?php 
//=========================================================
// fonctions
//=========================================================
include("./config/ConnexionBdd.php"); 
include_once("./config/fonctions.php");
//=========================================================
// Vérification Formulaire avant soumission
//=========================================================
if(isset($_POST['Envoie'])){

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : message non envoyé</div>';

     $nom       =  htmlentities($_POST['nom'],   ENT_QUOTES);
     $prenom    =  htmlentities($_POST['prenom'],   ENT_QUOTES);
     $email     =  htmlentities($_POST['email'],  ENT_QUOTES);
     $objet     =  htmlentities($_POST['objet'], ENT_QUOTES);
     $message   =  htmlentities($_POST['message'],   ENT_QUOTES);
     $human     =  htmlentities(isset($_POST['human'])?$_POST['human']:'',   ENT_QUOTES);
     
     // Nom
     if(empty($nom))   
            $GLOBALS["erreurs"]['nom']='<b>Nom</b> obligatoire';

    if(empty($prenom))   
            $GLOBALS["erreurs"]['prenom']='<b>Prénom</b> obligatoire';
     
     // Email
     if(empty($email))   
            $GLOBALS["erreurs"]['email']='<b>Email</b> obligatoire';
     elseif(!preg_match('#^[\w.+-]{1,64}@[\w.-]{1,64}\.[\w.-]{2,6}$#i', $email) )
             $GLOBALS["erreurs"]['email']='<b>Email</b> non valide';
    
      
      // Objet
     if(empty($objet))   
            $GLOBALS["erreurs"]['objet']='<b>Objet</b> obligatoire';  

     // Message
     if(empty($message))   
            $GLOBALS["erreurs"]['message']='<b>Message</b> obligatoire'; 

     // Human
     if(empty($human))   
            $GLOBALS["erreurs"]['human']='<b>Click</b> obligatoire'; 

    //enregistrement Bdd
    if(!isset($GLOBALS["erreurs"])){
      // insertion message Bdd
      $sauvegarde_message=$maBase->exec("INSERT INTO cnamcp09_messages (NOM_message, PRENOM_message,EMAIL_message, OBJET_message, TEXT_message, DATE_message) 
            VALUES ('{$nom}','{$prenom}','{$email}','{$objet}','{$message}',NOW())") ;
      // si succès
      if($sauvegarde_message==1)
        $retourEnvoiForm = '<div class="retourEnvoiFormok">Le message a été envoyé avec succès</div>';


    }
}
?>
<!DOCTYPE HTML>
<!--
	Theory by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>


<!-- Head -->

   <?php include("./include/head.php"); ?>

	<body>

		<!-- LOGO --> <!-- Bannière -->
			<section id="banner">
			</section>



<!-- NAVBAR -->

<header id="header">
    <a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
  </div>
</header>


<?php include("./include/navbar.php"); ?>
<!-- Form -->

</section id="contact" class="content-wrapper">
<div class="inner">
  <h3>Formulaire de contact</h3>


  <?php // message retour formulaire envoyé
  echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>

  <form name="contact" method="post" action="contact.php">
    <div class="row uniform">
      <div class="6u 12u$(xsmall) formChamp">
        <?php setBulleErreur('nom'); ?>
        <input type="text" name="nom" id="nom" value="<?php echo isset($nom)?html_entity_decode($nom):''; ?>" <?php setClassErreur('nom'); ?> placeholder="Nom" />
      </div>
      <div class="6u 12u$(xsmall) formChamp">
        <?php setBulleErreur('prenom'); ?>
        <input type="text" name="prenom" id="prenom" value="<?php echo isset($prenom)?html_entity_decode($prenom):''; ?>" <?php setClassErreur('prenom'); ?> placeholder="Prénom" />
      </div>
      <div class="6u$ 12u$(xsmall) formChamp">
        <?php setBulleErreur('email'); ?>
        <input type="text" name="email" id="email" value="<?php echo isset($email)?html_entity_decode($email):''; ?>" <?php setClassErreur('email'); ?> placeholder="Courriel" />
      </div>
      <div class="6u 12u$(xsmall) formChamp">
        <?php setBulleErreur('objet'); ?>
        <input type="text" name="objet" id="objet" value="<?php echo isset($objet)?html_entity_decode($objet):''; ?>" <?php setClassErreur('objet'); ?> placeholder="Objet" />
      </div>


      <!-- Break -->
      <div class="12u$ formChamp">
        <?php setBulleErreur('message'); ?>
        <textarea name="message" id="message" placeholder="Entrer votre message" rows="6" <?php setClassErreur('message'); ?> ><?php echo isset($message)?html_entity_decode($message):''; ?></textarea>
      </div>
      <!-- Break -->
      <div class="6u$ 12u$(small) formChamp">
        <?php setBulleErreur('human'); ?>
        <input type="checkbox" id="human" name="human" <?php setClassErreur('human'); ?> >
        <label for="human">Je ne suis pas un robot.</label>
      </div>

      <div class="12u$">
        <ul class="actions">
          <li><input type="submit" name="Envoie" value="Envoie" /></li>
          <li><input type="reset" value="Effacer" class="alt" /></li>
        </ul>
      </div>
    </div>
  </form>




  <!-- carte html -->

  
    <div id="container-map">
      <div id="map"></div>
      <div id="lien-map"><a href="./carte.html">Carte Google</a></div>
    </div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-BTFgZZPQU6_Au_s9sWj1cKR00uZXErM&callback=initMap"
    async defer></script>

</section>

		<!-- Footer -->

		<?php include("./include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("./include/scripts.php"); ?>

	</body>
</html>
