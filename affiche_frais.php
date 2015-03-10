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


<?php include './include/connexion_bd.php';
include './include/entete.html';
include './include/sommaire.php';
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
                $resultatRecherche = $connexion->query('SELECT DISTINCT mois FROM fichefrais ORDER BY mois DESC');
                            
                while($Mois = $resultatRecherche->fetch())
                {
                $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
                $leMois = substr($Mois['mois'], 4,2);
                $leMois = $tabMois[intval($leMois)-1];
                $lAnnee = substr($Mois['mois'], 0,4);
                ?>
                <option value="<?php echo $Mois['mois'] ?>"><?php echo $leMois. " " .$lAnnee . "\n"; ?></option>
                <?php
                }
                $resultatRecherche->closeCursor();
                ?>
            </select>
        </center>
        </fieldset>
    
        <input type="submit" value="Valider"></input>
        <input type="reset" value="Effacer"></input>
    </form>
    
    <?php
if (isset($_POST['mois']) != false)
{
    $_SESSION['mois']=$_POST['mois'];
    //echo $_POST['mois'];
    $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $leMois = substr($_POST['mois'], 4,2);
    $leMois = $tabMois[intval($leMois)-1];
    
    $lAnnee = substr($_POST['mois'], 0,4);
    
    $resultatEtat = $connexion->query('SELECT libelle, dateModif
                         FROM etat E
                         INNER JOIN fichefrais FF ON E.id = FF.idEtat
                         WHERE FF.idVisiteur = "'.$_SESSION['id'].'"
                         AND FF.mois = "'.$_POST['mois'].'"');

    while ($typeEtat = $resultatEtat->fetch())
    {
    $etat = $typeEtat['libelle'];
    $date = $typeEtat['dateModif'];
    }
    $resultatEtat->closeCursor();
    
    echo "Fiche de frais du mois de ".$leMois." ".$lAnnee." : ".$etat. " depuis le ".$date;

    $resultat = $connexion->query('SELECT montantValide 
                                 FROM fichefrais 
                                 WHERE idVisiteur="'.$_SESSION['id'].'" 
                                 AND mois = "'.$_POST['mois'].'"');

if ($ligne = $resultat->fetch())
{
    $montant = $ligne['montantValide'];
    echo '<br/><br/>';
    echo 'Montant validé : '.$montant.'';
}
?>
<br/><br/><h2>Quantités des elements forfaitisés</h2>
    <table width='100%' cellspacing='0' cellpadding='0' align='center'>
      <tr>
      <td colspan='1' align='center'>Forfait Etape</td>      
      <td colspan='1' align='center'>Frais Kilométrique</td>
      <td colspan='1' align='center'>Nuitées Hôtel</td>
      <td colspan='1' align='center'>Repas Restaurant</td>
   </tr>   
   <tr>
<?php
$resultat2 = $connexion->query('SELECT quantite
                              FROM lignefraisforfait 
                              WHERE idVisiteur="'.$_SESSION['id'].'" 
                              AND mois = "'.$_POST['mois'].'"');


while($ligne = $resultat2->fetch())
         
 {
    $idfrais = $ligne['quantite'];     
    echo  "<td width='25%' align='center'>".$idfrais."</td>";         
 }
 ?>
</tr></table>
<?php
$resultat2->closeCursor();

$resultat3 = $connexion->query('SELECT DATE, montant, libelle 
                              FROM lignefraishorsforfait 
                              WHERE mois="'.$_POST['mois'].'" 
                              AND idVisiteur="'.$_SESSION['id'].'" order by mois desc'); 
?>
<br/>
<h2>Descriptif des éléments hors forfaits</h2>
<table width='100%' cellspacing='0' cellpadding='0' align='center'>
      <tr>
      <td colspan='1' align='center'>Date</td>      
      <td colspan='1' align='center'>Libelle</td>
      <td colspan='1' align='center'>Montant</td>
      </tr>
<?php
 while($ligne=$resultat3->fetch())
         
 {
    $date = $ligne['DATE'];
    $montant = $ligne['montant'];
    $libelle = $ligne['libelle'];
      
      echo "
     <tr>
         <td width='20%' align='center'>$date</td>             
         <td width='60%' align='center'>$libelle</td>		 
         <td width='20%' align='center'>$montant</td>
     </tr>";
     
 }
$resultat3->closeCursor();
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