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
    include './include/connexion_bd.php';
    include './include/entete.html';
    include './include/sommaire.php';
    include './include/fonctions.php';
?>

<!-- Division pour le contenu principal -->
<div id="contenu">
    <h2>Mes fiches de frais</h2>

    <h2>Mois à sélectionner :</h2>
    <form action="affiche_frais.php" method="POST">
        <fieldset>
        <center>
        <label for="mois">Mois : </label>
            <select name="mois">
                <?php
                    affichageMoisListe($connexion);
                ?>
            </select>
        </center>
        </fieldset>
    
        <input type="submit" value="Valider"></input>
        <input type="reset" value="Effacer"></input>
    </form>
    
    <?php
// Si on choisit une date dans la liste déroulante et valide on affiche
if(isset($_POST['mois']) != false) {
    phraseEtatFiche($connexion, $_POST['mois'], $_SESSION['id']);
    montantFiche($connexion, $_SESSION['id']);
    ?>
    
    <br/><br/>
    <h2>Quantités des elements forfaitisés</h2>
        <table width='100%' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td colspan='1' align='center'>Forfait Etape</td>      
                <td colspan='1' align='center'>Frais Kilométrique</td>
                <td colspan='1' align='center'>Nuitées Hôtel</td>
                <td colspan='1' align='center'>Repas Restaurant</td>
            </tr>   
            <tr>
            <?php
                ligneTableauForfait($connexion, $_SESSION['id']);
             ?>
            </tr>
        </table>
    <br/>
    <h2>Descriptif des éléments hors forfaits</h2>
    <table width='100%' cellspacing='0' cellpadding='0' align='center'>
          <tr>
          <td colspan='1' align='center'>Date</td>      
          <td colspan='1' align='center'>Libelle</td>
          <td colspan='1' align='center'>Montant</td>
          </tr>
    <?php
        ligneTableauHorsForfait($connexion, $_SESSION['id']);
    ?>
    </table>
    <br/><br/>
    <a href="pdf.php">Impirmer en PDF</a>
    <?php
}
?>
</div>
    <!-- Division pour le pied de page -->
    
<?php include './include/pied.html';?>
  

    </body>
</html>