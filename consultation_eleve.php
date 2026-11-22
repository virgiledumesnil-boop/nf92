<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Consulter un élève</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Consulter un élève</h1>

        <div class="form-container">

            <form action="consulter_eleve.php" method="post">

                <div class="form-group">

                <?php

                require 'connexion.php';

                $requete = "SELECT `ideleve`,`nom`,`prenom`,`dateNaiss` FROM eleves WHERE 1";
                $resultat = mysqli_query($connect,$requete);
                if (!$resultat) {
                    die("Query failed: $requete" . mysqli_error($connect));
                } 
                else {
                    echo "<label for='theme'>Élèves disponibles :</label>";
                    echo "<select name='theme' size='1' required>";
                    while ($row = mysqli_fetch_assoc($resultat)) {
                        echo "<option value='" . $row['ideleve'] . "'>" . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . " né le " . htmlspecialchars($row['dateNaiss']) . "</option>";
                    }
                    echo "</select>";
                }
                mysqli_close($connect);
                ?>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Consulter">
                        <input type="reset" value="Réinitialiser">
                    </div>
            </form>
        </div>            
    </body>
</html>