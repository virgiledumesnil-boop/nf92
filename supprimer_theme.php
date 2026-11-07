<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Supprimer thème</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
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

            if (!isset($_POST['idtheme']) || !isset($_POST['supprime'])) {
                echo "<h1>Erreur</h1>";
                echo "<p>les données du formulaire ne sont pas définies.</p>";
            }
            else {

                $idtheme = $_POST['idtheme'];
                $supprime = $_POST['supprime'];

                $requete = "SELECT `nom`,`supprime` FROM themes WHERE idtheme='$idtheme'";
                $resultat = mysqli_query($connect,$requete);
                $theme = mysqli_fetch_assoc($resultat);

                echo "<h1>Thème à Supprimer :</h1>";
                echo "<p>Thème : $theme[nom]</p>";

                if ($theme['supprime']==1){
                    echo "<h1>Erreur</h1>";
                    echo "<p>Le thème '$theme[nom]' a déjà été supprimé de la base de données.</p>";
                    echo "<p>Vous pouvez le réactiver à tout moment dans 'Ajouter un thème'.</p>";
                    echo "<p><a href='suppression_theme.php'>Retour à la page de suppression de thème</a></p>";
                }
                else{

                    if ($supprime != 1) {
                        echo "<h1>Erreur</h1>";
                        echo "<p>Vous devez cocher la case pour confirmer la suppression du thème.</p>";
                        echo "<p><a href='suppression_theme.php'>Retour à la page de suppression de thème</a></p>";
                    } 
                    else {

                        $query = "UPDATE `themes` SET `supprime`= 1 WHERE idtheme = '$idtheme'";
                        $result = mysqli_query($connect,$query);                

                        if (!$result) {
                            die("Query failed: $query" . mysqli_error($connect));
                        } 
                        else {
                            echo "<h1>Thème Supprimé avec succès</h1>";
                            echo "<p>Le thème a été supprimé avec succès de la base de données.</p>";
                        }

                    
                    }
                }
            }
        }
        
        mysqli_close($connect);
        ?>
    </body>
</html>