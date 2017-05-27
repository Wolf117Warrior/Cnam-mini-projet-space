<?php 
session_start();
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
//============================================================
//------------- redirection User non authentifié ------------//
//============================================================ 
if(!isset($_SESSION['authenticate']) ) 
  header("location:index.php");
$access = 'admin';
//=======================================
//------------- Déconnexion ------------//
//=======================================
if(isset($_GET['deconnexion'])) :
  unset($_SESSION['authenticate']);
        session_destroy();
        header('location:index.php');
endif ;
//=========================================================
// conexion Bdd
//=========================================================
include_once("../config/ConnexionBdd.php");
//=========================================================
// fonctions
//=========================================================
include_once("../config/fonctions.php");
?><!DOCTYPE HTML>
<!--
	Theory by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>

<!-- Head -->

   <?php include("../include/head.php"); ?>

	<body class="administration">

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
            
            <section id="one" class="row">

<!-- NAVBAR -->


<?php include("./include/navbar.php"); ?>
                
              <section id="one" class="row">  
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>


         

          <!-- messages -->
          <section id="one">
            <div class="inner"><h3>Messages du formulaire de contact</h3></div>

            <div class="table-wrapper">
              <table>
                    <thead>
                      <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Objet</th>
                        <th>Message</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
            <?php  //--------------------------------//
                   //--- affichage liste messages ---//
                  //--------------------------------//
            $result=$maBase->query('SELECT * FROM cnamcp09_messages ORDER BY DATE_message DESC'); 
                         if($result) { 
                              while($message=$result->fetch()) {  ?>

                      <tr>
                        <td><?php echo $message['NOM_message']; ?></td>
                        <td><a href="mailto:<?php echo $message['EMAIL_message']; ?>"><?php echo $message['EMAIL_message']; ?></a></td>
                        <td><?php echo $message['OBJET_message']; ?></td>
                        <td width="600">
                          <div>
                          <div id="message_off" onclick="openMessage(this)"><?php echo tronqueTexte($message['TEXT_message'],200); ?></div>
                          <div style="display:none" id="message_on" onclick="closeMessage(this)"><?php echo $message['TEXT_message']; ?></div>
                        </div>
                        </td>
                        <td><?php echo date_format(new DateTime($message['DATE_message']), 'd/m/Y H:i:s'); ?></td>
                      </tr>
            <?php } }  ?>
                      
                    </tbody>
                  </table>
                </div>

          </section>

</section>

		<!-- Footer -->

		<?php include("../include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("../include/scripts.php"); ?>

	</body>
</html>
