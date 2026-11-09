<?php
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';

$erreur = false;
$insertion_reussie = false;

if (empty($nom) || empty($description) ) {
$erreur = true;
    if (empty($nom)){
        $erreur_type="nom";
    }
    elseif (empty($description)){
        $erreur_type="description";
    }    
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $nom_sec = mysqli_real_escape_string($connect, $nom);
        
        $query = "SELECT * FROM themes WHERE `nom`= '" . $nom ."'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $num_row = mysqli_num_rows($result);
            if($num_row == 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['supprime']==0){
                    $erreur = true;
                    $erreur_type = "existe_deja";
                }
                else{
                    $query = "UPDATE themes SET `supprime`= 0, `descriptif`='$description' WHERE nom = '$nom'";
                    if (mysqli_query($connect, $query)) {
                        $insertion_reussie = true;
                        $insertion = "reactivation";
                    }
                }
            }   
            elseif($num_row>1){
                $erreur = true;
                $erreur_type = "plusieurs_memes_nom";
            }
        }
        mysqli_close($connect);
    }
}
if (!$erreur & !$insertion_reussie) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $nom_sec = mysqli_real_escape_string($connect, $nom);
        $description_sec = mysqli_real_escape_string($connect, $description);
        
        $query = "INSERT INTO themes (idtheme, nom, supprime, descriptif) VALUES (NULL, '$nom_sec', '0' ,'$description_sec')";
        if (mysqli_query($connect, $query)) {
            $insertion_reussie = true;
            $insertion = "creation";
        }
        mysqli_close($connect);
    }
}

?>

<DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajout d'un thème</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if ($erreur): ?>

            <h1>Corriger les informations</h1>

            <div class="form-container">

                <form action="ajouter_theme.php" method="POST">

                    <div class="form-group">
                        <label for="nom">Nom du thème:</label>
                        <input type="text" <?php if($erreur_type=="nom" || $erreur_type == "existe_deja" || $erreur_type == "plusieurs_memes_nom"){echo "class='erreur-champ'";} ?>name="nom" id="nom" value = "<?php echo htmlspecialchars($nom); ?>" required>
                        <?php if($erreur_type=="nom"){echo"<span class='texte-erreur'>Veuillez rentrer un nom</span>";}?>
                        <?php if($erreur_type=="existe_deja"){echo"<span class='texte-erreur'>Un thème avec le même nom existe déjà</span>";}?>
                        <?php if($erreur_type=="plusieurs_memes_nom"){echo"<span class='texte-erreur'>Erreur, plusieurs themes avec le même nom existent dans la base de donnée</span>";}?>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>       
                        <textarea <?php if($erreur_type == "description"){echo "class='erreur-champ'";}?> name="description" id="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
                        <?php if($erreur_type == "description"){echo "<span class='texte-erreur'>Veuillez rentrer une description</span>";}?>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Corriger le formulaire">
                    </div>

                </form>
            </div>
        </body>
    <?php elseif ($insertion_reussie): ?>
        <h1>Opération réussie</h1>
        <div class="resultat-carte">
            <p><strong>Nom du theme :</strong> <?php echo htmlspecialchars($nom); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($description); ?></p>
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
</html>