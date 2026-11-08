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

                    $requete = "SELECT `idtheme`,`nom` FROM themes WHERE supprime = 0";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='theme'>Thèmes disponibles :</label>";
                        echo "<select name='idtheme' size='1'>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            echo "<option value='" . $row['idtheme'] . "'>" . $row['nom'] . "</option>";
                        }
                        echo "</select>";
                    }
                
                }
                
                echo <<< HTML

                    </div>

                    <div class="form-actions">
                        <input type="submit" style="background-color: #E63946;" value="Supprimer">
                    </div>
            </form>
        </div>

        HTML;

            
    mysqli_close($connect);
    ?>
            
    </body>
</html>