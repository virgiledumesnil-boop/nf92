<?php
$idtheme = isset($_POST['theme']) ? intval($_POST['theme']) : 0;
$date = isset($_POST['date']) ? $_POST['date'] : '';
$EffMax = isset($_POST['EffMax']) ? $_POST['EffMax'] : '';
date_default_timezone_set('Europe/Paris');
$date_ajd = date('Y-m-d');

$erreur = false;
$insertion_reussie = false;

if (empty($idtheme) || empty($date) || empty($EffMax) || ($date_ajd>$date) || (strlen($date) !== 10) || intval($EffMax) < 1) {
$erreur = true;
    if (empty($idtheme)){
        $erreur_type="theme";
    }
    elseif (empty($date)){
        $erreur_type="date";
    }    
    elseif (empty($EffMax)){
        $erreur_type="EffMax";
    }    
    elseif ($date_ajd>$date){
        $erreur_type="passé";
    }    
    elseif (strlen($date) !== 10){
        $erreur_type="futur";
    }
    elseif (intval($EffMax) < 1){
        $erreur_type="Effectif_négatif";
    }
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        $query = "SELECT * FROM seances WHERE `DateSeance`='$date' AND `Idtheme`='$idtheme'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            if (mysqli_num_rows($result)>0){
            $erreur = true;
            $erreur_type = "existe_deja";
            }
        }
        mysqli_close($connect);
    }
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $EffMax_sec = mysqli_real_escape_string($connect, $EffMax);
        
        $query = "INSERT INTO seances (idseance, DateSeance, EffMax, Idtheme) VALUES (NULL, '$date', '$EffMax_sec' ,'$idtheme')";
        if (mysqli_query($connect, $query)) {
            $insertion_reussie = true;
            $insertion = "creation";
        }
        mysqli_close($connect);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajout d'une séance</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if ($erreur): ?>

        <h1>Corriger les informations</h1>

        <div class="form-container">

            <form action="ajouter_seance.php" method="post">

                <div class="form-group">

                <?php

                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    mysqli_set_charset($connect,"utf8");

                    $requete = "SELECT `idtheme`,`nom`,`supprime` FROM themes WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='theme'>Thèmes disponibles :</label>";
                        $classe_select = ($erreur_type == "theme") ? "class='erreur-champ'" : "";
                        echo "<select name='theme' id='theme' size='1' $classe_select required>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            if ($row['supprime'] == 0) {
                                $selected = ($row['idtheme']==$idtheme)?"selected" : "";
                                echo "<option value='" . $row['idtheme'] . "' $selected>" . htmlspecialchars($row['nom']) . "</option>";
                            }
                        }
                        echo "</select>";
                        if ($erreur_type=="theme") {
                            echo "<span class='texte-erreur'>Veuillez sélectionner un thème</span>";
                        }
                    }
                
                }?>
                

                    </div>

                    <div class="form-group">
                        <label for="date" >Date de la séance</label>
                        <input type="date"  name="date" id="date" value="<?php echo htmlspecialchars($date); ?>" <?php if(($erreur_type=="date") || ($erreur_type == "passé") || ($erreur_type == "futur") || ($erreur_type=="existe_deja")){echo "class='erreur-champ'";} ?> required>
                        <?php if($erreur_type=="date"){echo "<span class='texte-erreur'>Veuillez rentrer une date</span>";} ?>
                        <?php if($erreur_type=="passé"){echo "<span class='texte-erreur'>La séance ne peut pas être dans le passé</span>";} ?>
                        <?php if($erreur_type=="futur"){echo "<span class='texte-erreur'>Veuillez rentrer une date à 8 chiffres</span>";} ?>
                        <?php if($erreur_type=="existe_deja"){echo "<span class='texte-erreur'>Une séance existe déjà le même jour sur le même thème</span>";} ?>
                    </div>

                    <div class="form-group">
                        <label for="EffMax">Effectif maximum</label>
                        <input type="number" name="EffMax" id="EffMax" value="<?php echo "$EffMax" ?>" min="1" <?php if($erreur_type=="EffMax" || $erreur_type="Effectif_négatif"){echo "class='erreur-champ'";} ?> required>
                        <?php if($erreur_type=="EffMax"){echo "<span class='texte-erreur'>veuillez rentrer un effectif max</span>";} ?>
                        <?php if($erreur_type="Effectif_négatif"){echo "<span class='text-erreur'>L'effectif max doit etre positif et non nul";} ?>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Corriger">
                        <input type="reset" value="Réinitialiser">
                    </div>
            </form>
        </div>
        <?php elseif ($insertion_reussie): ?>
            <h1>Opération réussie</h1>
            <div class="resultat-carte">
                <?php 
                    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
                    mysqli_set_charset($connect, "utf8");
                    if (!$connect) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    else {
                        $requete = "SELECT `nom` FROM themes WHERE idtheme='$idtheme'";
                        $resultat = mysqli_query($connect,$requete);
                        $theme = mysqli_fetch_assoc($resultat)['nom'];
                        mysqli_close($connect);
                    }

                ?>
                <p><strong>Theme :</strong> <?php echo htmlspecialchars($theme); ?></p>
                <p><strong>Date :</strong> <?php echo htmlspecialchars($date); ?></p>
                <p><strong>Effectif maximum :</strong> <?php echo htmlspecialchars($EffMax); ?></p>
                <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
                <p style="color: #457B9D; font-weight: bold;">La séance a été ajouté avec succès à la base de données.</p>
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