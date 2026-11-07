<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Inscription à une séance</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Inscription à une séance</h1>

        <div class="form-container">

            <form action="ajouter_seance.php" method="post">

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

                    $requete = "SELECT `idtheme`,`nom` FROM themes WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='theme'>Thèmes disponibles :</label><br>";
                        echo "<select name='theme' size='1'>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            if ($row['supprime'] == 0) {
                                echo "<option value='" . $row['idtheme'] . "'>" . $row['nom'] . "</option>";
                            }
                        }
                        echo "</select><br>";
                    }
                
                }
                
                echo <<< HTML

                    </div>

                    <div class="form-group">
                        <label for="date">Date de la séance</label>
                        <input type="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="EffMax">Effectif maximum</label>
                        <input type="number" name="EffMax" min="1" required>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Enregistrer">
                        <input type="reset" value="Réinitialiser">
                    </div>
            </form>
        </div>

        HTML;

            
    mysqli_close($connect);
    ?>
            
    </body>
</html>