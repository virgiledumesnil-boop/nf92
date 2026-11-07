<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajouter séance</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php

        $dbhost="tuxa.sme.utc";
        $dbname="nf92a022";
        $dbuser="nf92a022";
        $dbpass="yqtLMEgACs67";


        $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
        else {
            mysqli_set_charset($connect,"utf8");


        $idtheme = $_POST['theme'];
        $date = $_POST['date'];
        $EffMax = $_POST['EffMax'];
        $dateajd = date("Y-m-d");

        $requete = "SELECT `nom` FROM themes WHERE idtheme='$idtheme'";
        $resultat = mysqli_query($connect,$requete);
        $theme = mysqli_fetch_assoc($resultat);

        echo "<h1>Séance à ajouter :</h1>";
        echo "<p>Thème : $theme[nom]</p>";
        echo "<p>Date : $date</p>";
        echo "<p>Effectif maximum : $EffMax</p>";
        

            if (!isset($idtheme) || !isset($date) || !isset($EffMax) ||($date < $dateajd) || ($EffMax < 1)) {
                echo "<h1>Erreur</h1>";
                echo "<p>Les champs sont incomplets.</p>";
                echo "<p>Veuillez remplir tous les champs et vérifier que la date est supérieure ou égale à la date d'aujourd'hui et que l'effectif maximum est supérieur ou égal à 1.</p>";
                echo "<p><a href='ajout_seance.php'>Retour à la page d'ajout de séance</a></p>";
            } 
            else {

                $querry = "INSERT INTO seances (idseance, DateSeance, EffMax, Idtheme ) VALUES (NULL,'$date', $EffMax, $idtheme)";

                $result = mysqli_query($connect,$querry);                

                if (!$result) {
                    die("Query failed: $query" . mysqli_error($connect));
                } 
                else {
                    echo "<h1>Séance ajoutée avec succès</h1>";
                    echo "<p>La séance a été ajoutée avec succès à la base de données.</p>";
                }
            }
        
        }
        
        mysqli_close($connect);
        ?>
    </body>
</html>