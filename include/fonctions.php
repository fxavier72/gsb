<?php
/**
 * Fonction d'affichage des mois dans la liste déroulante
 * @param type $connexion
 */
function affichageMoisListe($connexion){
    $resultatRecherche = $connexion->query('SELECT DISTINCT mois FROM fichefrais ORDER BY mois DESC');

    while($Mois = $resultatRecherche->fetch())
    {
        $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $leMois = substr($Mois['mois'], 4,2);
        $leMois = $tabMois[intval($leMois)-1];
        $lAnnee = substr($Mois['mois'], 0,4);
?>
        <option value="<?php echo $Mois['mois'] ?>"><?php echo $leMois. " " .$lAnnee . "\n"; ?></option>
<?php
    }
    $resultatRecherche->closeCursor();
}

/**
 * Fonction d'affichage de la phrase d'état de la fiche
 * @param type $connexion
 * @param type $mois
 * @param type $id
 */
function phraseEtatFiche($connexion, $mois, $id) {
    // Création d'une variable de session contenant le mois pour le cas où l'on veut créer le PDF
    $_SESSION['mois']=$mois;
    //echo $_POST['mois'];
    $tabMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $leMois = substr($_POST['mois'], 4,2);
    $leMois = $tabMois[intval($leMois)-1];
    
    $lAnnee = substr($_POST['mois'], 0,4);
    
    // Récupération de l'état de la fiche (création, remboursé ...) et de sa date de der,ière modifiaction
    $resultatEtat = $connexion->query('SELECT libelle, dateModif
                                    FROM etat E
                                    INNER JOIN fichefrais FF ON E.id = FF.idEtat
                                    WHERE FF.idVisiteur = "'.$id.'"
                                    AND FF.mois = "'.$_POST['mois'].'"');
    while($typeEtat = $resultatEtat->fetch()) {
        $etat = $typeEtat['libelle'];
        $date = $typeEtat['dateModif'];
    }
    $resultatEtat->closeCursor();
    
    echo "Fiche de frais du mois de ".$leMois." ".$lAnnee." : ".utf8_encode($etat). " depuis le ".$date;
}

/**
 * Fonction d'affichage du montant de la fiche
 * @param type $connexion
 * @param type $id
 */
function montantFiche($connexion, $id) {
    $resultat = $connexion->query('SELECT montantValide 
                                 FROM fichefrais 
                                 WHERE idVisiteur="'.$id.'" 
                                 AND mois = "'.$_POST['mois'].'"');
    if ($ligne = $resultat->fetch()) {
        $montant = $ligne['montantValide'];
        echo '<br/><br/>';
        echo 'Montant validé : '.$montant.'';
    }
}

/**
 * Fonction d'affiche du tableau forfait
 * @param type $connexion
 * @param type $id
 */
function ligneTableauForfait($connexion, $id) {
    $resultat2 = $connexion->query('SELECT quantite
                                  FROM lignefraisforfait 
                                  WHERE idVisiteur="'.$id.'" 
                                  AND mois = "'.$_POST['mois'].'"');
    while($ligne = $resultat2->fetch()) {
        $idfrais = $ligne['quantite'];     
        echo  "<td width='25%' align='center'>".$idfrais."</td>";         
     }
     
     $resultat2->closeCursor();
}

/**
 * Fonction tableau hors forfait
 * @param type $connexion
 * @param type $id
 */
function ligneTableauHorsForfait($connexion, $id) {
    $resultat3 = $connexion->query('SELECT DATE, montant, libelle 
                                  FROM lignefraishorsforfait 
                                  WHERE mois="'.$_POST['mois'].'" 
                                  AND idVisiteur="'.$id.'" order by mois desc');
    
    while($ligne=$resultat3->fetch()) {
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
}


/**
 * Fonction de création de la fiche de frais
 * @param type $connexion
 */
function creationFicheFrais($connexion, $id){
    // Initialisation du jour et du mois
    $jour = date("d");
    $annee = date('Y');

    if ($jour < 10)// test pour savoir sur quel mois on est.
    {
        if (date('m') == 1)// test du mois d'une année inférieur 
        {
            $mois = 12;//on renvoit vers le mois d'après (décembre 12)
            $annee = $annee - 1;//on renvoit vers l'année précédente
        }
        else
        {
            $mois = date('m') - 1;
        }
    }
     else 
    {
         $mois = date('m');
    }

    // On vérifie si une fiche de frais existe pour ce mois là
    $_SESSION['annee_mois'] = $annee.$mois;
    $sql = "select * from fichefrais where idVisiteur = '" .$id."' and mois='".$_SESSION['annee_mois']."'";
    $resultat = $connexion->query($sql);

    if (!$ligne = $resultat->fetch())
    {
        $date = date('YYYY-MM-DD');
    //pas de fiche de frais pour ce mois là on la crée avec les lignes frais forfait correpondante
        $connexion->exec("insert into fichefrais values ('".$id."', '".$_SESSION['annee_mois']."', 0, 0, $date, 'CR')");
        $connexion->exec("insert into lignefraisforfait select '".$id."', '".$_SESSION['annee_mois']."', id, 0 from fraisforfait");
    }
}

/**
 * Fontion qui recupère la quantite de forfais étape, frais kilometrique, nuitée, et repas restaurant
 * @param type $connexion
 * $resultatForfaitEtape int(11) ETP
 * $forfaitEtape = $resultatForfaitEtape
 * $resultatFraisKm int(11) KM
 * $fraisKm = $resultatFraisKm
 * $resultatNuitee int(11) NUI
 * $nuitee = $resultatNuitee
 * $resultatRepas int(11) REP
 * $repas = $resultatRepas
 */
function recuperationElementsForfaitises($connexion, $id){
    // Recupération des forfaits étapes
    $resultatForfaitEtape = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "ETP" AND mois = "' .$_SESSION['annee_mois']. '"');
    // Recupère le resultat optenue par la requéte
    $forfaitEtape = $resultatForfaitEtape->fetch();
    // Recupération des frais KM
    $resultatFraisKm = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "KM" AND mois = "' .$_SESSION['annee_mois']. '"');
    $fraisKm = $resultatFraisKm->fetch();
    // Recupération des frais nuitee
    $resultatNuitee = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "NUI" AND mois = "' .$_SESSION['annee_mois']. '"');
    $nuitee = $resultatNuitee->fetch();
    // Recupération des frais repas
    $resultatRepas = $connexion->query('SELECT quantite FROM lignefraisforfait WHERE idVisiteur = "' .$id. '" AND idFraisForfait = "REP" AND mois = "' .$_SESSION['annee_mois']. '"');
    $repas = $resultatRepas->fetch();
?>   
    <h3>Tableau recapitulatif des éléments forfaitisé</h3>
            <tr>
                <td><?php echo $forfaitEtape['quantite']; ?></td>
                <td><?php echo $fraisKm['quantite']; ?></td>
                <td><?php echo $nuitee['quantite']; ?></td>
                <td><?php echo $repas['quantite']; ?></td>
            </tr>
        </table>
<?php
}

/**
 * @param type $connexion
 * $resultatHorsForfait -> recupère le libelle, la date et le montant dans la table lignefraishorsforfait quand l'id visiteur = a l'id de l'utilisateur connecté et que le mois = la valeur du mois initialisé dans creationFicheFrais
 */
function recuperationElementsHorsForfait($connexion, $id){
    $resultatHorsForfait = $connexion->query('select libelle, date, montant from lignefraishorsforfait where idVisiteur = "' .$id. '" and mois = ' .$_SESSION['annee_mois']);
    while($horsForfait = $resultatHorsForfait->fetch())
    {
    ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($horsForfait['date'])); ?></td>
                    <td><?php echo $horsForfait['libelle']; ?></td>
                    <td><?php echo $horsForfait['montant']; ?></td>
                </tr>
<?php
    }
}
?>

