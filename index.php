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
    <div>
    <a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
    </div>
</header>

<!-- NAVBAR -->
<?php include("./include/navbar.php"); ?>

<!-- Formulaire de recherche -->
<section>
  <div id="recherche" class="inner">
    <form method="post" action="searchengine.php">
      <div class="row uniform recherche-container">
        <div class="9u 12u$(small)  recherche-query">
          <input type="text" name="query" id="query" value="" placeholder="Mots clés ...">
        </div>
        <div class="3u$ 12u$(small) recherche-bouton">
          <input type="submit" value="Rechercher" class="fit">
        </div>
      </div>
    </form>
  </div>
</section>

<section id="content">
  <h3>Accueil</h3>
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
                                $article_id[$i] = $article['ID_article'];
                                $article_categorie[$i] = $article['LIBL_categorie'];
                                $article_titre[$i] = $article['TITRE_article'];
                                $article_date[$i] = $article['DATE_article'];
                                $article_content[$i] = $article['CONTENT_article'];
                                $nom_img = formateNomImage($article_titre[$i]);
                                $img_o[$i] = './medias/'.$article_id[$i].'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$article_id[$i].'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$article_id[$i].'-'.$nom_img.'-o.jpg'):'');
                                $img_m[$i] = './medias/'.$article_id[$i].'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$article_id[$i].'-'.$nom_img.'-m.jpg')?filemtime('./medias/'.$article_id[$i].'-'.$nom_img.'-m.jpg'):'');
                                $img_p[$i] = './medias/'.$article_id[$i].'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$article_id[$i].'-'.$nom_img.'-p.jpg')?filemtime('./medias/'.$article_id[$i].'-'.$nom_img.'-p.jpg'):'');
                                $photo[$i] = (file_exists('./medias/'.$article_id[$i].'-'.$nom_img.'-o.jpg')); 
                                $i++; 

                              }
                            }
                            $total = count($article_id);
                            
            ?>
          <?php if($count==0) {  ?></span><div class='row'>pas d'articles</div><?php  }  ?>
   <?php if($count>0) {  ?>
   <div class="table">
        <div class="table-row">
          <h4 class="cat table-cell">Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[0]); ?></p></h4>
        </div>
        <div class="table-row">
          <h3 class="titre table-cell"><?php echo html_entity_decode($article_titre[0]); ?></h3>
        </div>
        <div class="table-row">
          <div class="titre table-cell"><?php echo date_format(new DateTime($article_date[0]), 'd/m/Y H:i:s'); ?></div>
        </div>
        <div class="table-row">
          <span class="image fit table-cell">
            <img src="<?php echo ($photo[0]?$img_m[0]:'./images/no_pic.jpg'); ?>" width="200" alt="<?php echo html_entity_decode($article_categorie[0].' - '.$article_titre[0]); ?>">
          </span>
        </div>
        <div class="table-row">
          <div class="table-cell"><?php echo tronqueTexte(html_entity_decode($article_content[0]),800); ?></div>
        </div>
        <div class="table-row">
          <p class="more table-cell"><a href="article.php?id=<?php echo $article_id[0]; ?>" class="button special small">Lire la suite</a></p>
        </div>
      </div>
      <?php  }  ?>
<?php     for($i=1; $i<$total; $i++) { ?>
    <div class="table">
        <div class="table-row">
          <h4 class="cat table-cell"><?php $j=$i; if($j<$total) { ?>Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[$j]); ?></p><?php } ?></h4>
          <h4 class="cat table-cell"><?php $j++; if($j<$total) { ?>Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[$j]); ?></p><?php } ?></h4>
        </div>
        <div class="table-row">
          <h3 class="titre table-cell"><?php $j=$i;  if($j<$total) { ?><?php echo html_entity_decode($article_titre[$j]); ?><?php } ?> </h3>
          <h3 class="titre table-cell"><?php $j++; if($j<$total) { ?><?php echo html_entity_decode($article_titre[$j]); ?><?php } ?> </h3>
        </div>
        <div class="table-row">
          <div class="titre table-cell"><?php $j=$i; if($j<$total) { ?><?php echo date_format(new DateTime($article_date[$j]), 'd/m/Y H:i:s'); ?><?php } ?> </div>
          <div class="titre table-cell"><?php $j++; if($j<$total) { ?><?php echo date_format(new DateTime($article_date[$j]), 'd/m/Y H:i:s'); ?><?php } ?> </div>
        </div>
        <div class="table-row">
          <span class="image fit table-cell"><?php $j=$i; if($j<$total) { ?><img src="<?php echo ($photo[$j]?$img_m[$j]:'./images/no_pic.jpg'); ?>" width="200" alt="<?php echo html_entity_decode($article_categorie[$j].' - '.$article_titre[$j]); ?>"><?php } ?></span>
          <span class="image fit table-cell"><?php $j++;  if($j<$total) { ?><img src="<?php echo ($photo[$j]?$img_m[$j]:'./images/no_pic.jpg'); ?>" width="200" alt="<?php echo html_entity_decode($article_categorie[$j].' - '.$article_titre[$j]); ?>"><?php } ?></span>
        </div>
        <div class="table-row">
          <div class="table-cell"><?php $j=$i; if($j<$total) { ?><?php echo tronqueTexte(html_entity_decode($article_content[$j]),300); ?><?php } ?></div>
          <div class="table-cell"><?php $j++;  if($j<$total) { ?><?php echo tronqueTexte(html_entity_decode($article_content[$j]),300); ?><?php } ?></div>
        </div>
        <div class="table-row">
          <p class="more table-cell"><?php $j=$i; if($j<$total) { ?><a href="article.php?id=<?php echo $article_id[$j]; ?>" class="button special small">Lire la suite</a><?php } ?></p>
          <p class="more table-cell"><?php $j++;  if($j<$total) { ?><a href="article.php?id=<?php echo $article_id[$j]; ?>" class="button special small">Lire la suite</a><?php } ?></p>
        </div>
      </div>
<?php $i=$j; } ?>

</div>
</section>

<section id="main_categories" class="article.wrapper">

      <?php
      //============================================
      //== Affichage encart liste des catégories ==
      //============================================
          include("./include/categories.php");
               //============================================
      //== Affichage encart portfolio ==
      //============================================
          include("./include/encart_portfolio.php");
      ?>

</section>
</section>


		<!-- Footer -->

		<?php include("./include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("./include/scripts.php"); ?>

	</body>
</html>
