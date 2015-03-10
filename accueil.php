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

<?php include './include/entete.html';?>
<?php include './include/sommaire.php';?>

<!-- Division pour le contenu principal -->
<div id="contenu">
    <p>Bienvenue sur le site de GSB,</br> Ici vous pourrez saisir vos fiches de frais et les visualiser.</p>
</div>
<!-- Division pour le pied de page -->

<?php include './include/pied.html';?>
  

</body>
</html>

