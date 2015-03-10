<!-- Division pour le sommaire -->
    <div id="menuGauche">
       <div id="infosUtil">
                <h2>Bienvenue <?php echo $_SESSION['prenom'].' ' .$_SESSION['nom']; ?> </h2>
           
                <ul>
                    <a href="saisie_frais.php"><li>Saisie fiches de frais</li></a>
                    <a href="affiche_frais.php"><li>Mes fiches de frais</li></a>
                    <a href="deconnexion.php"><li>Deconnexion</li></a>
                </ul>
            
        </div>  
    </div>
