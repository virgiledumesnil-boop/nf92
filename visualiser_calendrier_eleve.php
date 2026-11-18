<?php
$ideleve = isset($_POST['theme']) ? interval($_POST['theme']) : '';
date_default_timezone_set('Europe/Paris');
$date_ajd = date('Y-m-d');

$erreur = false;
$lecture_reussie = false;

if (empty($ideleve)) {
    $erreur = true;
    $erreur_type = "ideleve";
}

if (!$erreur) {
    $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
    if ($connect) {
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM eleves WHERE ideleve='$ideleve'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            if (mysqli_num_rows($result)<1){
                $erreur = true;
                $erreur_type = "inexistant";
            }
            else{
                $eleve = mysqli_fetch_assoc($result);
                $prenom = $eleve['prenom'];
                $nom = $eleve['nom'];
                $date_naiss = $eleve['dateNaiss'];
                $date_ins = $eleve['dateInscription'];
                $age = date_diff(date_create($date_naiss),date_create($date_ajd));
                $lecture_reussie = True;
            }
        }
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM inscription WHERE ideleve='$ideleve'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $num_seances=mysqli_num_rows($result);
        }
        mysqli_close($connect);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Visualisation du calendrier d'un élève</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    </head>
    <body>
        <?php if ($erreur): ?>

        <h1>Corriger les informations</h1>

        <div class="form-container">

            <form action="visualiser_calendrier_eleve.php" method="post">

                <div class="form-group">

                <?php

                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");

                if (!$connect) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                else {
                    mysqli_set_charset($connect,"utf8");

                    $requete = "SELECT `ideleve`,`nom`,`prenom`,`dateNaiss` FROM eleves WHERE 1";
                    $resultat = mysqli_query($connect,$requete);
                    if (!$resultat) {
                        die("Query failed: $requete" . mysqli_error($connect));
                    } 
                    else {
                        echo "<label for='theme'>Élèves disponibles :</label>";
                        $classe_select = ($erreur) ? "class='erreur-champ'" : "";
                        echo "<select name='theme' size='1' $classe_select required>";
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            $selected = ($row['ideleve']==$ideleve) ? "selected" : "";
                            echo "<option value='" . $row['ideleve'] . "' $selected>" . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . " né le " . htmlspecialchars($row['dateNaiss']) . "</option>";
                        }
                        echo "</select>";
                    }
                
                }
                
                    if ($erreur_type=="ideleve"){echo"<span class='texte-erreur'>Veuillez selectioner un élève</span>";};
                    if ($erreur_type == "inexistant"){echo "<span class='texte-erreur'>l'élève n'existe pas dans la base de donnée</span>";}
                ?>
                    </div>


                    <div class="form-actions">
                        <input type="submit" value="Corriger">
                        <input type="reset" value="Réinitialiser">
                    </div>
            </form>
        </div>
        <?php elseif ($lecture_reussie): ?>
            <h1>Visualisation du calendrier de l'élève</h1>
            <div class="resultat-carte" style="max-width: 850px;">
                <p><strong>Prénom :</strong> <?php echo htmlspecialchars($prenom); ?></p>
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?></p>
                <p><strong>Date de naissance :</strong> <?php echo htmlspecialchars($date_naiss); ?></p>
                <p><strong>Âge :</strong> <?php echo htmlspecialchars($age->y); ?></p>
                <p><strong>Date d'Inscription :</strong> <?php echo htmlspecialchars($date_ins); ?></p>
                <?php
                $calendrier = [];

                $connect = mysqli_connect("tuxa.sme.utc", "nf92a022", "yqtLMEgACs67", "nf92a022");
                if ($connect) {
                    mysqli_set_charset($connect, "utf8");
                    
                    $query_inscr = "SELECT * FROM inscription WHERE ideleve='$ideleve'";
                    $res_inscr = mysqli_query($connect, $query_inscr);
                    
                    if ($res_inscr) {
                        while ($row = mysqli_fetch_assoc($res_inscr)) {
                            
                            $query_seance = "SELECT * FROM seances WHERE idseance='" . $row['idseance'] . "'";
                            $res_seance = mysqli_query($connect, $query_seance);
                            
                            if ($res_seance) {
                                $seance_info = mysqli_fetch_assoc($res_seance);
                                $date_seance = $seance_info['DateSeance'];
                                $idtheme = $seance_info['Idtheme'];
                                
                                if ($date_seance >= $date_ajd) {
                                    
                                    $query_theme = "SELECT * FROM themes WHERE idtheme='$idtheme'";
                                    $res_theme = mysqli_query($connect, $query_theme);
                                    
                                    if ($res_theme) {
                                        $theme_info = mysqli_fetch_assoc($res_theme);
                                        $calendrier[] = [
                                            'title' => $theme_info['nom'],
                                            'start' => $date_seance
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    mysqli_close($connect);
                }
                ?>

                <?php if (empty($calendrier)): ?>
                    <p><em>Aucune séance future programmée pour cet élève.</em></p>
                <?php else: ?>
                <div id="calendrier-eleve" style="margin-top: 20px;"></div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const zoneCalendrier = document.getElementById('calendrier-eleve');
                    const mesSeances = <?php echo json_encode($calendrier); ?>;
                    const calendar = new FullCalendar.Calendar(zoneCalendrier, {
                        initialView: 'dayGridMonth',
                        locale: 'fr',
                        firstDay: 1,
                        height: 'auto',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,listMonth'
                        },
                        buttonText: {
                            today: "Aujourd'hui",
                            month: 'Mois',
                            list: 'Liste'
                        },
                        events: mesSeances,
                        eventColor: '#0249E1'
                    });
                    calendar.render();
                });
                </script>
                <?php endif; ?>               

                <hr style="border: 0; border-top: 1px solid #e1e4e8; margin: 15px 0;">
                <p style="color: #457B9D; font-weight: bold;">Voici le calendrier de l'élève que vous avez sélectioné</p>
                <div class="back-home">
                    <a href="visualisation_calendrier_eleve.php" target="contenu" class="valider">Visualiser un autre calendrier</a>
                    <a href="accueil.html" target="contenu" class="continuer">Retour à l'accueil</a>
                </div>
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