<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="./styles/styles.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
  </head>
    
  <body>
    <div id="page">

<?php 
    include './include/entete.html';
    include './include/sommaire.php';
    include './include/connexion_bd.php';
    include './include/fonctions.php';
    
    creationFicheFrais($connexion, $_SESSION['id']);
?>
<!-- Division pour le contenu principal -->
<div id="contenu">
    
      <?php 
      echo '<h2>Saisie des fiches de frais ' .$_SESSION['annee_mois']. '</h2>' ;
      ?>
    
    <div class="corpsForm">

    <form method="post" action="maj_frais_forfait.php" >
        <fieldset>
            <legend>Eléments forfaitisés</legend>
            <label for="etape">Forfait Etape : </label>
            <input type="number" name="etape" required=""/></br>
            <label for="km">Frais Kilomètrique : </label>
            <input type="number" name="km" required=""/></br>
            <label for="nuit">Nuitée Hôtel : </label>
            <input type="number" name="nuit" required=""/></br>
            <label for="repas">Repas Restaurant : </label>
            <input type="number" name="repas" required=""/>
        </fieldset> 
        <input type="submit" value="Valider"/>
        <input type="reset" value="Réinitialiser"/></br>
    </form>
        
        <table>
            <tr>
                <th>Forfait Etape</th>
                <th>Frais Kilomètrique</th>
                <th>Nuitée Hôtel</th>
                <th>Repas Restaurant</th>
            </tr>
        
<?php
    // Fonction de récupération des elements forfaitises
    recuperationElementsForfaitises($connexion, $_SESSION['id']);
?>
        
    <form method="post" action="maj_frais_hors_forfait.php">
        <fieldset>
            <legend>Frais hors forfait</legend>
            <label for="date">Date : </label>
            <input type="date" name="date" required=""/></br>
            <label for="lib">Libelle : </label>
            <input type="text" name="libelle" required=""/></br>
            <label for="montant">Montant : </label>
            <input type="number" name="montant" required=""/>
        </fieldset> 
        <input type="submit" value="Valider"/>
        <input type="reset" value="Réinitialiser"/>
    </form>
        
        <h3>Tableau recapitulatif des éléments hors forfait</h3>
        
        <table>
            <tr>
                <th>Date</th>
                <th>Libelle</th>
                <th>Montant</th>
            </tr>
            
<?php
    // Fonction de récupération des éléments hors forfaits
    recuperationElementsHorsForfait($connexion, $_SESSION['id']);
?>
        </table>
        
        
    </div>
</div>
    <!-- Division pour le pied de page -->
    
<?php include './include/pied.html';?>

</body>
</html>
