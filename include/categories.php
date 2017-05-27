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
                                  $cat = html_entity_decode($categorie['LIBL_categorie']);
                                  $total = $categorie['Nb_articles'];
                      ?>
                    
                    
                      <li><a href="blog.php?id=<?= $catid; ?>" class="lien-cat"><?php echo '<b>'.html_entity_decode($categorie['LIBL_categorie']).'</b>   - ('.$total.' articles)'; ?></a></li>
                    <?php     } 
                            }   
                          if($count==0)  echo "<li>pas de catégories</li>";
                    ?>
                </ul>

</div>