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
if(isset($_GET["id"]))      $id = htmlentities($_GET["id"], ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities($_GET["action"], ENT_QUOTES);

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
     
//=========================================================
//===== enregistrement Bdd ==========
//=========================================================
    if(!isset($GLOBALS["erreurs"])){
      
        //=========================================================
        //===== NOUVEAU : insertion article Bdd ==========
        //=========================================================
      if($_POST['Envoie']=="nouveau"){  
          
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été ajoutée avec succès</div>';
        //=========================================================
        //===== MODIFIER : update article Bdd ==========
        //=========================================================
      }else if($_POST['Envoie']=="enregistrer"){    
          // insertion message Bdd
          
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été modifiée avec succès</div>';
            // renommage photo
            foreach (glob('../medias/'.$id."*.jpg") as $filename) {
              $nouveau_nom = '../medias/'.$id.'-'.formateNomImage($titre.substr($filename, -6));
              if(!file_exists($nouveau_nom))
                rename($filename , $nouveau_nom);
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
  }
  $action='modifier';           
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

                <!-- navigation -->
                  <nav id="nav" class="6u">
                    <a href="categories.php">Catégories</a>
                    <a href="articles.php">Articles</a>
                    <a href="portfolio.php">Portfolio</a>
                    <a href="messages.php">Messages</a>
                  </nav>
                
                 <!-- Connexion utilisateur : lien déconnexion -->
                <div id="connexion" class="6u">Vous êtes connecté : <?php echo $_SESSION['authenticate']['user']; ?> - <a href='?deconnexion'>se déconnecter</a></div>

          </section>

          <!-- catégories -->
          <section id="portfolio" class="content-wrapper">

<div class="inner">

  <h4 class="center">Portofolio articles</h4>
  <div class="box alt">
    <div class="row 50% uniform">
        <?php //--------------------------------//
        //--- affichage liste articles ---//
        //--------------------------------// 
        $result=$maBase->query("SELECT ID_article,TITRE_article  FROM cnamcp09_articles WHERE ID_categorie IS NOT NULL ORDER BY DATE_article DESC"); 
            $count=$result->rowCount() ;
                         if($result) {  
                              while($article=$result->fetch()) { 
                                $article_id = $article['ID_article'];
                                $article_titre = $article['TITRE_article'];
            ?>
          <?php 
                  $nom_img = formateNomImage($article_titre);
                  $img_o = '../medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.(file_exists('../medias/'.$article_id.'-'.$nom_img.'-o.jpg')?filemtime('../medias/'.$article_id.'-'.$nom_img.'-o.jpg'):'');
                  $img_m = '../medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.(file_exists('../medias/'.$article_id.'-'.$nom_img.'-m.jpg')?filemtime('../medias/'.$article_id.'-'.$nom_img.'-m.jpg'):'');
                  $img_p = '../medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.(file_exists('../medias/'.$article_id.'-'.$nom_img.'-p.jpg')?filemtime('../medias/'.$article_id.'-'.$nom_img.'-p.jpg'):'');
                  $photo = (file_exists('../medias/'.$article_id.'-'.$nom_img.'-o.jpg')); 
          ?>
          <div class="4u">
            <span class="image fit">
              <img src="<?php echo ($photo?$img_m:'./images/no_pic.jpg'); ?>" alt="" />
            </span>
            <div class="12u$(xsmall) formChamp formArticle supprimer">
                <a href="javascript:confirm_supprimer('l\'image','article.php?action=supprimer&id=<?php echo $id; ?>');" class="button small">supprimer</a>
            </div>
        </div>


              <?php           } 
                      }   
                      if($count==0)  echo "<div class='row'>pas de photos</div>";
              ?>
    </div>
  </div>

</div>

<div class="inner">

  <h4 class="center">Portofolio de satellite</h4>
  <div class="inner modif-portfolio">

              <?php // message retour formulaire envoyé
                    echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                <form name="article" method="post" enctype="multipart/form-data" action="article.php<?php echo isset($action)?'?action='.$action:''; ?>
                <?php echo isset($id)?'&id='.$id:''; ?>">
                    <div class="row uniform">
                           <div class="12u$(xsmall) formChamp formArticle choix-photo">
                              <?php setBulleErreur('photo'); ?>
                              <input class="input-file" name="photo" type="file" <?php setClassErreur('photo'); ?>></li>
                          </div>     
                  </div>
              </form>

            </div>
  <div class="box alt">
    <div class="row 50% uniform">

      <div class="4u"><span class="image fit"><img src="../images/portofolio/satellite/satellite-103.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="../images/portofolio/satellite/satellite-103418_1920.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="../images/portofolio/satellite/satellite-1030782_1920.jpg" alt="" /></span></div>
      <!-- Break -->
      <div class="4u"><span class="image fit"><img src="../images/portofolio/satellite/telescope-63119_1280.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="../images/portofolio/satellite/8537258881_35bce8fa2e_o.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="../images/portofolio/satellite/Facebook-Satellite.jpg" alt="" /></span></div>

    </div>
  </div>

</div>

<div class="inner">

  <h4 class="center">Portofolio de Agence</h4>
  <div class="inner modif-portfolio">

              <?php // message retour formulaire envoyé
                    echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
                <form name="article" method="post" enctype="multipart/form-data" action="article.php<?php echo isset($action)?'?action='.$action:''; ?>
                <?php echo isset($id)?'&id='.$id:''; ?>">
                    <div class="row uniform">
                           <div class="12u$(xsmall) formChamp formArticle choix-photo">
                              <?php setBulleErreur('photo'); ?>
                              <input class="input-file" name="photo" type="file" <?php setClassErreur('photo'); ?>></li>
                          </div>     
                  </div>
              </form>

            </div>
  <div class="box alt">
    <div class="row 50% uniform">

      <div class="4u"><span class="image fit"><img src="../images/portofolio/agence/ESA_Space_Operations_Centre.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="../images/portofolio/agence/nasa-space-walk.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="../images/portofolio/agence/spaceX.jpg" alt="" /></span></div>
    </div>
  </div>

</div>


</section>









    <!-- Footer -->

    <?php include("../include/footer.php"); ?>

    <!-- Scripts -->

    <?php include("../include/scripts.php"); ?>

  </body>
</html>