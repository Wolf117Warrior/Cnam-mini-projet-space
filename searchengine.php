<?php  
session_start(); 
// fuseau horaire 
date_default_timezone_set('America/Martinique'); 
//=========================================================
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
include("./config/fonctions.php"); 
//=========================================================
//===== init ==========
//=========================================================
// mot clé de recherche
if(isset($_POST["query"]))   $_SESSION['query'] = htmlentities(trim($_POST["query"]), ENT_QUOTES); 
// pagination 
$page = '';
if(isset($_GET["page"]))    $page = htmlentities($_GET["page"], ENT_QUOTES); 
if(isset($_GET["aff"]))     $_SESSION['aff'][preg_replace('/.php/','',basename($_SERVER['PHP_SELF']))] = htmlentities($_GET["aff"], ENT_QUOTES); 
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


<!-- NAVBAR -->
<?php include("./include/navbar.php"); ?>

<!-- Formulaire de recherche -->
  <div id="recherche" class="inner">
    <form method="post" action="searchengine.php">
      <div class="row uniform recherche-container">
        <div class="9u 12u$(small)  recherche-query">
          <input type="text" name="query" id="query" value="<?php echo isset($_SESSION['query'])?html_entity_decode($_SESSION['query']):''; ?>" placeholder="Mots clés ...">
        </div>
        <div class="3u$ 12u$(small) recherche-bouton">
          <input type="submit" value="Rechercher" class="fit">
        </div>
      </div>
    </form>
  </div>
</section>

<section id="content">
<section id="main" class="article-wrapper">

  <div class="inner taille1">
  <?php //--------------------------------//
        //--- affichage liste articles ---//
        //--------------------------------// 
        $where_rech = "AND (lower(CONVERT(c.LIBL_categorie USING utf8)) LIKE lower(CONVERT('%{$_SESSION['query']}%' USING utf8))  
                  OR lower(CONVERT(a.TITRE_article USING utf8)) LIKE lower(CONVERT('%{$_SESSION['query']}%' USING utf8)) 
                  OR lower(CONVERT(a.CONTENT_article USING utf8)) LIKE lower(CONVERT('%{$_SESSION['query']}%' USING utf8)) )";
        /** Initialisation pagination **/
        // total des articles
        $result_total_articles = $maBase->query("SELECT COUNT(*) AS total FROM cnamcp09_articles a 
                                              LEFT JOIN cnamcp09_categories c ON a.ID_categorie = c.ID_categorie WHERE c.ID_categorie IS NOT NULL {$where_rech}");
        $tab_total_articles=$result_total_articles->fetch();
        $total_articles=$tab_total_articles[0];

        // pagination
        $pagination = paginationBdd($total_articles,$page);

        $result=$maBase->query("SELECT a.*, c.LIBL_categorie  AS 'LIBL_categorie'
                FROM cnamcp09_articles a LEFT JOIN cnamcp09_categories c ON a.ID_categorie = c.ID_categorie 
                WHERE c.ID_categorie IS NOT NULL {$where_rech} ORDER BY DATE_article DESC LIMIT {$pagination['offset']},{$pagination['limit']}"); 
            $i = 0;
            $article_id = [];
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
     <h3>Résultat de recherche :  
      <span class="motrech"><?php echo $_SESSION['query'].'</span> - <span class="countrech">('.$total_articles.')</span>'; ?> articles trouvés
    </h3>

       <?php if($count==0) {  ?></span><div class='row'>pas d'articles</div><?php  }  ?>

<?php  
  
  for($i=0; $i<$total; $i++) { ?>
    <div class="table">
        <div class="table-row">
          <h4 class="cat table-cell"><?php $j=$i; if($j<$total) { ?>Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[$j]); ?></p><?php } ?></h4>
          <h4 class="cat table-cell"><?php $j++; if($j<$total) { ?>Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[$j]); ?></p><?php } ?></h4>
          <h4 class="cat table-cell"><?php $j++; if($j<$total) { ?>Catégorie : <p class="cat"><?php echo html_entity_decode($article_categorie[$j]); ?></p><?php } ?></h4>
        </div>
        <div class="table-row">
          <h3 class="titre table-cell"><?php $j=$i;  if($j<$total) { ?><?php echo html_entity_decode($article_titre[$j]); ?><?php } ?> </h3>
          <h3 class="titre table-cell"><?php $j++; if($j<$total) { ?><?php echo html_entity_decode($article_titre[$j]); ?><?php } ?> </h3>
          <h3 class="titre table-cell"><?php $j++; if($j<$total) { ?><?php echo html_entity_decode($article_titre[$j]); ?><?php } ?> </h3>
        </div>
        <div class="table-row">
          <div class="titre table-cell"><?php $j=$i; if($j<$total) { ?><?php echo date_format(new DateTime($article_date[$j]), 'd/m/Y H:i:s'); ?><?php } ?> </div>
          <div class="titre table-cell"><?php $j++; if($j<$total) { ?><?php echo date_format(new DateTime($article_date[$j]), 'd/m/Y H:i:s'); ?><?php } ?> </div>
          <div class="titre table-cell"><?php $j++;  if($j<$total) { ?><?php echo date_format(new DateTime($article_date[$j]), 'd/m/Y H:i:s'); ?><?php } ?> </div>
        </div>
        <div class="table-row">
          <span class="image fit table-cell"><?php $j=$i; if($j<$total) { ?><img src="<?php echo ($photo[$j]?$img_m[$j]:'./images/no_pic.jpg'); ?>" width="200" alt=""><?php } ?></span>
          <span class="image fit table-cell"><?php $j++;  if($j<$total) { ?><img src="<?php echo ($photo[$j]?$img_m[$j]:'./images/no_pic.jpg'); ?>" width="200" alt=""><?php } ?></span>
          <span class="image fit table-cell"><?php $j++;  if($j<$total) { ?><img src="<?php echo ($photo[$j]?$img_m[$j]:'./images/no_pic.jpg'); ?>" width="200" alt=""><?php } ?></span>
        </div>
        <div class="table-row">
          <div class="table-cell"><?php $j=$i; if($j<$total) { ?><?php echo tronqueTexte(html_entity_decode($article_content[$j]),300); ?><?php } ?></div>
          <div class="table-cell"><?php $j++;  if($j<$total) { ?><?php echo tronqueTexte(html_entity_decode($article_content[$j]),300); ?><?php } ?></div>
          <div class="table-cell"><?php $j++;  if($j<$total) { ?><?php echo tronqueTexte(html_entity_decode($article_content[$j]),300); ?><?php } ?></div>
        </div>
        <div class="table-row">
          <p class="more table-cell"><?php $j=$i; if($j<$total) { ?><a href="article.php?id=<?php echo $article_id[$j]; ?>" class="button special small">Lire la suite</a><?php } ?></p>
          <p class="more table-cell"><?php $j++;  if($j<$total) { ?><a href="article.php?id=<?php echo $article_id[$j]; ?>" class="button special small">Lire la suite</a><?php } ?></p>
          <p class="more table-cell"><?php $j++;  if($j<$total) { ?><a href="article.php?id=<?php echo $article_id[$j]; ?>" class="button special small">Lire la suite</a><?php } ?></p>
        </div>
      </div>
<?php $i=$j; } ?>  

<?php 
//---------------------//
//----  Pagination ----//
//---------------------//
echo '<p class="pagination">'.$pagination['pagination'].'</p>';
?>

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
