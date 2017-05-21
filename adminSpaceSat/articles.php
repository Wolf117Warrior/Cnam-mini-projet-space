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
// supprime accents utf8
function str_to_noaccent($str){
    $str = preg_replace('#Ç#', 'C', $str);
    $str = preg_replace('#ç#', 'c', $str);
    $str = preg_replace('#è|é|ê|ë#', 'e', $str);
    $str = preg_replace('#È|É|Ê|Ë#', 'E', $str);
    $str = preg_replace('#à|á|â|ã|ä|å#', 'a', $str);
    $str = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $str);
    $str = preg_replace('#ì|í|î|ï#', 'i', $str);
    $str = preg_replace('#Ì|Í|Î|Ï#', 'I', $str);
    $str = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $str);
    $str = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $str);
    $str = preg_replace('#ù|ú|û|ü#', 'u', $str);
    $str = preg_replace('#Ù|Ú|Û|Ü#', 'U', $str);
    $str = preg_replace('#ý|ÿ#', 'y', $str);
    $str = preg_replace('#Ý#', 'Y', $str);
    return ($str);
}
// formate chaine utf8 bdd pour nom image 
//str_to_noaccent : supprime tous les accents (utf8)  - pregreplace : remplace tout ce qui n'est pas une lettre non accentuées ou un chiffre par un tiret "-"  
function formateNomImage($str){
  return preg_replace('/([^.a-z0-9]+)/i', '-',str_to_noaccent(html_entity_decode($str)));
}
//=========================================================
//===== init ==========
//=========================================================
if(isset($_GET["id"]))      $id = htmlentities($_GET["id"], ENT_QUOTES);  
if(isset($_GET["action"]))  $action = htmlentities($_GET["action"], ENT_QUOTES);
if(isset($_GET["selcat"]))     $selcat = htmlentities($_GET["selcat"], ENT_QUOTES);
if(isset($_GET["col"]))     $col = htmlentities($_GET["col"], ENT_QUOTES);
if(isset($_GET["tri"]))     $tri = htmlentities($_GET["tri"], ENT_QUOTES);
else $tri = 'asc';
//=========================================================
// conexion Bdd
//=========================================================
include_once("../config/ConnexionBdd.php");
//=========================================================
//===== SUPPRIMER : delete article Bdd ==========
//=========================================================
      if(isset($action)&&$action=='supprimer'&&isset($id)){  
          $suppprimer_article= $maBase->exec("DELETE FROM cnamcp09_articles WHERE ID_article='{$id}'");  
          // si succès
          if($suppprimer_article==1)
            $retourEnvoiForm = '<div class="retourEnvoiFormok">L\'article a été supprimée avec succès</div>';
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

<section id="articles" class="content-wrapper">
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


         

          <!-- messages -->
          <section id="one">
            <div class="inner">
              <h3>Liste des articles</h3>

                      <div class="12u$ formChamp">
                            <div class="select-wrapper ">
                                <select name="categorie" id="categorie" onchange="javascript:window.location='articles.php?selcat='+this.value;">
                                  
                                  <?php  //--- affichage liste catégories ---//
                                        //$result=$maBase->query('SELECT * FROM cnamcp09_categories ORDER BY LIBL_categorie DESC'); 
                                        $result=$maBase->query('SELECT  c.LIBL_categorie, c.ID_categorie, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                                                  FROM cnamcp09_categories c  LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie GROUP BY 1,2
                                                                UNION
                                                              SELECT  IFNULL(c.LIBL_categorie ,"Sans catégorie"), IFNULL(a.ID_categorie ,"sans"), COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                                                  FROM cnamcp09_categories c  RIGHT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie GROUP BY 1,2 ');
                                        $count=$result->rowCount(); ?>
                                        <option value='toutes'>Toutes les Catégories - (<?php echo $count; ?> articles)</option>
                                  <?php
                                        if($result) { 
                                          while($cat=$result->fetch()) {      ?>
                                  <option <?php echo (isset($selcat)&&$selcat==$cat['ID_categorie']?'selected':'');?> 
                                    value="<?php echo $cat['ID_categorie']; ?>"><?php echo $cat['LIBL_categorie'].'   - ('.$cat['Nb_articles'].' articles)'; ?>
                                  </option>
                                  <?php   }   }    ?>
                                </select>
                            </div>
                          </div>


            <?php // message retour formulaire envoyé
                    echo isset($retourEnvoiForm)?$retourEnvoiForm:''; ?>
              <div class="boutNouveau">
                <a href="article.php?action=nouveau" class="button small">nouveau</a>
              </div>

            </div>

            <div class="table-wrapper">
              <table>
                    <thead>
                      <tr>
                        <th></th>
                        <th><a href="articles.php?<?php echo (isset($selcat)?'selcat='.$selcat.'&':'') ?>col=titre&tri=<?php echo ($tri=='asc'?'desc':'asc'); ?>">Titre</a></th>
                        <th>Contenu</th>
                        <th><a href="articles.php?<?php echo (isset($selcat)?'selcat='.$selcat.'&':'') ?>col=date&tri=<?php echo ($tri=='asc'?'desc':'asc'); ?>">Date</a></th>
                        <th><a href="articles.php?<?php echo (isset($selcat)?'selcat='.$selcat.'&':'') ?>col=cat&tri=<?php echo ($tri=='asc'?'desc':'asc'); ?>">Catégorie</a></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
            <?php  //--------------------------------//
                   //--- affichage liste articles ---//
                  //--------------------------------// 
               $result=$maBase->query("SELECT a.*, IFNULL(c.LIBL_categorie ,'sans catégorie')  AS 'LIBL_categorie'
                FROM cnamcp09_articles a LEFT JOIN cnamcp09_categories c ON a.ID_categorie = c.ID_categorie "
              .(isset($selcat)&&$selcat=='toutes'||!isset($selcat)?'':(isset($selcat)&&$selcat=='sans'?'WHERE a.ID_categorie IS NULL':'WHERE a.ID_categorie='.$selcat))
              ." ORDER BY ".(isset($col)&&$col=='titre'?'TITRE_article':(isset($col)&&$col=='cat'?'LIBL_categorie':'DATE_article'))." ".(isset($tri)&&$tri=='asc'?'ASC':'DESC')); 



            $count=$result->rowCount() ;
                         if($result) { 
                              while($article=$result->fetch()) {  
                                $article_id = $article['ID_article'];
                                $article_titre = $article['TITRE_article'];
            ?>
                      <tr>
                        <td>
                    <?php 
                              $nom_img = formateNomImage($article_titre);
                              $img_o = './medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-o.jpg'):'');
                              $img_m = './medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-m.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-m.jpg'):'');
                              $img_p = './medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-p.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-p.jpg'):'');
                              $photo = (file_exists('../medias/'.$article_id.'-'.$nom_img.'-p.jpg')); 
                    ?>
                          <img src="<?php echo ($photo?$img_p:'../medias/no_pic.jpg'); ?>" style="width:80px;height:40px" alt="">
                        </td>
                        <td><?php echo $article_titre; ?> </td>
                        <td><?php echo $article['CONTENT_article']; ?></td>
                        <td><?php echo date_format(new DateTime($article['DATE_article']), 'd/m/Y H:i:s'); ?></td>
                        <td><?php echo $article['LIBL_categorie']; ?></td>
                        <td><a href="article.php?action=modifier&id=<?php echo $article_id; ?>" class="button small">modifier</a></td>
                        <td><a href=javascript:confirm_supprimer('l\'article&nbsp;<?php echo rawurlencode($article_titre); ?>','articles.php?action=supprimer&id=<?php echo $article_id.(isset($tri)?'&tri='.$tri:''); ?>'); class="button small">supprimer</a></td>
                      </tr>
            <?php           } 
                      }   
                      if($count==0)  echo "<tr><td colspan='6'>pas d'articles</td></tr>";
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