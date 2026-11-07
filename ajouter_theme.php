<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajouter theme</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        $nom = $_POST['nom'];
        $description = $_POST['description'];

        echo "<h1>Thème à ajouter :</h1>";
        echo "<p>Nom : $nom</p>"; 
        echo "<p>Description : $description</p>";

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

            $requete = "SELECT * FROM themes WHERE nom='$nom'";
            $resultat = mysqli_query($connect,$requete);
            if (mysqli_num_rows($resultat) > 0) {
                
                $supprime = mysqli_fetch_assoc($resultat)['supprime'];

                if ($supprime == 1) {
                    $query = "UPDATE `themes` SET `supprime`= 0, `descriptif`='$description' WHERE nom = '$nom'";
                    $result = mysqli_query($connect,$query);

                    if (!$result) {
                        die("Query failed: $query" . mysqli_error($connect));
                    } 
                    else {
                        echo "<h1>Thème réactivé avec succès</h1>";
                        echo "<p>Le thème '$nom' a été réactivé avec succès dans la base de données.</p>";
                        echo "<p><a href='ajouter_theme.php'>Retour à la page d'ajout de thème</a></p>";
                    }
                }
                else {
                    echo "<h1>Erreur</h1>";
                    echo "<p>Le thème '$nom' existe déjà dans la base de données.</p>";
                }

            }    
            else {

                $query = "INSERT INTO themes (idtheme, nom, supprime,descriptif) VALUES (NULL,'$nom', 0, '$description')";

                $result = mysqli_query($connect,$query);                

                if (!$result) {
                    die("Query failed: $query" . mysqli_error($connect));
                } 
                else {
                    echo "<h1>Thème ajouté avec succès</h1>";
                    echo "<p>Le thème '$nom' a été ajouté avec succès à la base de données.</p>";
                }
            }
        
        }
        
        mysqli_close($connect);
        ?>
    </body>
</html>