<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Supression d'un Thème</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Suppression d'un Thème</h1>

        <div class="form-container">

            <form action="supprimer_theme.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ? (vous pourrez le reactiver plus tard)');">

                <div class="form-group">

                <?php

                require 'connexion.php';

                $requete = "SELECT `idtheme`,`nom` FROM themes WHERE supprime = 0";
                $resultat = mysqli_query($connect,$requete);
                if (!$resultat) {
                    die("Query failed: $requete" . mysqli_error($connect));
                } 
                else {
                    echo "<label for='theme'>Thèmes disponibles :</label>";
                    echo "<select name='idtheme' size='1' required>";
                    while ($row = mysqli_fetch_assoc($resultat)) {
                        echo "<option value='" . $row['idtheme'] . "'>" . htmlspecialchars($row['nom']) . "</option>";
                    }
                    echo "</select>";
                }
                
                echo <<< HTML

                    </div>

                    <div class="form-actions">
                        <input type="submit" style="background-color: var(--supprime);" value="Supprimer">
                    </div>
            </form>
        </div>

HTML;

            
    mysqli_close($connect);
    ?>
            
    </body>
</html>