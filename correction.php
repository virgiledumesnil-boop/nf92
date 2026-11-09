<?php

$idseance = isset($_POST['seance']) ? $_POST['seance'] : '';
$ideleve = isset($_POST['eleve']) ? $_POST['eleve'] : '';

$erreur = false;
$insertion_reussie = false;



if (empty($idseance) || empty($ideleve)) {
$erreur = true;
    if (empty($idseance)){
        $erreur_type="seance";
    }
    elseif (empty($ideleve)){
        $erreur_type="eleve";
    }     
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM inscription WHERE `idseance`= '" . $idseance ."' AND `ideleve`='" . $ideleve . "'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $num_row = mysqli_num_rows($result);
            if($num_row >= 1) {
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
        
        $query = "INSERT INTO inscription (idseance, ideleve, note) VALUES ($idseance, $ideleve, NULL)";
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
        <title>Inscription à une séance</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if ($erreur): ?>

        <h1>Corriger les informations</h1>

        <div class="form-container">
            <form action="inscrire_eleve.php" method="post">
                <div class="form-group">

                <?php

                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    mysqli_set_charset($connect,"utf8");

                    $requete = "SELECT `ideleve`,`nom`,`prenom`,`dateNaiss`,`dateInscription` FROM eleves WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='eleve'>Eleves disponibles :</label>";
                        $classe_select = ($erreur_type == "eleve" || $erreur_type == "existe_deja") ? "class='erreur-champ'" : "";
                        echo "<select name='eleve' size='1' $classe_select required>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            $selected = ($row['ideleve']==$ideleve) ? "selected" : "";
                            echo "<option value='" . $row['ideleve'] . "' $selected>" . $row['prenom'] . " " . $row['nom'] . " naît le " . $row['dateNaiss'] . " Inscit le " . $row['dateInscription'] . "</option>";
                            
                        }
                        echo "</select>";
                        if ($erreur_type == "eleve"){echo "<span class='text-erreur'>Veuillez choisir un élève</span>";}
                        if ($erreur_type == "existe_deja"){echo "<span class='text-erreur'>Cet élève est déjà inscrit à cette séance</span>";}
                        echo "</div>";
                        echo "<div class='form-group'>";
                    }

                    $query = "SELECT `idseance`,`DateSeance`,`EffMax`,`Idtheme` FROM seances WHERE 1";
                    $resultat = mysqli_query($connect,$query);
                    if (!$resultat) {
                        die("Query failed: $query" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='seance'>Séances disponibles :</label>";
                        $classe_select = ($erreur_type == "seance") ? "class='erreur-champ'" : "";
                        echo "<select name='seance' size='1' $erreur_type required>";
                        while ($row = mysqli_fetch_assoc($resultat)) {

                            $idseance_option = $row['idseance'];
                            $EffMax = $row['EffMax'];
                            $date_seance = $row['DateSeance'];

                            $selected = ($idseance_option==$idseance)?"selected" : "";

                            $query_seance = "SELECT * FROM inscription WHERE `idseance` = $idseance_option";
                            $result_seance = mysqli_query($connect,$query_seance);
                            if (!$result_seance) {
                                die("Query failed: $query_seance" . mysqli_error($connect));
                            } 
                            else {
                                $nb_inscrit = mysqli_num_rows($result_seance);
                                $places_restantes = $EffMax - $nb_inscrit;

                                if (($nb_inscrit < $EffMax) && ($date_seance>=$date)) {

                                    $requete_theme = "SELECT `nom` FROM themes WHERE `idtheme` = '" . $row['Idtheme'] . "'";
                                    $resultat_theme = mysqli_query($connect,$requete_theme);
                                    if (!$resultat_theme) {
                                        die("Query failed: $requete_theme" . mysqli_error($connect));
                                    } 
                                    else {

                                        echo "<option value='" . $row['idseance'] . "'> Séance du " . $row['DateSeance'] . " sur le theme " . mysqli_fetch_assoc($resultat_theme)['nom'] . " (" . $places_restantes . " places restantes)</option>";
                                    }
                                }
                            }
                            
                        }
                        echo "</select>";
                        if ($erreur_type=="seance"){echo"<span class='erreu-text'>Veuillez selectioner une séance</span>";};
                        echo "</div>";
                    }

                
                }
                mysqli_close($connect);
                ?>
                <div class="form-actions">
                    <input type="submit" value="Corriger">
                    <input type="reset" value="Réinitialiser">
                </div>
            </form>

        </div>
        </body>
    <?php elseif ($insertion_reussie): ?>
        <h1>Opération réussie</h1>
        <div class="resultat-carte">


            <?php 
                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    $query_theme = "SELECT `nom` FROM themes WHERE idtheme=(SELECT `idtheme` FROM seances WHERE `idseance`=$idseance)";
                    $result_theme = mysqli_query($connect,$query_theme);
                    $theme = mysqli_fetch_assoc($result_theme);
                    $query_date = "SELECT `DateSeance` FROM seance WHERE idseance=$idseance";
                    $result_date = mysqli_query($connect,$query_date);
                    $date= mysqli_fetch_assoc($result_date);
                    $query_eleve = "SELECT `nom`,`prenom`,`dateNaiss` FROM eleves WHERE ideleve=$ideleve";
                    $result_eleve = mysqli_query($connect,$query_eleve);
                    $eleve = mysqli_fetch_assoc($result_eleve);
                    $nom = $eleve['nom'];
                    $prenom = $eleve['prenom'];
                    $date_naissance = $eleve['dateNaiss'];
                }

            ?>

            <p><strong>Theme :</strong> <?php echo htmlspecialchars($theme); ?></p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($date); ?></p>
            <p><strong>Eleve :</strong> <?php echo htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . " né le $date_naissance" ; ?></p>
            <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
            <p style="color: #457B9D; font-weight: bold;">L'élève a été inscrit avec succès à la séance.</p>
        </div>
    <?php else: ?>
        <h1>Erreur technique</h1>
        <div class="resultat-carte" style="border-left-color: #E63946;">
            <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
            <p>Veuillez contacter l'administrateur.</p>
        </div>
    <?php endif; ?>
</html>