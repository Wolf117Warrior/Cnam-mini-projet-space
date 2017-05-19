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
//Affichage erreur validation formulaire
//=========================================================
function setClassErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'class="error"':'');  
}
function setBulleErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'<div id="bulleErreur" class="bulleErreur"><span><b>'.$GLOBALS["erreurs"][$champs].'</b></span></div>':'');  
}
//=========================================================
//===== init ==========
//=========================================================
if(isset($_GET["id"]))      $id = htmlentities($_GET["id"], ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities($_GET["action"], ENT_QUOTES);
//conexion Bdd
include_once("../config/ConnexionBdd.php");
//=========================================================
//===== post formulaire ==========
//=========================================================
if(isset($_POST['Envoie'])){ 

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : article non enregistrée</div>';

     $categorie       =  htmlentities($_POST['categorie'],   ENT_QUOTES);
     $titre           =  htmlentities($_POST['titre'],   ENT_QUOTES);
     $contenu         =  htmlentities($_POST['contenu'],   ENT_QUOTES);

//=========================================================
//===== Vérification Erreurs post formulaire ==========
//=========================================================
     
     // Catégorie
     //if(empty($categorie))   
       //     $GLOBALS["erreurs"]['categorie']='<b>Catégorie</b> obligatoire';

     // Titre
     if(empty($titre))   
            $GLOBALS["erreurs"]['titre']='<b>Titre</b> obligatoire';

     // Contenu
     if(empty($contenu))   
            $GLOBALS["erreurs"]['contenu']='<b>Contenu</b> obligatoire';
     
//=========================================================
//===== enregistrement Bdd ==========
//=========================================================
    if(!isset($GLOBALS["erreurs"])){
      
        //=========================================================
        //===== NOUVEAU : insertion article Bdd ==========
        //=========================================================
      if($_POST['Envoie']=="nouveau"){  
          $sauvegarde_article=$maBase->exec("INSERT INTO cnamcp09_articles (TITRE_article, CONTENT_article, DATE_article, ID_categorie) 
                       VALUES ('{$titre}', '{$contenu}', NOW(), ".($categorie==null?null:$categorie).")");
          $id = $maBase->lastInsertId();
          $action = 'modifier';
          // si succès
          if($sauvegarde_article==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été ajoutée avec succès</div>';
        //=========================================================
        //===== MODIFIER : update article Bdd ==========
        //=========================================================
      }else if($_POST['Envoie']=="enregistrer"){    
          // insertion message Bdd
          $sauvegarde_article=$maBase->exec("UPDATE cnamcp09_articles 
            SET TITRE_article='{$titre}', CONTENT_article='{$contenu}', ID_categorie=".($categorie==null?null:$categorie)."
              WHERE ID_article='{$id}'");
          // si succès
          if($sauvegarde_article==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été modifiée avec succès</div>';
        }
    }
}

//=========================================================
//===== Bdd : article sélectionnée ==========
//=========================================================
if(isset($action)&&$action=='modifier'&&isset($id)){
            $result_modif=$maBase->query('SELECT * FROM cnamcp09_articles WHERE ID_article='.$id); 
            if($result_modif) {  
              $art = $result_modif->fetch();
              $titre = $art['TITRE_article'];
              $contenu = $art['CONTENT_article'];
              $date = $art['DATE_article'];
              $categorie = $art['ID_categorie'];
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

</section id="contact" class="content-wrapper">
<div class="inner">

            
            <section id="one" class="row">

                <!-- navigation -->
                  <nav id="nav" class="6u">
                    <a href="categories.php">Catégories</a>
                    <a href="articles.php">Articles</a>
                    <a href="index.php">Portofolio</a>
                    <a href="messages.php">Messages</a>
                  </nav>
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>

          <!-- catégories -->
          <section id="one">
            <div class="inner">
              <h3><?php echo (isset($action)&&$action=='nouveau'?'Nouveau':(isset($action)&&$action=='modifier'?'Modifer':'')); ?> Article</h3>

            </div>

          </section>


          <section id="one">
            <div class="inner article">

              <?php // message retour formulaire envoyé
                    echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                <form name="article" method="post" action="article.php<?php echo isset($action)?'?action='.$action:''; ?><?php echo isset($id)?'&id='.$id:''; ?>">
                    <div class="row uniform">
                      <div class="12u$(xsmall) formChamp formArticle">
                        <span class="image fit"><img src="../images/pic01.jpg" style="width:400px" alt=""></span>
                        <input type="file">
                      </div>
                          <div class="12u$ formChamp">
                            <?php setBulleErreur('categorie'); ?>
                            <div class="select-wrapper ">
                                <select name="categorie" id="categorie" <?php setClassErreur('categorie'); ?>>
                                  <option value='null'>Catégorie</option>
                                  <?php  //--- affichage liste catégories ---//
                                        $result=$maBase->query('SELECT * FROM cnamcp09_categories ORDER BY LIBL_categorie DESC'); 
                                        $count=$result->rowCount(); 
                                        if($result) { 
                                          while($cat=$result->fetch()) {      ?>
                                  <option <?php echo (isset($categorie)&&$categorie==$cat['ID_categorie']?'selected':'');?> 
                                    value="<?php echo $cat['ID_categorie']; ?>"><?php echo $cat['LIBL_categorie']; ?>
                                  </option>
                                  <?php   }   }    ?>
                                </select>
                            </div>
                          </div>

                            <div class="12u$(xsmall) formChamp formArticle">
                              <?php setBulleErreur('titre'); ?>
                              <input type="text" name="titre" id="titre" value="<?php echo isset($titre)?html_entity_decode($titre):''; ?>" <?php setClassErreur('titre'); ?> placeholder="Titre" />
                            </div>
  
                            <!-- Break -->
                            <div class="12u$ formChamp">
                              <?php setBulleErreur('contenu'); ?>
                              <textarea name="contenu" id="contenu" placeholder="Entrer votre contenu" rows="6" <?php setClassErreur('contenu'); ?> ><?php echo isset($contenu)?html_entity_decode($contenu):''; ?></textarea>
                            </div>

                            <div class="12u$">
                                <input type="submit" name="Envoie" value="<?php echo (isset($action)&&$action=='modifier')?enregistrer:nouveau; ?>" />
                            </div>
                  </div>
              </form>

            </div>

          </section>


</section>

    <!-- Footer -->

    <?php include("../include/footer.php"); ?>

    <!-- Scripts -->

    <?php include("../include/scripts.php"); ?>

  </body>
</html>