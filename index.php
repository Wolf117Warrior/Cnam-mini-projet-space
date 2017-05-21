<?php      
//=========================================================
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
include("./config/fonctions.php"); 
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
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
                    $img_o = './medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-o.jpg'):'');
                    $img_m = './medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-m.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-m.jpg'):'');
                    $img_p = './medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-p.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-p.jpg'):'');
                    $photo = (file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')); 

          ?>
          <span class="image fit"><img src="<?php echo ($photo?($i==1?$img_m:$img_p):'./images/no_pic.jpg'); ?>" alt=""></span>
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

      <?php
      //============================================
      //== Affichage encart liste des catégories ==  
      //============================================
          include("./include/categories.php"); 
      ?>

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
