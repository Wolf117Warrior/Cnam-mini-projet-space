<?php      
//=========================================================
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
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
// fonction tronquer texte long
//=========================================================
function tronqueTexte($texte,$long){
  return mb_strimwidth($texte, 0, $long, ' ...');
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



  <?php //--------------------------------//
        //--- affichage liste articles ---//
        //--------------------------------// 
        $result=$maBase->query("SELECT a.*, c.LIBL_categorie  AS 'LIBL_categorie'
                FROM cnamcp09_articles a LEFT JOIN cnamcp09_categories c ON a.ID_categorie = c.ID_categorie 
                WHERE c.LIBL_categorie IS NOT NULL ORDER BY DATE_article DESC LIMIT 0,3"); 
            $i = 0;
            $count=$result->rowCount() ;
                         if($result) { 
                              while($article=$result->fetch()) { 
                                $i++; 
                                $article_id = $article['ID_article'];
                                $art = $article['TITRE_article'];
            ?>
    <?php if($i==1) { ?>
      <div class="12u$(small) taille2">
        <h2>Les derniers articles</h2>
        
      </div>
    <?php } ?>
<?php if($i==1||$i==2) { ?>
      <div class="row">
<?php } ?>

        <div class="<?php echo ($i%2==0?'6u':($i%3==0?'6u$':'taille2')); ?> 12u$(small)">
          <p><?php echo $article['LIBL_categorie']; ?></p>
          <h3><?php echo $article['TITRE_article']; ?></h3>
          <?php 
                    $nom_img = formateNomImage($article['TITRE_article']);
                    $img_o = './medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.filemtime('./medias/'.$article_id.'-'.$nom_img.'-o.jpg');
                    $img_m = './medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.filemtime('./medias/'.$article_id.'-'.$nom_img.'-m.jpg');
                    $img_p = './medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.filemtime('./medias/'.$article_id.'-'.$nom_img.'-p.jpg');
                    $photo = (file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')); 
          ?>
          <span class="image fit"><img src="<?php echo ($photo?($i==1?$img_m:$img_p):'./images/pic01.jpg'); ?>" alt=""></span>
          <?php echo date_format(new DateTime($article['DATE_article']), 'd/m/Y H:i:s'); ?>
          <p>
            <?php echo tronqueTexte($article['CONTENT_article'],800); ?>
            <a href="article.php?id=<?php echo $article_id; ?>" class="button special small">Lire la suite</a>
          </p>
        </div>
 <?php if($i==1||$i==3) { ?>         
      </div>
<?php } ?>
              <?php           } 
                      }   
                      if($count==0)  echo "<div class='row'>pas d'articles</div>";
              ?>

</div>
</section>

<section id="main_categories" class="article.wrapper">

      <div class="6u 12u$(small) main_cats">

        <?php  //--------------------------------//
                   //--- affichage liste catégories ---//
                  //--------------------------------//
            $result=$maBase->query('SELECT  c.LIBL_categorie, c.ID_categorie, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                      FROM cnamcp09_categories c  LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie
                                        GROUP BY 1,2 HAVING Nb_articles!=0');
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
                    
                    
                      <li><a href="blog.php?id=<?= $catid; ?>" class="lien-cat"><?php echo '<b>'.$categorie['LIBL_categorie'].'</b>   - ('.$total.' articles)'; ?></a></li>
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
