<?php      
//conexion Bdd
include("./config/ConnexionBdd.php"); 
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
                WHERE c.LIBL_categorie IS NOT NULL ORDER BY DATE_article DESC"); 
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
        <h2>Catégorie : </h2>
        <p><?php echo $article['LIBL_categorie']; ?></p>
        
      </div>
    <?php } ?>
<?php if($i==1||$i%4==0) { ?>
      <div class="row">
<?php } ?>

        <div class="4u<?php echo ($i%3==0?'$':''); ?> 12u$(medium)">
          <h2>Catégorie : </h2>
          <p><?php echo $article['LIBL_categorie']; ?></p>
          <h3><?php echo $article['TITRE_article']; ?></h3>
          <span class="image fit"><img src="images/pic01.jpg" alt=""></span>
          <?php echo date_format(new DateTime($article['DATE_article']), 'd/m/Y H:i:s'); ?>
          <p>
            <?php echo $article['CONTENT_article']; ?>
            <a href="article.php" class="button special small">Lire la suite</a>
          </p>
        </div>
 <?php if($i%3==0) { ?>         
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
