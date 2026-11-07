<?php
// 1. RÉCUPÉRATION ET LOGIQUE (Aucun code HTML ici)
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';

date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d');

// Variables pour stocker l'état des erreurs
$erreur = false;
$message_erreur_age = '';

if (!empty($date_naissance)) {
    $age = date_diff(date_create($date_naissance), date_create($date))->y;
    if ($age < 15 || $age > 123 || $date_naissance > $date) {
        $erreur = true;
        $message_erreur_age = "L'élève doit avoir entre 15 et 123 ans.";
    }
} 
else {
    $erreur = true;
}

if (empty($nom) || empty($prenom)) {
    $erreur = true;
}

// Si pas d'erreur, on fait l'insertion BDD
$insertion_reussie = false;
if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        // Sécurité : on échappe les caractères spéciaux
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout d'un élève</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php if ($erreur): ?>
        <!-- CAS 1 : IL Y A UNE ERREUR, ON RE-AFFICHE LE FORMULAIRE -->
        <h1>Corriger les informations</h1>
        <div class="form-container">
            <form action="ajouter_eleve.php" method="POST">
                
                <div class="form-groupe">
                    <label for="nom">Nom :</label>
                    <!-- On réinjecte ce que l'utilisateur a tapé dans value="..." -->
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                
                <div class="form-groupe">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                </div>

                <div class="form-groupe">
                    <label for="date_naissance">Date de naissance :</label>
                    <!-- Si $message_erreur_age n'est pas vide, on ajoute la classe 'erreur-champ' -->
                    <input type="date" id="date_naissance" name="date_naissance" 
                           value="<?php echo $date_naissance; ?>" 
                           class="<?php echo !empty($message_erreur_age) ? 'erreur-champ' : ''; ?>" required>
                    
                    <!-- On affiche le texte rouge seulement s'il y a une erreur sur la date -->
                    <?php if (!empty($message_erreur_age)): ?>
                        <span class="texte-erreur"><?php echo $message_erreur_age; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <input type="submit" value="Retenter l'ajout">
                </div>
            </form>
        </div>

    <?php elseif ($insertion_reussie): ?>
        <!-- CAS 2 : TOUT EST BON, ON AFFICHE LE SUCCÈS DANS UNE JOLIE CARTE -->
        <h1>Opération réussie</h1>
        <div class="resultat-carte">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($prenom); ?></p>
            <p><strong>Âge :</strong> <?php echo $age; ?> ans</p>
            <p><strong>Inscrit le :</strong> <?php echo $date; ?></p>
            <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
            <p style="color: #457B9D; font-weight: bold;">L'élève a été ajouté avec succès à la base de données.</p>
        </div>
        
    <?php else: ?>
        <!-- CAS 3 : ERREUR BASE DE DONNÉES -->
        <h1>Erreur technique</h1>
        <div class="resultat-carte" style="border-left-color: #E63946;">
            <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
            <p>Veuillez contacter l'administrateur.</p>
        </div>
    <?php endif; ?>

</body>
</html>