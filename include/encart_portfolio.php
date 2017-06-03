        <div class="inner main-encart2 portfolio">
              <div class="row">
                <div class="12u$(small)">
                  <h3>Portfolio</h3>
                      <?php //--------------------------------//
                              //--- affichage liste articles ---//
                              //--------------------------------// 
                              $result_art=$maBase->query("SELECT ID_article,TITRE_article  FROM cnamcp09_articles 
                                                                  WHERE ID_categorie IS NOT NULL ORDER BY RAND() LIMIT 0,8"); 
                                  $count_art=$result_art->rowCount() ;
                                               if($result_art) {  
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
                              ?>
                              <span class="image fit">
                                <img src="<?php echo ($photo?$img_m:'./images/no_pic.jpg'); ?>" alt="" />
                              </span>
                              <?php           } 
                      }   
                      if($count_art==0)  echo "<div class='row'>pas de photos</div>";
              ?>

                  <a href="portfolio.php" class="button special small">Voire tout</a>
                </div>
              </div>
        </div>