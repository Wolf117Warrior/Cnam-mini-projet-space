<?php 
session_start();
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
// Fonctions
//=========================================================
include_once("../config/fonctions.php");
//=========================================================
//===== init ==========
//=========================================================
if(isset($_GET["id"]))      $id = htmlentities($_GET["id"], ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities($_GET["action"], ENT_QUOTES);
if(isset($_GET["tri"]))     $tri = htmlentities($_GET["tri"], ENT_QUOTES);
else $tri = 'asc';
//=========================================================
//===== post formulaire ==========
//=========================================================
if(isset($_POST['Envoie'])){ 

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : catégorie non enregistrée</div>';

     $categorie       =  htmlentities($_POST['categorie'],   ENT_QUOTES);

//=========================================================
//===== Vérification Erreurs post formulaire ==========
//=========================================================
     
     // Nom
     if(empty($categorie))   
            $GLOBALS["erreurs"]['categorie']='<b>Catégorie</b> obligatoire';
     
//=========================================================
//===== enregistrement Bdd ==========
//=========================================================
    if(!isset($GLOBALS["erreurs"])){
      
        //=========================================================
        //===== NOUVEAU : insertion catégorie Bdd ==========
        //=========================================================
      if($_POST['Envoie']=="nouveau"){ 
          $sauvegarde_categorie=$maBase->exec("INSERT INTO cnamcp09_categories (LIBL_categorie) VALUES ('{$categorie}')");
          $id = $maBase->lastInsertId();
          $action = 'modifier';
          // si succès
          if($sauvegarde_categorie==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">La catégorie a été ajoutée avec succès</div>';
        //=========================================================
        //===== MODIFIER : update catégorie Bdd ==========
        //=========================================================
      }else if($_POST['Envoie']=="enregistrer"){    
          // insertion message Bdd
          $sauvegarde_categorie=$maBase->exec("UPDATE cnamcp09_categories SET LIBL_categorie='{$categorie}' WHERE ID_categorie='{$id}'");
          // si succès
          if($sauvegarde_categorie==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">La catégorie a été modifiée avec succès</div>';
        }
    }
}
//=========================================================
//===== SUPPRIMER : delete catégorie Bdd ==========
//=========================================================
      if(isset($action)&&$action=='supprimer'&&isset($id)){  
          $suppprimer_categorie= $maBase->exec("DELETE FROM cnamcp09_categories WHERE ID_categorie='{$id}'");  
          // si succès
          if($suppprimer_categorie==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">La catégorie a été supprimée avec succès</div>';
      }
//=========================================================
//===== Bdd : Catégorie sélectionnée ==========
//=========================================================
if(isset($action)&&$action=='modifier'&&isset($id)){
            $result_modif=$maBase->query('SELECT * FROM cnamcp09_categories WHERE ID_categorie='.$id); 
            if($result_modif) {  
              $cat = $result_modif->fetch();
              $categorie = $cat['LIBL_categorie'];
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

         
<!-- NAVBAR -->


<?php include("./include/navbar.php"); ?>
                
              <section id="one" class="row">  
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>

          <!-- catégories -->
          <section id="one">
            <div class="inner">
              <h3>Liste des Catégories</h3>

                      <div class="newcat">
                          <?php // message retour formulaire envoyé
                                  echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                            <form name="categories" method="post" action="categories.php<?php echo isset($action)?'?action='.$action:''; ?><?php echo isset($id)?'&id='.$id:''; ?>">
                              <div class="test">
                                <div class="formChamp">
                                
                                  <?php setBulleErreur('categorie'); ?>
                                  <input type="text" name="categorie" id="categorie" value="<?php echo isset($categorie)?html_entity_decode($categorie):''; ?>"
                                  <?php setClassErreur('nom'); ?> 
                                  placeholder="<?php echo (isset($_GET['action'])&&$_GET['action']=='modifier')?'catégorie':'nouvelle catégorie'; ?> " />
                                </div>

                                <div class="boutChamp">
                                    <input type="submit" name="Envoie" value="<?php echo (isset($action)&&$action=='modifier')?'enregistrer':'nouveau'; ?>" />
                                </div>
                              </div>
                            </form>

                        </div>

            </div>

            <?php  //--------------------------------//
                   //--- affichage liste catégories ---//
                  //--------------------------------//
            $result=$maBase->query('SELECT  c.LIBL_categorie, c.ID_categorie, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                        FROM cnamcp09_categories c  LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie GROUP BY 1,2
                                    UNION
                                    SELECT  IFNULL(c.LIBL_categorie ,"sans catégorie"), a.ID_categorie, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                        FROM cnamcp09_categories c  RIGHT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie GROUP BY 1,2');
            $count=$result->rowCount() ;
                                ?>

            <div>
              <table>
                    <thead>
                      <tr>
                        <th><a href="categories.php?tri=<?php echo ($tri=='asc'?'desc':'asc'); ?>">Catégories (<?php echo $count; ?>)</a></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                            if($result) { 
                              while($categorie=$result->fetch()) {  
                                  $catid = $categorie['ID_categorie'];
                                  $cat = $categorie['LIBL_categorie'];
                                  $total = $categorie['Nb_articles'];

                                if($catid==null) {    
                      ?>
                                  <tr>
                                    <td class="table_row"><?php echo '<b>'.$cat.'</b>   - ('.$total.' articles)'; ?> </td>
                                    <td></td>
                                    <td></td>
                                  </tr>
                      <?php } else { ?>
                      <tr>
                        <td class="table_row"><?php echo '<b>'.$cat.'</b>   - ('.$total.' articles)'; ?> </td>
                        <td><a href="?action=modifier&id=<?php echo $catid; ?>" class="button small">modifier</a></td>
                        <td><a href=javascript:confirm_supprimer('la&nbsp;catégorie&nbsp;<?php echo rawurlencode($cat); ?>','categories.php?action=supprimer&id=<?php echo $catid; ?>'); class="button small">supprimer</a></td>
                      </tr>
            <?php           } 
                          }
                      }   
                      if($count==0)  echo "<tr><td>pas de catégories</td></tr>";
              ?>
                      
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
