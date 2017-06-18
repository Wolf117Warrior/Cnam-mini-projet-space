<?php
//=========================================================
//Affichage erreur validation formulaire
//=========================================================
function setClassErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'class="error"':'');  
}
function setBulleErreur($champs){
   echo (isset($GLOBALS["erreurs"][$champs])?'<div id="bulleErreur" class="bulleErreur"><span><b>'.$GLOBALS["erreurs"][$champs].'</b></span></div>':'');  
}
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
  return strtolower(preg_replace('/([^.a-z0-9]+)/i', '-',str_to_noaccent(html_entity_decode($str))));
}
//=========================================================
// fonction tronquer texte long
//=========================================================
function tronqueTexte($texte,$long){
  $texte = strip_tags($texte);
  if(strlen($texte)<$long)
    return $texte.' ...';
  return (mb_strimwidth($texte, 0, $long, ' ...'));
}

//=========================================================
// fonction pagination enregistrements Bdd
//=========================================================
function paginationBdd($total_articles, $page, $params=null){
      if($total_articles==0) return ['offset'=>0,'limit'=>0,'pagination'=>''];
      // paramètres $_GET de l'url
      $param_url='';
      foreach ($_GET as $key => $value) {
        if($key!='page'&&$key!='aff'&&!array_key_exists($key,$params))    $param_url .= '&'.$key.'='.$value;
      }
      // paramètres optionnels l'url
      foreach ($params as $key => $value) {
        $param_url .= '&'.$key.'='.$value;
      }

      // tableau select affichage par page
        $tab_aff = [3,6,9,12,15,18,21,24,48];
        
        // nombre articles par page
        $nom_page = preg_replace('/.php/','',basename($_SERVER['PHP_SELF']));
        if(isset($_SESSION['aff'][$nom_page][$params['cat_id']])) $nb_par_page = $_SESSION['aff'][$nom_page][$params['cat_id']];
        else if(isset($_SESSION['aff'][$nom_page])&&!is_array($_SESSION['aff'][$nom_page])) $nb_par_page = $_SESSION['aff'][$nom_page];
        else            $nb_par_page = $tab_aff[0];

        // nombre de liens de numéros de page à afficher
        $nb_liens = 7;
        // nombre de pages 
        $nb_pages = ceil($total_articles/$nb_par_page);
        // page en cours
        if(!isset($page)||!is_numeric($page)) $page = 1;
        $page=intval($page);
        if($page>$nb_pages)$page=$nb_pages;
        /********************/
        /* limit/offset Bdd */
        /********************/
        // limit : maximum de lignes à obtenir
        $limit=$nb_par_page;
        // offset : décale les lignes à obtenir (commence tjs à 0)
        $offset=$nb_par_page*($page-1);      
        // pagination
        $pagination = '';
        /*******************************/
        /* nombres d'articles par page */
        /*******************************/
        $pagination .= 'Affichage :';
        $pagination .= '<span class="select-wrapper select-container">';
        $pagination .= '<select id="aff" onchange="javascript:window.location=\'?'.(isset($page)?'page='.$page:'').$param_url.'&aff=\'+this.value">';
        for($i=0;$i<count($tab_aff);$i++)
          $pagination .= '<option value="'.$tab_aff[$i].'" '.($nb_par_page==$tab_aff[$i]?'selected':'').'>'.$tab_aff[$i].'</option>';
        $pagination .= '</select>';
        $pagination .= '</span>';
        /*********************************************************/
        /* si pas la première page : affichage bouton pagination */
        /********************************************************/
        if($page>1){
          $pagination .= '<span><a href="?page=1'.$param_url.'">◄◄</a></span>';// aller à la première page
          if(($page-1)!=1) $pagination .= '<span><a href="?page='.($page-1).$param_url.'">◄</a></span>';// aller à la page précédente
        }
        /************************************************/
        /*** Nbre de liens inférieur à Nbre de pasges ***/
        /************************************************/
        if($nb_liens<$nb_pages){
            // nombre de page avant 1ère césure
            if($nb_liens<4) $part=0;
            elseif($nb_liens<6) $part=1;
            else $part=2;
            // césure début
          if($page<=ceil($nb_liens/2)){
              $cesure1_debut = $nb_liens-$part;
              $cesure1_fin = $nb_pages-$part;
              $cesure2_debut = $cesure2_fin = $nb_pages;
          }
          // césure fin
          else if($page>=$nb_pages-(ceil($nb_liens/2))){
              $cesure1_debut = $cesure1_fin = 0;
              $cesure2_debut = $part;
              $cesure2_fin = $nb_pages-($nb_liens-$part);
            }
          // césure milieu
          else if($page>ceil($nb_liens/2)){
              $cesure1_debut = $part;
              $cesure1_fin = ($page-ceil($nb_liens/2))+$part;
              $cesure2_debut = ($page+floor($nb_liens/2))-$part;
              $cesure2_fin = $nb_pages-$part;
            }
          // numéros de pages
          for($i=0;$i<$cesure1_debut;$i++){
            if($i==($page-1)) $pagination .= '<span class="pagination_on">'.($i+1).'</span>';// page active
            else              $pagination .= '<span><a href="?page='.($i+1).$param_url.'">'.($i+1).'</a></span>';// autres pages inactive + lien
          }
          // césure
          if($cesure1_debut!=0) $pagination .= ' ... ';
          // numéros de pages
          for($i=$cesure1_fin;$i<$cesure2_debut;$i++){
              if($i==($page-1)) $pagination .= '<span class="pagination_on">'.($i+1).'</span>';// page active
              else              $pagination .= '<span><a href="?page='.($i+1).$param_url.'">'.($i+1).'</a></span>';// autres pages inactive + lien
          }
          // césure
          if($cesure2_fin!=$nb_pages) $pagination .= ' ... '; 
          // numéros de pages
          for($i=$cesure2_fin;$i<$nb_pages;$i++){
              if($i==($page-1)) $pagination .= '<span class="pagination_on">'.($i+1).'</span>';// page active
              else              $pagination .= '<span><a href="?page='.($i+1).$param_url.'">'.($i+1).'</a></span>';// autres pages inactive + lien
          }
        /********************************************************/
        /*** Nbre de liens supérieur ou égale à Nbre de pages ***/
        /********************************************************/
        } else {
          // numéros de pages
         for($i=0;$i<$nb_pages;$i++){
            if($i==($page-1)) $pagination .= '<span class="pagination_on">'.($i+1).'</span>';// page active
            else              $pagination .= '<span><a href="?page='.($i+1).$param_url.'">'.($i+1).'</a></span>';// autres pages inactive + lien
          }
        }
        /***********************************************************/
        /** si pas la dernière page : affichage bouton pagination **/
        /***********************************************************/
        if($page<$nb_pages){
            if(($page+1)!=$nb_pages)  $pagination .= '<span><a href="?page='.($page+1).$param_url.'">►</a></span>';// aller à la page suivante
            $pagination .= '<span><a href="?page='.$nb_pages.$param_url.'">►►</a></span>';// aller à la dernière page
        }

        return ['offset'=>$offset,'limit'=>$limit,'pagination'=>$pagination] ;
}

?>