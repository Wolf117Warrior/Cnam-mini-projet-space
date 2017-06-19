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
// fonctions
//=========================================================
include_once("../config/fonctions.php");
//=========================================================
//===== init ==========
//=========================================================
if(isset($_GET["id"]))      $id = htmlentities(trim($_GET["id"]), ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities(trim($_GET["action"]), ENT_QUOTES);

//=========================================================
//===== post formulaire ==========
//=========================================================
if(isset($_POST['Envoie'])){ 

  $retourEnvoiForm = '<div class="retourEnvoiForm">Erreur : article non enregistrée</div>';

     $categorie       =  htmlentities(trim($_POST['categorie']),   ENT_QUOTES);
     $titre           =  htmlentities(trim($_POST['titre']),   ENT_QUOTES);
     $contenu         =  htmlentities(trim($_POST['contenu']),   ENT_QUOTES);

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
          if($sauvegarde_article==1){
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été modifiée avec succès</div>';
            // renommage photo
            foreach (glob('../medias/'.$id."*.jpg") as $filename) {
              $nouveau_nom = '../medias/'.$id.'-'.formateNomImage($titre.substr($filename, -6));
              if(!file_exists($nouveau_nom))
                rename($filename , $nouveau_nom);
            }
          }
              
      }
//=========================================================
//===== gestion upload photo  ==========
//=========================================================
     if(isset($id)&&isset($_FILES['photo'])&&!empty($_FILES['photo']['name']))   {  //echo'<pre>';var_dump($_FILES['photo']);echo'</pre>';
     //echo ini_get('upload_max_filesize'), ", " , ini_get('post_max_size');
        $image_accept = array('image/jpeg','image/png');
        //str_to_noaccent : supprime tous les accents (utf8)  - pregreplace : remplace tout ce qui n'est pas une lettre non accentuées ou un chiffre par un tiret "-" 
        $nom = formateNomImage($titre);
        $extension = strtolower(substr($_FILES['photo']['name'], strrpos($_FILES['photo']['name'], ".")));

        $nom = $id.'-'.$nom;
        $nom_o = $nom.'-o';
        $nom_m = $nom.'-m';
        $nom_p = $nom.'-p';

        // si téléchargée
        if(is_uploaded_file($_FILES['photo']['tmp_name']))
          // vérification format image  
          if(!in_array( $_FILES['photo']['type'] , $image_accept))
            $GLOBALS["erreurs"]['photo']='<b>Photo</b> format non valide (jpg ou png)';
          // vérificatio poids iage
          else if($_FILES['photo']['size']>2000000)
            $GLOBALS["erreurs"]['photo']='<b>Photo</b> max 2Mo';
          // transfert dossier média
          else if(!move_uploaded_file($_FILES['photo']['tmp_name'], '../medias/'.$nom.$extension))
            $GLOBALS["erreurs"]['photo']='Erreur <b>Photo</b> non uploadée';
          else {    

            //redimensionnement : centrage vertical           
            $dimensions = getimagesize('../medias/'.$nom.$extension); 
            
            $ratio_redimentionnement = ($dimensions[0]/576);
            $nouvellelargeur = 576;
            $nouvellehauteur = $dimensions[1]/$ratio_redimentionnement;
            
            $facteurReduction = $dimensions[1]/$nouvellehauteur;
            $facteurAugmentation = $nouvellehauteur/$dimensions[1];

            $hauteurproportion = 200*$facteurReduction;
            $ySource = $dimensions[1]/2 - $hauteurproportion/2;
            
            $source =  imagecreatefromstring (file_get_contents('../medias/'.$nom.$extension));
            imagejpeg ($source , '../medias/'.$nom_o.'.jpg' , 100);

            $nouvelle_image = imagecreatetruecolor ( 576 , 200);
            //NouvelleImage, ImageDepart, XDestination, YDestination, XSource, YSource, NouvelleLargeur, NouvelleHauteur, LargeurImageDepart, HauteurImageDepart
            imagecopyresampled($nouvelle_image , $source , 0 , 0 , 0 , $ySource , 576 , $nouvellehauteur , $dimensions[0] , $dimensions[1] );
            imagejpeg ($nouvelle_image , '../medias/'.$nom_m.'.jpg' , 100);

            $source =  imagecreatefromstring (file_get_contents('../medias/'.$nom_m.'.jpg'));
            $nouvelle_image = imagecreatetruecolor ( 360 , 125);
            //NouvelleImage, ImageDepart, XDestination, YDestination, XSource, YSource, NouvelleLargeur, NouvelleHauteur, LargeurImageDepart, HauteurImageDepart
            imagecopyresampled($nouvelle_image , $source , 0 , 0 , 0 , 0 , 360 , 125 , 576 , 200 );
            imagejpeg ($nouvelle_image , '../medias/'.$nom_p.'.jpg' , 100);

            imagedestroy($source);
            imagedestroy($nouvelle_image);
            unlink('../medias/'.$nom.$extension);

            // insertion photo Bdd
            $sauvegarde_article=$maBase->exec("UPDATE cnamcp09_articles SET PHOTO_article='1' WHERE ID_article='{$id}'");

        }
     }
  }
}
//=========================================================
//===== suprresion photos ==========
//=========================================================
if(isset($action)&&$action=='supprimer'&&isset($id)){
  foreach (glob('../medias/'.$id."*.jpg") as $filename) {
      unlink($filename);
      // supression photo Bdd
      $sauvegarde_article=$maBase->exec("UPDATE cnamcp09_articles SET PHOTO_article='0' WHERE ID_article='{$id}'");
  }
  $action='modifier';           
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

<section id="article" class="content-wrapper">
    <div class="inner">



            <section id="one" class="row">
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>

          <?php include("./include/navbar.php"); ?>

          <!-- catégories -->
          <section id="one">
            <div class="inner">
              <h3><?php echo (isset($action)&&$action=='nouveau'?'Nouveau':(isset($action)&&$action=='modifier'?'Modifer':'')); ?> Article</h3>

            </div>

          </section>


          <section id="one">
            <div class="inner">

              <?php // message retour formulaire envoyé
                    echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                <form name="article" method="post" enctype="multipart/form-data" action="article.php<?php echo isset($action)?'?action='.$action:''; ?><?php echo isset($id)?'&id='.$id:''; ?>">
                    <div class="row uniform">
                          <div class="12u$(xsmall) formChamp formArticle">
                            <span class="image fit">
                            <?php 
                              $nom_img = formateNomImage($titre);
                              $img_o = '../medias/'.$id.'-'.$nom_img.'-o.jpg?v='.(file_exists('../medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('../medias/'.$id.'-'.$nom_img.'-o.jpg'):'');
                              $img_m = '../medias/'.$id.'-'.$nom_img.'-m.jpg?v='.(file_exists('../medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('../medias/'.$id.'-'.$nom_img.'-m.jpg'):'');
                              $img_p = '../medias/'.$id.'-'.$nom_img.'-p.jpg?v='.(file_exists('../medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('../medias/'.$id.'-'.$nom_img.'-p.jpg'):'');
                              $photo = (file_exists('../medias/'.$id.'-'.$nom_img.'-o.jpg')); 
                            ?>
                              <img src="<?php echo ($photo?$img_p:'../images/no_pic.jpg'); ?>" style="float:left;max-width:360px;margin-right:10px" alt="">
                              <img src="<?php echo ($photo?$img_m:'../images/no_pic.jpg'); ?>" style="float:left;max-width:570px;margin-right:10px" alt="">
                              <img src="<?php echo ($photo?$img_o:'../images/no_pic.jpg'); ?>" style="max-width:343px;" alt="">
                            </span>
                          </div>
                          <span style="clear:both"></span>
                          <div class="12u$(xsmall) formChamp formArticle supprimer">
                            <a href="javascript:confirm_supprimer('l\'image','article.php?action=supprimer&id=<?php echo $id; ?>');" class="button small">supprimer les images</a>                     
                          </div>
                           <div class="12u$(xsmall) formChamp formArticle choix-photo">
                              <?php setBulleErreur('photo'); ?>
                              <input class="input-file" name="photo" type="file" <?php setClassErreur('photo'); ?>></li>
                          </div>

                          <div class="12u$ formChamp">
                            <?php setBulleErreur('categorie'); ?>
                            <div class="select-wrapper ">
                                <select name="categorie" id="categorie" <?php setClassErreur('categorie'); ?>>
                                  <option value='null'>Catégorie</option>
                                  <?php //===================================// 
                                        //=== affichage liste catégories ====//
                                        //===================================// 
                                        $result=$maBase->query('SELECT * FROM cnamcp09_categories ORDER BY LIBL_categorie DESC'); 
                                        $count=$result->rowCount(); 
                                        if($result) { 
                                          while($cat=$result->fetch()) {      ?>
                                  <option <?php echo (isset($categorie)&&$categorie==$cat['ID_categorie']?'selected':'');?> 
                                    value="<?php echo $cat['ID_categorie']; ?>"><?php echo html_entity_decode($cat['LIBL_categorie']); ?>
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
                                <input type="submit" name="Envoie" value="<?php echo (isset($action)&&$action=='modifier')?'enregistrer':'nouveau'; ?>" />
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