<?php
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';

date_default_timezone_set('Europe/Paris');
$date = date('y-m-d');

$erreur = false;

if (!exist($nom) || !exist($prenom) || !exist($date_naissance) || ($age->y < 15) || ($age->y > 100) || ($date_naissance > $date)) {
$erreur = true;
    if (!existe($nom)){
        $erreur_type=nom;
    }
    if (!existe($prenom)){
        $erreur_type=prenom;
    }
    if (!exist($date_naissance)){
        $erreur_type=date;
    }
    if ($age->y < 15){
        $erreur_type=jeune;
    }
    if ($age->y > 100){
        $erreur_type=vieux;
    }
    if ($date_naissance > $date) {
        $erreur_type=futur;
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
                        <input class="<?php if($erreur_type=nom){echo"erreur-champ";} ?>" type="text" id="nom" name="nom" value="<?php htmlspecialchars($nom); ?>" required>
                        <?php if($erreur_type=nom){echo"<span class='texte-erreur'>Veuillez saisir un Nom</span>";} ?>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input class="<?php if($erreur_type=prenom){echo"erreur-champ";} ?>" type="text" id="prenom" name="prenom" value="<?php htmlspecialchars($prenom); ?>" required>
                        <?php if($erreur_type=prenom){echo"<span class='texte-erreur'>Veuillez saisir un Prenom</span>";} ?>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input class="<?php if($erreur_type=date || $erreur_type=jeune || $erreur_type=vieux || $erreur_type=futur){echo"erreur-champ";} ?>"type="date" id="date_naissance" name="date_naissance" value="<?php htmlspecialchars($date_naissance); ?>" required>
                        <?php if($erreur_type=date){echo"<span class='texte-erreur'>Veuillez saisir une date</span>";} ?>
                        <?php if($erreur_type=jeune){echo"<span class='texte-erreur'>Il faut avoir plus de 15 ans pour s'inscrire</span>";} ?>
                        <?php if($erreur_type=nom){echo"<span class='texte-erreur'>Il faut être âgé de moins de 100ans</span>";} ?>
                        <?php if($erreur_type=nom){echo"<span class='texte-erreur'>Veuillez saisir une date anterieure a aujourdhui</span>";} ?>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Ajouter l'élève">
                        <input type="reset" value="Réinitialiser">
                    </div>

                </form>
            </div>
        </body>
    <?php elseif ($insertion_reussie): ?>
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
        <h1>Erreur technique</h1>
        <div class="resultat-carte" style="border-left-color: #E63946;">
            <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
            <p>Veuillez contacter l'administrateur.</p>
        </div>
    <?php endif; ?>
</html>











<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajouter éleve</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
        $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
        $date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';
        date_default_timezone_set('Europe/Paris');
        $date=date('Y\-m\-d');
        $age = date_diff(date_create($date_naissance), date_create($date));

        if (!isset($nom) || !isset($prenom) || !isset($date_naissance) || ($age->y < 15) || ($age->y > 123) || ($date_naissance > $date)) {
            echo "<h1>Erreur</h1>";
            echo "<p>Les champs sont incomplets ou la date de naissance est incorrecte.</p>";
            echo "<p>Veuillez remplir tous les champs et vérifier que la date de naissance est correcte.</p>";
            echo "<p>Un élève doit avoir au moins 15 ans et au maximum 123 ans.</p>";
            echo "<p><a href='ajout_eleve.html'>Retour à la page d'ajout d'élève</a></p>";
        } 
        else {

            echo "<h1>Élève à ajouter</h1>";
            echo "<p>Nom : $nom</p>"; 
            echo "<p>Prénom : $prenom</p>";
            echo "<p>Date de naissance : $date_naissance</p>";
            echo "<p>Âge : $age->y ans</p>";
            echo "<p>Date d'inscription : $date</p>";

            $dbhost="tuxa.sme.utc";
            $dbname="nf92a022";
            $dbuser="nf92a022";
            $dbpass="yqtLMEgACs67";

            $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ("connection failed. contactez un administrateur du site");

            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
                echo "<p>contactez un administrateur du site</p>";
            }
            else {
                mysqli_set_charset($connect,"utf8");

                $querry = "INSERT INTO eleves (ideleve,nom, prenom, dateNaiss,dateInscription) VALUES (NULL,'$nom', '$prenom', '$date_naissance', '$date')";

                $result = mysqli_query($connect,$querry);

                if (!$result) {
                    die("Query failed: $query" . mysqli_error($connect));
                } else {
                    echo "<p>L'élève a été ajouté avec succès à la base de données.</p>";
                }
            
            }
            
            mysqli_close($connect);

        }

        ?>
    </body>
</html>