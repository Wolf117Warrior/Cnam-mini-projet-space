<?php
//header('Content-Type: text/html; charset=UTF-8');
//---- Désactive le rapport d'erreurs par défaut pour affichage pesonnalisé----//
//error_reporting(0);

$Bdd_serveur='localhost' ; 
$Bdd_login='root' ;
$Bdd_pwd='root' ;
$Bdd_nom='werfl9_cnam_pro' ;

$Bdd_serveur='web2.pulseheberg.net' ; 
$Bdd_login='werfl9_cnam_pro' ;
$Bdd_pwd='96MenrV4k6ZK' ;
$Bdd_nom='werfl9_cnam_pro' ;

try{

       $maBase = new PDO("mysql:host={$Bdd_serveur};dbname={$Bdd_nom}", $Bdd_login, $Bdd_pwd);
       //$maBase = new PDO("mysql:host={$Bdd_serveur};dbname={$Bdd_nom};charset=utf8", $Bdd_login, $Bdd_pwd);               
}
catch(Exception $e)
{
                die("Impossible de se Connecter à la Base : {$e->getMessage()}");
}
?>
