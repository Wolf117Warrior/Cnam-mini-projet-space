<?php
//=========================================================
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
include("./config/fonctions.php"); 
?><!DOCTYPE HTML>
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

<!-- Form -->

</section id="portfolio" class="content-wrapper">

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
                                $article_titre = html_entity_decode($article['TITRE_article']);
            ?>
          <?php 
                  $nom_img = formateNomImage($article_titre);
                  $img_o = './medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-o.jpg'):'');
                  $img_m = './medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-m.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-m.jpg'):'');
                  $img_p = './medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-p.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-p.jpg'):'');
                  $photo = (file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')); 
          ?>
          <div class="4u"><span class="image fit">
            <img src="<?php echo ($photo?$img_m:'./images/no_pic.jpg'); ?>" alt="" />
          </span></div>
              <?php           } 
                      }   
                      if($count==0)  echo "<div class='row'>pas de photos</div>";
              ?>
    </div>
  </div>

</div>

<div class="inner">

  <h4 class="center">Portofolio de satellite</h4>
  <div class="box alt">
    <div class="row 50% uniform">

      <div class="4u"><span class="image fit"><img src="images/portofolio/satellite/satellite-103.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="images/portofolio/satellite/satellite-103418_1920.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="images/portofolio/satellite/satellite-1030782_1920.jpg" alt="" /></span></div>
      <!-- Break -->
      <div class="4u"><span class="image fit"><img src="images/portofolio/satellite/telescope-63119_1280.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="images/portofolio/satellite/8537258881_35bce8fa2e_o.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="images/portofolio/satellite/Facebook-Satellite.jpg" alt="" /></span></div>

    </div>
  </div>

</div>

<div class="inner">

  <h4 class="center">Portofolio de Agence</h4>
  <div class="box alt">
    <div class="row 50% uniform">

      <div class="4u"><span class="image fit"><img src="images/portofolio/agence/ESA_Space_Operations_Centre.jpg" alt="" /></span></div>
      <div class="4u"><span class="image fit"><img src="images/portofolio/agence/nasa-space-walk.jpg" alt="" /></span></div>
      <div class="4u$"><span class="image fit"><img src="images/portofolio/agence/spaceX.jpg" alt="" /></span></div>
    </div>
  </div>

</div>


</section>

		<!-- Footer -->

		<?php include("./include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("./include/scripts.php"); ?>

	</body>
</html>
