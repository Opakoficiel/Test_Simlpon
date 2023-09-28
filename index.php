<?php
include('connexionBD.php'); // Fichier PHP contenant la connexion à notre BDD
$liste_type = $DB->query("select * from type_activites")->fetchAll();
// Si la variable "$_Post" contient des informations alors on les traitre
if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    // On se place sur le bon formulaire grâce au "name" de la balise "input"
    if (isset($_POST['Validation'])) {


            $type  = $_POST['type_']; // On récupère le type d'activité
            $date = $_POST['date'];
            $heure_debut = $_POST['heure_debut'];
            $heure_fin = $_POST['heure_fin'];
    }
     // On insert nos données dans la table utilisateur
     $DB->insert(
        "INSERT INTO activites(type_activite, date_, heure_debut, heure_fin) 
        VALUES (?, ?, ?, ?)",
        array($type, $date, $heure_debut, $heure_fin));
    // var_dump($DB);
    // die();
    $id = $DB->query(
        "SELECT id_activite FROM activites ORDER BY id_activite DESC LIMIT 1;")->fetchColumn(); // On conserve l'id de l'activité
    header('Location: emargements.php?id=' . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css">
    <title>Accueil</title>
</head>
<body>
<div class="card-body"   style="padding: 50px;">
    <div class="form-group">
        <img src="images.png" height="50"> <br> <br>
        <h3>Bonjour et bienvenue!!!</h3>
        <form method="post" action="" enctype="multipart/form-data">
        <label for="type_" class="control-label">Choisissez le type d'activité effectuée :</label>
        <select name="type_" id="type_" class="form-control">
            <?php
            foreach ($liste_type as $type_) : ?>
                <option value="<?= $type_["id_type_activite"]; ?>">
                    <?= $type_["type_"]; ?>
                </option>
            <?php endforeach ?>
        </select>
        <br>
        <div class="form-group">
            <label for="type_">Date</label> 
            <input type="date" class="form-control" max="<?php echo date('Y-m-d', mktime(0, null, null, null,  null, date('Y'))); ?>" name="date" value="<?php if (isset($date)) {echo $date;} ?>" required> <br>
          <div class="form-group">
          <label for="type_">Heure du début</label> 
            <input name ="heure_debut" type="time" class="form-control">
          </div>
          <div class="form-group">
          <label for="type_">Heure de la fin</label> 
            <input name ="heure_fin" type="time" class="form-control">
          </div>
        <button class="btn btn-outline-danger btn-block" name = "Validation">Valider</button> 
    </div>
    </form>
</div>
</body>
</html>

<?php
// Si la variable "$_GET" contient des informations alors on les traitre
if (!empty($_GET)) {
    extract($_GET);
    $valid = true;

    // On se place sur le bon formulaire grâce au "name" de la balise "input"
    if (isset($_GET['Rechercher'])) {
        $activitee_recherchee = $_GET['search'];
    }
    
    // On récupère tous les utilisateurs qui ont pour métier $activitee_rechercheer
    $afficher_activite = $DB->query("SELECT DISTINCT
        type_, id_activite, date_, heure_debut, heure_fin
        FROM activites 
        JOIN type_activites  ON type_activite = id_type_activite
        WHERE type_ LIKE '%$activitee_recherchee%'");

    $afficher_activite = $afficher_activite->fetchAll();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container mt-5">
    <h1>Voir les précédantes listes de participants</h1>
    <form class="form-inline mt-4" method="get">
        <div class="form-group mr-2">
            <input type="text" class="form-control" name="search" placeholder="Entrer le type d'activité" value="<?php echo $search; ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="Rechercher">Rechercher</button>
    </form>

    <div class="mt-5 mb-3"></div>
    <table class="table">
        <thead>
            <tr>
                <th>Activité</th> 
                <th>Date</th>
                <th>Heure du début</th> 
                <th>Heure de la fin</th>
                <th>Voir la liste des participants</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Foreach agit comme une boucle mais celle-ci s'arrête de façon intelligente. 
            // Elle s'arrête avec le nombre de lignes qu'il y a dans la variable afficher_activite

            // La variable afficher_activite est comme un tableau contenant plusieurs valeurs
            // pour lire chacune des valeurs distinctement on va mettre un pointeur que l'on appellera ici $ap
            foreach($afficher_activite as $ap){
            ?>
                <tr>          
                    <td><?= $ap['type_'] ?></td>
                    <td><?= $ap['date_'] ?></td>
                    <td><?= $ap['heure_debut'] ?></td>
                    <td><?= $ap['heure_fin'] ?></td>
                    <td><a href="voir_emargements.php?id=<?= $ap['id_activite'] ?>">Voir participants</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>



