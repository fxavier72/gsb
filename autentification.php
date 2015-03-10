<?php
 include './include/connexion_bd.php';
 
$resultatRecherche = $connexion->query('SELECT * FROM visiteur WHERE login ="' .$_POST['txtLogin']. '" AND mdp="' .$_POST['txtMdp']. '"');

if($ligne = $resultatRecherche->fetch())
 {
    session_start();
    $_SESSION['id']=$ligne['id'];
    $_SESSION['nom']=$ligne['nom'];
    $_SESSION['prenom']=$ligne['prenom']; 
    $_SESSION['adresse']=$ligne['adresse'];
    $_SESSION['cp']=$ligne['cp'];
    $_SESSION['ville']=$ligne['ville'];
    $_SESSION['dateEmbauche']=$ligne['dateEmbauche'];
    header('location: accueil.php');
 }
 
 else
 {
     header('location: index.php?message=true');
 }

 $resultat->closeCursor();
?>
