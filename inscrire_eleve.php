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
            $requete = "SELECT `DateSeance`,`EffMax` FROM seances WHERE `idseance` = '$idseance'";
            $resultat = mysqli_query($connect,$requete);
            $resultat_date = mysqli_fetch_assoc($resultat);
            $DateSeance = $resultat_date['DateSeance'];
            $EffMax = $resultat_date['EffMax'];


            echo "<h1>Inscription à effectuer:</h1>";
            echo "<p>Eleve : " . $prenom . " " . $nom . "</p>";
            echo "<p>Date de la séance : " . $DateSeance . "</p>";
            echo "<p>Theme de la séance : " . $theme . "</p>";

            $query = "SELECT * FROM inscription WHERE `idseance` = $idseance";
            $resultat_inscriptions = mysqli_query($connect,$query);

            if (mysqli_num_rows($resultat_inscriptions) >= $EffMax) {
                echo "<h1>Erreur</h1>";
                echo "<p>La séance sur le thème " . $theme . " a la date du " . $DateSeance . " est complète.</p>";
            } else {

                $query = "SELECT * FROM inscription WHERE `idseance` = '$idseance' AND `ideleve` = '$ideleve'";
                $resultat_inscriptions = mysqli_query($connect,$query);
                if (mysqli_num_rows($resultat_inscriptions) > 0) {
                    echo "<h1>Erreur</h1>";
                    echo "<p>L'élève " . $prenom . " " . $nom . " est déjà inscrit à la séance sur le thème " . $theme . " à la date du " . $DateSeance . ".</p>";
                } else {

                    $query = "INSERT INTO inscription (idseance, ideleve,note) VALUES ('$idseance', '$ideleve', NULL)";
                    $result = mysqli_query($connect,$query);                

                    if (!$result) {
                        die("Query failed: $query" . mysqli_error($connect));
                    } 
                    else {
                        
                        echo "<h1>Inscription réalisée avec succès</h1>";
                        echo "<p>L'élève a été inscrit à la séance sur le thème " . $theme . " avec succès.</p>";
                    }
                }

            }

        
        }
        
        mysqli_close($connect);
        ?>
    </body>
</html>