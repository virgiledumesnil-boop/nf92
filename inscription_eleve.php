<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Inscrire un élève</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Inscrire un élève à une séance</h1>

        <div class="form-container">

            <form action="inscrire_eleve.php" method="post">
                <div class="form-group">

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

                    $requete = "SELECT `ideleve`,`nom`,`prenom`,`dateNaiss`,`dateInscription` FROM eleves WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='eleve'>Eleves disponibles :</label>";
                        echo "<select name='eleve' size='1'>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            echo "<option value='" . $row['ideleve'] . "'>" . $row['prenom'] . " " . $row['nom'] . " naît le " . $row['dateNaiss'] . " Inscit le " . $row['dateInscription'] . "</option>";
                            
                        }
                        echo "</select>";
                        echo "</div>
                              <div class='form-group'>";
                    }

                    $requete = "SELECT `idseance`,`DateSeance`,`EffMax`,`Idtheme` FROM seances WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='seance'>Séances disponibles :</label>";
                        echo "<select name='seance' size='1'>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            $requete_theme = "SELECT `nom` FROM themes WHERE `idtheme` = '" . $row['Idtheme'] . "'";
                            $resultat_theme = mysqli_query($connect,$requete_theme);

                            echo "<option value='" . $row['idseance'] . "'> Séance du " . $row['DateSeance'] . " sur le theme " . mysqli_fetch_assoc($resultat_theme)['nom'] . " (" . $row['EffMax'] . " places)</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                    }

                
                }
                mysqli_close($connect);
                ?>
                <div class="form-actions">
                    <input type="submit" value="Enregistrer">
                    <input type="reset" value="Réinitialiser">
                </div>
            </form>

        </div>
    </body>
</html>