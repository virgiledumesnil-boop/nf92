<?php
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';

date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d');

$erreur = false;

$age = date_diff(date_create($date_naissance),date_create($date));

if (empty($nom) || empty($prenom) || empty($date_naissance) || ($age->y < 15) || ($age->y > 100) || ($date_naissance > $date) || (strlen($date_naissance) !== 10)) {
$erreur = true;
    if (empty($nom)){
        $erreur_type="nom";
    }
    elseif (empty($prenom)){
        $erreur_type="prenom";
    }
    elseif (empty($date_naissance)){
        $erreur_type="date";
    }
    elseif(strlen($date_naissance) !== 10){
        $erreur_type="futur2";
    }
    elseif ($date_naissance > $date) {
        $erreur_type="futur";
    }
    elseif ($age->y < 15){
        $erreur_type="jeune";
    }
    elseif ($age->y > 100){
        $erreur_type="vieux";
    }
    
}

$insertion_reussie = false;
if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $nom_sec = mysqli_real_escape_string($connect, $nom);
        $prenom_sec = mysqli_real_escape_string($connect, $prenom);
        
        $query = "INSERT INTO eleves (ideleve, nom, prenom, dateNaiss, dateInscription) VALUES (NULL, '$nom_sec', '$prenom_sec', '$date_naissance', '$date')";
        if (mysqli_query($connect, $query)) {
            $insertion_reussie = true;
        }
        mysqli_close($connect);
    }
}

?>

<DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajout d'un élève</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if ($erreur): ?>

            <h1>Corriger les informations</h1>
            <div class="form-container">
                <form action="ajouter_eleve.php" method="POST">

                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input <?php if($erreur_type=="nom"){echo "class='erreur-champ'";} ?> type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                        <?php if($erreur_type=="nom"){echo"<span class='texte-erreur'>Veuillez saisir un Nom</span>";} ?>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input class="<?php if($erreur_type=="prenom"){echo"erreur-champ";} ?>" type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                        <?php if($erreur_type=="prenom"){echo"<span class='texte-erreur'>Veuillez saisir un Prenom</span>";} ?>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input class="<?php if($erreur_type=="date" || $erreur_type=="jeune" || $erreur_type=="vieux" || $erreur_type=="futur" || $erreur_type=="futur2"){echo"erreur-champ";} ?>"type="date" id="date_naissance" name="date_naissance" value="<?php echo $date_naissance; ?>" required>
                        <?php if($erreur_type=="date"){echo"<span class='texte-erreur'>Veuillez saisir une date</span>";} ?>
                        <?php if($erreur_type=="jeune"){echo"<span class='texte-erreur'>Il faut avoir plus de 15 ans pour s'inscrire</span>";} ?>
                        <?php if($erreur_type=="vieux"){echo"<span class='texte-erreur'>Il faut être âgé de moins de 100ans</span>";} ?>
                        <?php if($erreur_type=="futur"){echo"<span class='texte-erreur'>Veuillez saisir une date anterieure a aujourdhui</span>";} ?>
                        <?php if($erreur_type=="futur2"){echo"<span class='texte-erreur'>Veuillez saisir une date à 8 chiffres</span>";} ?>
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
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($prenom); ?></p>
            <p><strong>Né le :</strong> <?php echo $date_naissance; ?></p>
            <p><strong>Âge :</strong> <?php echo $age->y; ?> ans</p>
            <p><strong>Inscrit le :</strong> <?php echo $date; ?></p>
            <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
            <p style="color: #457B9D; font-weight: bold;">L'élève a été ajouté avec succès à la base de données.</p>
        </div>
    <?php else: ?>
        <h1>Erreur technique</h1>
        <div class="resultat-carte" style="border-left-color: #E63946;">
            <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
            <p>Veuillez contacter l'administrateur.</p>
        </div>
    <?php endif; ?>
</html>