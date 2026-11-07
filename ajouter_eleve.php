<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajouter éleve</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
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