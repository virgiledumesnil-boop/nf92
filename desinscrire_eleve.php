<?php

$identifiants = explode('-', $_POST['inscriptions']);

$idseance_form = $identifiants[0];
$ideleve_form  = $identifiants[1];

$erreur = false;
$suppression_reussie = false;



if (empty($idseance_form) || empty($ideleve_form)) {
$erreur = true;
    if (empty($idseance_form)){
        $erreur_type="seance";
    }
    elseif (empty($ideleve_form)){
        $erreur_type="eleve";
    }     
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM inscription WHERE `idseance`= $idseance_form AND `ideleve`=$ideleve_form";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $num_row = mysqli_num_rows($result);
            if($num_row == 0) {
                $erreur = true;
                $erreur_type = "pas_inscrit";
            }   
        }
        mysqli_close($connect);
    }
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $query = "DELETE FROM inscription WHERE `idseance`= $idseance_form AND `ideleve`=$ideleve_form";
        if (mysqli_query($connect, $query)) {
            $suppression_reussie = true;
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
                        $class_select = ($erreur) ? "class='erreur-champ'" : "";
                        echo "<select name='inscriptions' size='1' $class_select required>";
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
                            $selected = ($ideleve == $ideleve_form && $idseance == $idseance_form) ? "selected" : "";
                            echo "<option value ='$idseance-$ideleve' $selected>$prenom_eleve $nom_eleve né(e) le $date_naissance inscrit a la séance du $date_seance sur le thème '$nom_theme'</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                        if($erreur_type=="eleve"){echo"<span class='texte-erreur'>L'eleve n'as pas été identifié</span>";}
                        if($erreur_type=="seance"){echo"<span class='texte-erreur'>La séance n'as pas été identifié</span>";}
                        if($erreur_type=="pas_inscrit"){echo"<span class='texte-erreur'>L'élève n'est pas inscrit à la séance</span>";}
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
    <?php elseif ($suppression_reussie): ?>
        <h1>Opération réussie</h1>
        <div class="resultat-carte">


            <?php 
                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    $query_eleve = "SELECT * FROM eleves WHERE ideleve=$ideleve_form";
                    $result_eleve = mysqli_query($connect,$query_eleve);
                    $eleve=mysqli_fetch_assoc($result_eleve);
                    $nom_eleve = $eleve['nom'];
                    $prenom_eleve = $eleve['prenom'];
                    $date_naissance = $eleve['dateNaiss'];
                    
                    $query_seance = "SELECT * FROM seances WHERE idseance=$idseance_form";
                    $result_seance = mysqli_query($connect,$query_seance);
                    $seance = mysqli_fetch_assoc($result_seance);
                    $date_seance = $seance['DateSeance'];
                    $idtheme = $seance['Idtheme'];
 
                    $query_theme = "SELECT * FROM themes WHERE idtheme=$idtheme";
                    $result_theme = mysqli_query($connect,$query_theme);
                    $theme = mysqli_fetch_assoc($result_theme);
                    $nom_theme = $theme['nom'];
                }

            ?>

            <p><strong>Theme :</strong> <?php echo htmlspecialchars($nom_theme); ?></p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($date_seance); ?></p>
            <p><strong>Eleve :</strong> <?php echo htmlspecialchars($prenom_eleve) . " " . htmlspecialchars($nom_eleve) . " né(e) le $date_naissance" ; ?></p>
            <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
            <p style="color: #457B9D; font-weight: bold;">L'élève a été desinscrit de la séance avec succès.</p>
        </div>
    <?php else: ?>
        <h1>Erreur technique</h1>
        <div class="resultat-carte" style="border-left-color: #E63946;">
            <p>Impossible de se connecter à la base de données ou d'enregistrer l'élève.</p>
            <p>Veuillez contacter l'administrateur.</p>
        </div>
    <?php endif; ?>
</html>