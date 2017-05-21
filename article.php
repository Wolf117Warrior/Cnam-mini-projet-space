<?php  
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
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
//=========================================================
//===== init ==========
//=========================================================
if(isset($_GET["id"]))      $id = htmlentities($_GET["id"], ENT_QUOTES);  
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


<section id="one" class="wrapper">

  <div class="inner">
    <nav id="nav">
      <a href="index.php">Accueil</a>
      <a href="blog.php">Blog</a>
      <a href="portofolio.php">Portofolio</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</section>

<section id="content">

<section id="main" class="article-wrapper">

  <div class="inner taille1">

    <?php
//=========================================================
//===== Bdd : article sélectionnée ==========
//=========================================================
if(isset($id)){
            //$result_modif=$maBase->query('SELECT * FROM cnamcp09_articles WHERE ID_article='.$id); 
            $result=$maBase->query("SELECT a.*, c.LIBL_categorie  AS 'LIBL_categorie'
                FROM cnamcp09_articles a LEFT JOIN cnamcp09_categories c ON a.ID_categorie = c.ID_categorie 
                WHERE a.ID_article=".$id);
            if($result) {  
              $art = $result->fetch();
              $titre = $art['TITRE_article'];
              $contenu = $art['CONTENT_article'];
              $date = $art['DATE_article'];
              $categorie = $art['LIBL_categorie'];   
            }        
}
    ?>

      <div class="row">
        <div class="12u$(small)">
          <p><?php echo $categorie; ?></p>
          <h3><?php echo isset($titre)?html_entity_decode($titre):''; ?></h3>
          <?php echo date_format(new DateTime($date), 'd/m/Y H:i:s'); ?>
           <?php 
                  $nom_img = formateNomImage($titre);
                  $img_o = './medias/'.$id.'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$id.'-'.$nom_img.'-o.jpg'):'');
                  $img_m = './medias/'.$id.'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$id.'-'.$nom_img.'-m.jpg'):'');
                  $img_p = './medias/'.$id.'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$id.'-'.$nom_img.'-p.jpg'):'');
                  $photo = (file_exists('./medias/'.$id.'-'.$nom_img.'-o.jpg')); 
          ?>
          <span class="image fit">
            <img src="<?php echo ($photo?$img_m:'./medias/no_pic.jpg'); ?>" alt="">
          </span>

          <p><?php echo isset($contenu)?html_entity_decode($contenu):''; ?></p>
          
        </div>

</div>
</section>

<section id="main_categories" class="article.wrapper">

      <div class="6u 12u$(small) main_cats">

            <?php  //--------------------------------//
                   //--- affichage liste catégories ---//
                  //--------------------------------//
            $result=$maBase->query('SELECT  c.LIBL_categorie, c.ID_categorie, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                        FROM cnamcp09_categories c  LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie GROUP BY 1,2');
            $count=$result->rowCount() ;
        ?>
                <h4>Les différentes catégories (<?php echo $count; ?>)</h4>
                <ul class="alt">
                      <?php
                            if($result) { 
                              while($categorie=$result->fetch()) {  
                                  $catid = $categorie['ID_categorie'];
                                  $cat = $categorie['LIBL_categorie'];
                                  $total = $categorie['Nb_articles'];
                      ?>
                    
                    
                      <li><?php echo '<b>'.$categorie['LIBL_categorie'].'</b>   - ('.$total.' articles)'; ?></li>
                    <?php     } 
                            }   
                          if($count==0)  echo "<li>pas de catégories</li>";
                    ?>
                </ul>

        </div>

         <div class="inner main-encart2 portfolio">
              <div class="row">
                <div class="12u$(small)">
                  <h3>Portfolio</h3>
                  <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
                  <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
                  <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
                  <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
                  <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
                  <a href="portfolio.php" class="button special small">Voire tout</a>
                </div>
              </div>
        </div>
</section>
</section>


    <!-- Footer -->

    <?php include("./include/footer.php"); ?>

    <!-- Scripts -->

    <?php include("./include/scripts.php"); ?>

  </body>
</html>
