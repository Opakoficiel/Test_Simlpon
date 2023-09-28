<?php

include('connexionBD.php'); 


// Récupération de l'id passer en argument dans l'URL
$id = (int) $_GET['id'];
$afficher_liste_participants = $DB->query("SELECT *FROM 
activites 
JOIN participer ON id_activite = activite_id
JOIN participants ON id_participant = participant_id  WHERE activite_id = $id  AND id_participant = participant_id");
$afficher_liste_participants = $afficher_liste_participants->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des participants</title>
    <!-- Ajout de Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Liste des participants</h1>
    <div class="my-5"></div>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Nom</th> 
                <th>Prénom(s)</th> 
                <th>Numéro de téléphone</th>
                <th>Adresse email</th> 
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($afficher_liste_participants as $ap){
            ?>
                <tr>          
                    <td><?= $ap['nom'] ?></td>
                    <td><?= $ap['prenom'] ?></td>
                    <td><?= $ap['numero2telephone'] ?></td>
                    <td><?= $ap['email'] ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<!-- Ajout de Bootstrap JS (optionnel) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
