<?php

$Hostname = "hostname";
$NameBDD = "DataBaseName";
$User = "userDataBase";
$Password = "passwordDataBase";

try
{
    $connexion = new PDO("mysql:host=$Hostname;dbname=$NameBDD", $User, $Password);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
    
}

?>
