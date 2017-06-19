<?php
session_start(); 
//=========================================================
// conexion Bdd
//=========================================================
include("./config/ConnexionBdd.php"); 
include("./config/fonctions.php"); 
//=========================================================
//===== init ==========
//=========================================================
// id catégorie articles
if(isset($_GET["cat_id"]))      $cat_id = htmlentities($_GET["cat_id"], ENT_QUOTES);
// pagination 
if(isset($_GET["page"]))    $_SESSION['page'][$cat_id] = htmlentities($_GET["page"], ENT_QUOTES); 
if(isset($_GET["aff"]))     $_SESSION['aff'][preg_replace('/.php/','',basename($_SERVER['PHP_SELF']))][$cat_id] = htmlentities($_GET["aff"], ENT_QUOTES); 
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


<?php include("./include/navbar.php"); ?>

<!-- Form -->

</section id="portfolio" class="content-wrapper">


    <?php //----------------------------------//
          //--- affichage liste catégories ---//
          //----------------------------------//
           /* $result=$maBase->query('SELECT c.LIBL_categorie, c.ID_categorie, a.ID_article, COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                      FROM cnamcp09_categories c  LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie
                                        GROUP BY 1,2,3 HAVING Nb_articles!=0');*/

            $result=$maBase->query('SELECT DISTINCT a.ID_categorie , c.LIBL_categorie , COUNT(DISTINCT a.ID_article) AS "Nb_articles"  
                                  FROM cnamcp09_categories c LEFT JOIN cnamcp09_articles a ON c.ID_categorie = a.ID_categorie
                                  WHERE a.ID_categorie IS NOT NULL GROUP BY 1,2');
            $count=$result->rowCount();
    
            if($result) { 
              while($categorie=$result->fetch()) {  
                $catid = $categorie['ID_categorie'];
                $cat = html_entity_decode($categorie['LIBL_categorie']);
                $total = $categorie['Nb_articles'];
            ?>
                      
                      <div class="inner">
                        <h4 class="center">Portfolio de <?php echo html_entity_decode($categorie['LIBL_categorie']).'</b>   - ('.$total.' articles)'; ?></h4>
                        <div class="box alt">
                          <div class="row 50% uniform">
                            <?php //--------------------------------//
                              //--- affichage liste articles ---//
                              //--------------------------------// 
                            // pagination
                              if(!isset($_SESSION['page'][$catid]))$_SESSION['page'][$catid]=1;
                              $pagination = paginationBdd($total, $_SESSION['page'][$catid],['cat_id'=>$catid] );
                              $result_art=$maBase->query("SELECT ID_article,TITRE_article  FROM cnamcp09_articles 
                                                                  WHERE ID_categorie ='{$catid}' AND PHOTO_article='1' ORDER BY DATE_article DESC
                                                                  LIMIT {$pagination['offset']},{$pagination['limit']}"); 
                                  $count_art=$result_art->rowCount() ;
                                               if($result_art) {  
                                                    $exist = false;
                                                    while($article=$result_art->fetch()) { 
                                                      $article_id = $article['ID_article'];
                                                      $article_titre = html_entity_decode($article['TITRE_article']);
                                  ?>
                            <?php 
                              $nom_img = formateNomImage($article_titre);
                              $img_o = './medias/'.$article_id.'-'.$nom_img.'-o.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-o.jpg'):'');
                              $img_m = './medias/'.$article_id.'-'.$nom_img.'-m.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-m.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-m.jpg'):'');
                              $img_p = './medias/'.$article_id.'-'.$nom_img.'-p.jpg?v='.(file_exists('./medias/'.$article_id.'-'.$nom_img.'-p.jpg')?filemtime('./medias/'.$article_id.'-'.$nom_img.'-p.jpg'):'');
                              $photo = (file_exists('./medias/'.$article_id.'-'.$nom_img.'-o.jpg')); 
                              if($photo){ $exist = true;
                            ?>
                              <div class="4u"><span class="image fit">
                                <img src="<?php echo $img_m; ?>" alt="" />
                              </span></div>

                              <?php      }     } ?>
<?php 
//---------------------//
//----  Pagination ----//
//---------------------//
echo '<div style="width: 100%;"><p class="pagination">'.$pagination['pagination'].'</p></div>';
?>
               <?php       }   
                      if(!$exist)  echo "<div class='row'>pas de photos</div>";
              ?>
                          </div>
                        </div>
                      </div>

            <?php   } 
                  }   
                if($count==0)  echo '<div class="inner"><div class="box">pas de catégories</div></div>';  ?>
               
</section>

		<!-- Footer -->

		<?php include("./include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("./include/scripts.php"); ?>

	</body>
</html>
