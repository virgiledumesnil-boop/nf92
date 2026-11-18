<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Désinscrire un élève</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Désinscrire un élève d'une séance</h1>
        <div class="form-container">
            <form action="desinscrire_eleve.php" method="post">
                <div class="form-group">

                <?php

                date_default_timezone_set('Europe/Paris');
                $date = date('Y-m-d');
                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    mysqli_set_charset($connect,"utf8");

                    $query_inscription = "SELECT * FROM inscription WHERE 1";
                    $result_incription = mysqli_query($connect,$query_inscription);
                    if(!$result_incription) {
                        die("Query failed: $query_inscription" . mysqli_error($connect));
                    }
                    else{
                        echo "<label for='inscriptions'>Inscriptions :</label>";
                        echo "<select name='inscriptions' size='1' required>";
                        while($row = mysqli_fetch_assoc($result_incription)){
                            $idseance = $row['idseance'];
                            $ideleve = $row['ideleve'];

                            $query_seance = "SELECT * FROM seances WHERE idseance=$idseance";
                            $result_seance = mysqli_query($connect,$query_seance);
                            if(!$result_seance) {
                                die("Query failed: $query_seance" . mysqli_error($connect));
                            }
                            else{
                                $seance = mysqli_fetch_assoc($result_seance);
                                $idtheme = $seance['Idtheme'];
                                $date_seance = $seance['DateSeance'];
                            }

                            $query_theme = "SELECT * FROM themes WHERE idtheme=$idtheme";
                            $result_theme = mysqli_query($connect,$query_theme);
                            if(!$result_theme) {
                                die("Query failed: $query_theme" . mysqli_error($connect));
                            }
                            else{
                                $theme = mysqli_fetch_assoc($result_theme);
                                $nom_theme = $theme['nom'];
                            }

                            $query_eleve = "SELECT * FROM eleves WHERE ideleve=$ideleve";
                            $result_eleve = mysqli_query($connect,$query_eleve);
                            if(!$result_eleve) {
                                die("Query failed: $query_eleve" . mysqli_error($connect));
                            }
                            else{
                                $eleve = mysqli_fetch_assoc($result_eleve);
                                $nom_eleve = $eleve['nom'];
                                $prenom_eleve = $eleve['prenom'];
                                $date_naissance = $eleve['dateNaiss'];
                            }
                            if($date_seance >= $date){
                                echo "<option value ='$idseance-$ideleve'>" . htmlspecialchars($prenom_eleve) . " " . htmlspecialchars($nom_eleve) . " né(e) le " . htmlspecialchars($date_naissance) . " inscrit a la séance du " . htmlspecialchars($date_seance) . " sur le thème '" . htmlspecialchars($nom_theme) . "'</option>";
                            }
                        }
                        echo "</select>";
                        echo "</div>";

                    }
                }
                mysqli_close($connect);
                ?>
                <div class="form-actions">
                    <input type="submit" class="form-supprimer" value="Desinscrire">
                    <input type="reset" value="Réinitialiser">
                </div>
            </form>

        </div>
    </body>
</html>