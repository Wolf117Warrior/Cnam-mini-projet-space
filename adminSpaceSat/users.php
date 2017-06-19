<?php 
session_start();
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
//--- redirection User non authentifié  ---//
if(!isset($_SESSION['authenticate']) ) 
  header("location:index.php");

$access = 'admin';
//------------- Déconnexion ------------//
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
if(isset($_GET["id"]))      $id = htmlentities(trim($_GET["id"]), ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities(trim($_GET["action"]), ENT_QUOTES);
// pagination 
$page = '';
if(isset($_GET["page"]))    $page = htmlentities(trim($_GET["page"]), ENT_QUOTES); 
if(isset($_GET["aff"]))     $_SESSION['aff'][preg_replace('/.php/','',basename($_SERVER['PHP_SELF']))] = htmlentities(trim($_GET["aff"]), ENT_QUOTES); 
//=========================================================
//===== post formulaire ==========
//=========================================================
if(isset($_POST['Envoie'])){ 

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : utilisateur non enregistrée</div>';

     $nom       =  htmlentities(trim($_POST['nom']),   ENT_QUOTES);
     $login     =  htmlentities(trim($_POST['login']),   ENT_QUOTES);
     $mdp       =  htmlentities(trim($_POST['mdp']),   ENT_QUOTES);

//=========================================================
//===== Vérification Erreurs post formulaire ==========
//=========================================================
     
     // Nom
     if(empty($nom))   
            $GLOBALS["erreurs"]['nom']='<b>Nom</b> obligatoire';
           // Nom
     if(empty($login))   
            $GLOBALS["erreurs"]['login']='<b>Login</b> obligatoire';
           // Nom
     if(empty($mdp))   
            $GLOBALS["erreurs"]['mdp']='<b>Mot de passe</b> obligatoire';
      elseif(strlen($mdp) < 4)
           $GLOBALS["erreurs"]['pwd']='<b>Mot de Passe</b> non valide (min 4 caractères)';   

     
//=========================================================
//===== enregistrement Bdd ==========
//=========================================================
    if(!isset($GLOBALS["erreurs"])){
      
        //=========================================================
        //===== NOUVEAU : insertion catégorie Bdd ==========
        //=========================================================
      if($_POST['Envoie']=="nouveau"){ 
          $sauvegarde_utilisateur=$maBase->exec("INSERT INTO cnamcp09_utilisateurs (LOGIN_utilisateur,MDP_utilisateur,NOM_utilisateur) 
                                                              VALUES ('{$login}',md5('{$mdp}'),'{$nom}')");
          $id = $maBase->lastInsertId();
          $action = 'modifier';
          // si succès
          if($sauvegarde_utilisateur==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'utilsateur a été ajoutée avec succès</div>';
        //=========================================================
        //===== MODIFIER : update catégorie Bdd ==========
        //=========================================================
      }else if($_POST['Envoie']=="enregistrer"){    
          // insertion message Bdd
          $sauvegarde_utilisateur=$maBase->exec("UPDATE cnamcp09_utilisateurs 
                                                        SET LOGIN_utilisateur='{$login}', MDP_utilisateur=md5('{$mdp}'), NOM_utilisateur='{$nom}' 
                                                            WHERE ID_utilisateur='{$id}'");
          // si succès
          if($sauvegarde_utilisateur==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'utilsateur a été modifiée avec succès</div>';
        }
    }
}
//=========================================================
//===== SUPPRIMER : delete catégorie Bdd ==========
//=========================================================
      if(isset($action)&&$action=='supprimer'&&isset($id)){  
          $suppprimer_utilisateur= $maBase->exec("DELETE FROM cnamcp09_utilisateurs WHERE ID_utilisateur='{$id}'");  
          // si succès
          if($suppprimer_utilisateur==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'utilsateur a été supprimée avec succès</div>';
      }
//=========================================================
//===== Bdd : Catégorie sélectionnée ==========
//=========================================================
if(isset($action)&&$action=='modifier'&&isset($id)){
            $result_modif=$maBase->query('SELECT * FROM cnamcp09_utilisateurs WHERE ID_utilisateur='.$id); 
            if($result_modif) {  
              $user = $result_modif->fetch();
              $nom = $user['NOM_utilisateur'];
              $login = $user['LOGIN_utilisateur'];
              $mdp = $user['MDP_utilisateur'];
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

<section id="utilisateurs" class="content-wrapper">
<div class="inner">
            
            <section id="one" class="row">
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>


                       <!-- navigation -->
            <?php include("./include/navbar.php"); ?>

          <!-- messages -->
          <section id="one">
            <div class="inner">
              <h3>Liste des utilisateurs</h3>

                    <div class="newuser">
                          <?php // message retour formulaire envoyé
                                  echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                            <form name="utilisateurs" method="post" action="users.php<?php echo isset($action)?'?action='.$action:''; ?><?php echo isset($id)?'&id='.$id:''; ?>">
                              <div class="user">
                                <div class="formChamp">
                                  <?php setBulleErreur('nom'); ?>
                                  <input type="text" name="nom" id="nom" value="<?php echo isset($nom)?html_entity_decode($nom):''; ?>" <?php setClassErreur('nom'); ?> placeholder="nom" />
                                </div>
                                <div class="formChamp">
                                  <?php setBulleErreur('login'); ?>
                                  <input type="text" name="login" id="login" value="<?php echo isset($login)?html_entity_decode($login):''; ?>" <?php setClassErreur('login'); ?> placeholder="login" />
                                </div>
                                <div class="formChamp">
                                  <?php setBulleErreur('mdp'); ?>
                                  <input type="text" name="mdp" id="mdp" value="<?php echo isset($mdp)?html_entity_decode($mdp):''; ?>" <?php setClassErreur('mdp'); ?> placeholder="mot de passe" />
                                </div>

                                <div class="boutChamp">
                                    <input type="submit" name="Envoie" value="<?php echo (isset($action)&&$action=='modifier')?'enregistrer':'nouveau'; ?>" />
                                </div>
                              </div>
                            </form>

                        </div>
            </div>

            <div class="table-wrapper liste-users">
              <table>
                    <thead>
                      <tr>
                        <th>Nom</th>
                        <th>Login</th>
                        <th>Mot de passe</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
            <?php  //--------------------------------//
                   //--- affichage liste articles ---//
                  //--------------------------------// 
                        /** Initialisation pagination **/
        // total des articles
        $result_total_articles = $maBase->query("SELECT COUNT(*) AS total FROM cnamcp09_utilisateurs");
        $tab_total_articles=$result_total_articles->fetch();
        $total_articles=$tab_total_articles[0];

        // pagination
        $pagination = paginationBdd($total_articles,$page);

                $result=$maBase->query("SELECT * FROM cnamcp09_utilisateurs ORDER BY ID_utilisateur ASC LIMIT {$pagination['offset']},{$pagination['limit']}"); 
                $count=$result->rowCount() ;
                         if($result) { 
                              while($user=$result->fetch()) {  
                                $user_id = $user['ID_utilisateur'];
                                $user_nom = html_entity_decode($user['NOM_utilisateur']);
            ?>
                      <tr>
                        <td width="350"><?php echo $user_nom; ?></td>
                        <td width="350"><?php echo html_entity_decode($user['LOGIN_utilisateur']); ?></td>
                        <td width="350"><?php echo html_entity_decode($user['MDP_utilisateur']); ?></td>
                        <td><a href="users.php?action=modifier&id=<?php echo $user_id; ?>" class="button small">modifier</a></td>
                        <td><a href="javascript:confirm_supprimer('l\'utilisateur&nbsp;<?php echo rawurlencode(html_entity_decode($user_nom)); ?>','users.php?action=supprimer&id=<?php echo $user_id; ?>');" class="button small">supprimer</a></td>
                      </tr>
            <?php           } 
                      }   
                      if($count==0)  echo "<tr><td colspan='5'>pas d'utilisateurs enregistrés</td></tr>";
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