<?php
include './include/connexion_bd.php'; 

session_start();
$id = $_SESSION['id']; 
$mois=$_SESSION['mois'];
$tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
$leMois = substr($_SESSION['mois'], 4,2);
$leMois = $tabMois[intval($leMois)-1]; 
$lAnnee = substr($_SESSION['mois'], 0,4);
$resultat=$connexion->query('SELECT montantValide from fichefrais where idVisiteur="'.$id.'" and mois = "'.$mois.'"');


ob_start();
?>
<div align="center">
<img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" /><br/>
</div>
<br/><br/>
<fieldset>
<div align="left">
<fieldset>
    <p align="center"><h3>REMBOURSEMENT DES FRAIS ENGAGE</h3></p>
       
</fieldset>
        
<br/><br/>

Visiteur : <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?>

<br/><br/>

<?php

    $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $leMois = substr($_SESSION['mois'], 4,2);
    $leMois = $tabMois[intval($leMois)-1];
    
    $lAnnee = substr($_SESSION['mois'], 0,4);
?>
Mois : <?php echo $leMois." ".$lAnnee; ?>

<br/><br/>

<h2 align="center">Quantités des elements forfaitisés</h2>
     <table width='100%' cellspacing='0' cellpadding='0' align='center' border="1">
      <tr>
      <td colspan='1' align='center'>Forfait Etape</td>      
      <td colspan='1' align='center'>Frais Kilométrique</td>
      <td colspan='1' align='center'>Nuitées Hôtel</td>
      <td colspan='1' align='center'>Repas Restaurant</td>
   </tr>   
   <tr>
       
<?php
$resultat2=$connexion->query('SELECT quantite from lignefraisforfait WHERE idVisiteur="'.$id.'" And mois = "'.$mois.'"');


while($ligne=$resultat2->fetch())
         
 {
    $idfrais=$ligne['quantite'];     
    echo  "<td width='25%' align='center'>".$idfrais."</td>";         
 }
 ?>
</tr></table>
<?php
$resultat2->closeCursor();

$resultat3=$connexion->query('SELECT DATE, montant, libelle FROM lignefraishorsforfait where mois="'.$mois.'" and idVisiteur="'.$id.'" order by mois desc'); 
?>
<br/>
<h2 align="center">Descriptif des éléments hors forfaits</h2>
 <table width='100%' cellspacing='0' cellpadding='0' align='center' border="1">
      <tr>
      <td colspan='1' align='center'>Date</td>      
      <td colspan='1' align='center'>Libelle</td>
      <td colspan='1' align='center'>Montant</td>
      </tr>
<?php
 while($ligne=$resultat3->fetch())
         
 {
    $date=$ligne['DATE'];
    $montant=$ligne['montant'];
    $libelle=$ligne['libelle'];
      
      echo "
     <tr>
         <td width='20%' align='center'>$date</td>             
         <td width='60%' align='center'>$libelle</td>     
         <td width='20%' align='center'>$montant</td>
     </tr>";
     
 }
$resultat3->closeCursor();
?>

      <br/>
      <br/>
</table>
</div>
<br/>
<br/>
<div align="center">
    <?php
      if ($ligne = $resultat->fetch())
{
    $montant=$ligne['montantValide'];
    echo '<strong>montant validé : '.$montant.' €</strong>';
}  
?>
    
</div>
</fieldset>
<div align="right">
    <?php
    
   $leMois = date("m");
   $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mais", "Juin", "Juillet", "Août", "Septembre",
                    "Octobre", "Novembre", "Décembre");
   $mois = $tabMois[intval($leMois)-1];

echo "<br />Fait à Le Mans, le ".date("d")." ".$mois." ".date("Y")."<br/> Vu l'agent comptable : "; 

?>
</div>


<?php
echo'<br/><img src="./images/signature.jpg" align="right">';


?>
<?php 
    $content =  ob_get_clean();
    require('./lib/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
?>