<?php
//header('Content-Type: text/html; charset=UTF-8');
//---- Désactive le rapport d'erreurs par défaut pour affichage pesonnalisé----//
//error_reporting(0);

<<<<<<< HEAD
/*$Bdd_serveur='localhost' ; 
$Bdd_login='root' ;
$Bdd_pwd='root' ;
$Bdd_nom='werfl9_cnam_pro' ;*/
=======
$Bdd_serveur='rrrrrrrr' ; 
$Bdd_login='rrrrrr' ;
$Bdd_pwd='rrrrrr' ;
$Bdd_nom='rrrrrrrr' ;
>>>>>>> 208d7ae287245f4c8ee2470a5bf18c71d507e601

$Bdd_serveur='rrrrrrrr' ; 
$Bdd_login='rrrrrrrr' ;
$Bdd_pwd='rrrrrrrr' ;
$Bdd_nom='rrrrrrrrrrrr' ;

try{

       $maBase = new PDO("mysql:host={$Bdd_serveur};dbname={$Bdd_nom}", $Bdd_login, $Bdd_pwd);
       //$maBase = new PDO("mysql:host={$Bdd_serveur};dbname={$Bdd_nom};charset=utf8", $Bdd_login, $Bdd_pwd);               
}
catch(Exception $e)
{
                die("Impossible de se Connecter à la Base : {$e->getMessage()}");
}
?>
