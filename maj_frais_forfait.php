<?php 
session_start();

include './include/connexion_bd.php';

$connexion->exec ('update lignefraisforfait set quantite = quantite + '.$_POST['etape'].' where idFraisForfait = "ETP" and idVisiteur = "'.$_SESSION['id'].'" and mois = "'.$_SESSION['annee_mois']. '"');
$connexion->exec ('update lignefraisforfait set quantite = quantite + '.$_POST['km'].' where idFraisForfait = "KM" and idVisiteur = "'.$_SESSION['id'].'" and mois = "'.$_SESSION['annee_mois']. '"');
$connexion->exec ('update lignefraisforfait set quantite = quantite + '.$_POST['nuit'].' where idFraisForfait = "NUI" and idVisiteur = "'.$_SESSION['id'].'" and mois = "'.$_SESSION['annee_mois']. '"');
$connexion->exec ('update lignefraisforfait set quantite = quantite + '.$_POST['repas'].' where idFraisForfait = "REP" and idVisiteur = "'.$_SESSION['id'].'" and mois = "'.$_SESSION['annee_mois']. '"');

header('location: saisie_frais.php');

?>
