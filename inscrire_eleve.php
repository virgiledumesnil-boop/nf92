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


            $idseance = $_POST['seance'];
            $ideleve = $_POST['eleve'];

            $requete_theme = "SELECT `nom` FROM themes WHERE `idtheme` = (SELECT `Idtheme` FROM seances WHERE `idseance` = '$idseance')";
            $resultat_theme = mysqli_query($connect,$requete_theme);
            $theme= mysqli_fetch_assoc($resultat_theme)['nom'];
            $requete_eleve = "SELECT `nom`, `prenom` FROM eleves WHERE `ideleve` = '$ideleve'";
            $resultat_eleve = mysqli_query($connect,$requete_eleve);
            $eleve = mysqli_fetch_assoc($resultat_eleve);
            $nom = $eleve['nom'];
            $prenom = $eleve['prenom'];
            $requete_date = "SELECT `DateSeance` FROM seances WHERE `idseance` = '$idseance'";
            $resultat_date = mysqli_query($connect,$requete_date);
            $DateSeance = mysqli_fetch_assoc($resultat_date)['DateSeance'];


            echo "<h1>Inscription à effectuer:</h1>";
            echo "<p>Eleve : " . $prenom . " " . $nom . "</p>";
            echo "<p>Date de la séance : " . $DateSeance . "</p>";
            echo "<p>Sur le theme : " . $theme . "</p>";
        


            $querry = "INSERT INTO inscription (idseance, ideleve,note) VALUES ('$idseance', '$ideleve', NULL)";
            $result = mysqli_query($connect,$querry);                

            if (!$result) {
                die("Query failed: $query" . mysqli_error($connect));
            } 
            else {
                
                echo "<h1>Inscription réalisée avec succès</h1>";
                echo "<p>L'élève a été inscrit à la séance sur le thème " . $theme . " avec succès.</p>";
            }

        
        }
        
        mysqli_close($connect);
        ?>
    </body>
</html>