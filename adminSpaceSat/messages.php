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
//=========================================================
//===== init ==========
//=========================================================
// pagination 
$page = '';
if(isset($_GET["page"]))    $page = htmlentities(trim($_GET["page"]), ENT_QUOTES); 
if(isset($_GET["aff"]))     $_SESSION['aff'][preg_replace('/.php/','',basename($_SERVER['PHP_SELF']))] = htmlentities(trim($_GET["aff"]), ENT_QUOTES); 
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
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>


            <!-- navigation -->
  <?php include("./include/navbar.php"); ?>

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
            /** Initialisation pagination **/
        // total des articles
        $result_total_articles = $maBase->query("SELECT COUNT(*) AS total FROM cnamcp09_messages");
        $tab_total_articles=$result_total_articles->fetch();
        $total_articles=$tab_total_articles[0];
        // pagination
        $pagination = paginationBdd($total_articles,$page);

            $result=$maBase->query("SELECT * FROM cnamcp09_messages ORDER BY DATE_message DESC LIMIT {$pagination['offset']},{$pagination['limit']}"); 
            $count=$result->rowCount();
                         if($result) { 
                              while($message=$result->fetch()) {  ?>

                      <tr>
                        <td><?php echo html_entity_decode($message['NOM_message']); ?></td>
                        <td><a href="mailto:<?php echo html_entity_decode($message['EMAIL_message']); ?>"><?php echo html_entity_decode($message['EMAIL_message']); ?></a></td>
                        <td><?php echo html_entity_decode($message['OBJET_message']); ?></td>
                        <td width="600">
                          <div>
                          <div id="message_off" onclick="openMessage(this)"><?php echo tronqueTexte(html_entity_decode($message['TEXT_message']),200); ?></div>
                          <div style="display:none" id="message_on" onclick="closeMessage(this)"><?php echo html_entity_decode($message['TEXT_message']); ?></div>
                        </div>
                        </td>
                        <td><?php echo date_format(new DateTime($message['DATE_message']), 'd/m/Y H:i:s'); ?></td>
                      </tr>
            <?php } }  
                  if($count==0)  echo "<tr><td colspan='5'>pas de messages</td></tr>";
            ?>
                      
                    </tbody>
                  </table>
                </div>

          </section>
<?php 
//---------------------//
//----  Pagination ----//
//---------------------//
echo '<p class="pagination">'.$pagination['pagination'].'</p>';
?>
</section>

		<!-- Footer -->

		<?php include("../include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("../include/scripts.php"); ?>

	</body>
</html>