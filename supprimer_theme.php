<?php
$idtheme_form = isset($_POST['idtheme']) ? interval($_POST['idtheme']) : '';

$erreur = false;
$suppression_reussie = false;

if (empty($idtheme_form)) {
    $erreur = true;
    $erreur_type= "idtheme"; 
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        $query = "SELECT * FROM themes WHERE `idtheme`=$idtheme_form";
        $result = mysqli_query($connect, $query);
        if ($result) {
            if (mysqli_num_rows($result)==0){
                $erreur = true;
                $erreur_type = "existe_pas";
            }
        }
        mysqli_close($connect);
    }
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");

        $query = "SELECT * FROM themes WHERE `idtheme`=$idtheme_form";
        $reslut = mysqli_query($connect,$query);
        $theme = mysqli_fetch_assoc($result);
        $nom_theme = $theme['nom'];
        $description = $theme['descriptif'];
                
        $query = "UPDATE `themes` SET `supprime`= 1 WHERE idtheme = '$idtheme'";
        if (mysqli_query($connect, $query)) {
            $suppression_reussie = true;
        }
        mysqli_close($connect);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Supression d'un thème</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if ($erreur): ?>

            <h1>Corriger les informations</h1>

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
                        echo "<select name='idtheme' size='1' class='erreur-champ' required>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            $selected = ($row['idtheme']==$idtheme_form) ? "selected" : "";
                            echo "<option value='" . $row['idtheme'] . "' $selected>" . htmlspecialchars($row['nom']) . "</option>";
                        }
                        echo "</select>";
                    }
                
                }
                ?>

                    </div>

                    <div class="form-actions">
                        <input type="submit" style="background-color: var(--supprime);" value="Supprimer">
                    </div>
            </form>
        </div>
        <?php elseif ($insertion_reussie): ?>
            <h1>Opération réussie</h1>
            <div class="resultat-carte">
                <p><strong>Nom du theme :</strong> <?php echo htmlspecialchars($nom_theme); ?></p>
                <p><strong>Description :</strong> <?php echo htmlspecialchars($description_theme); ?></p>
                <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
                <?php if($insertion == "creation"){echo "<p style='color: #457B9D; font-weight: bold;'>Le theme a été ajouté avec succès à la base de données.</p>";}; ?>
                <?php if($insertion == "reactivation"){echo"<p style ='color: #457B9D; font-weight: bold;'>Le theme a été réactivé avec succès à la base de données.</p>";}; ?>
            </div>
        <?php else: ?>
            <h1>Erreur technique</h1>
            <div class="resultat-carte" style="border-left-color: #E63946;">
                <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
                <p>Veuillez contacter l'administrateur.</p>
            </div>
        <?php endif; ?>
    </body>
</html>