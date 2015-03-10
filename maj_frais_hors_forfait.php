<?php
session_start();

include './include/connexion_bd.php';

$idMaxHF = $connexion->query('select MAX(id) as idMax from lignefraishorsforfait');
$id = $idMaxHF->fetch();
$idMax = ($id['idMax']+1);

$connexion->exec ('insert into lignefraishorsforfait values('.$idMax.', "'.$_SESSION['id'].'", "'.$_SESSION['annee_mois'].'", "'.$_POST['libelle'].'", "' .$_POST['date'].'", ' .$_POST['montant']. ')');

header('location: saisie_frais.php');
?>
