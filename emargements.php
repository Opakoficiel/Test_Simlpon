<?php
include('connexionBD.php'); // Fichier PHP contenant la connexion à notre BDD
$id_activite = (int)($_GET['id']);
// Si la variable "$_Post" contient des informations alors on les traitre
if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    // On se place sur le bon formulaire grâce au "name" de la balise "input"
    if (isset($_POST['Enregistrement'])) {
            $nom  = trim($_POST['nom']); // On récupère le nom
            $prenom = trim($_POST['prenom']); // on récupère le prénom
            $mail = strtolower(trim($_POST['mail'])); // On récupère le mail
            $numero2telephone = trim($_POST['numero2telephone']); // On récupère le numero de telephone;

            //  Vérification du nom
            if (empty($nom)) {
                $valid = false;
                $er_nom = ("Le nom ne peut pas être vide");
            }
            // On vérifit que le nom est dans le bon format
            elseif (!preg_match("/[A-Za-zéèà'ôîïöç_ùëûâ\-]+$/", $nom)) {
                $valid = false;
                $er_nom = "Le nom n'est pas valide";
            }


            //  Vérification du prénom
            if (empty($prenom)) {
                $valid = false;
                $er_prenom = ("Le(s) prénom(s) ne peut(peuvent) pas être(s) vide(s)");
            }
            // On vérifit que le prenom est dans le bon format
            elseif (!preg_match("/[A-Za-zéèà'ôîïöç_ùëûâ\-]+([ ]+[A-Za-zéèà'ôîïöç_ùëûâ\-]+)*$/", $prenom)) {
                
                $valid = false;
                $er_prenom = "Le(s) prénom(s) n'est(ne sont) pas valide(s)";
            }

            // Vérification du telephone
            if (empty($numero2telephone)) {
              $valid = false;
              $er_numero2telephone = "Le numéro de téléphone ne peut pas être vide";

              // On vérifit que le telephone est dans le bon format
          } elseif (!preg_match("/^0[1-9]+[0-9]{8}$/", $numero2telephone)) {
              $valid = false;
              $er_numero2telephone = "Le numéro de téléphone n'est pas dans le bon format";
          } else {
              // On vérifit que le telephone est disponible
              $req_numero2telephone = $DB->query(
                  "SELECT numero2telephone FROM participants 
                  JOIN participer ON id_participant = participant_id
                  JOIN activites ON id_activite = activite_id 
                  WHERE id_activite = $id_activite AND numero2telephone = ?",
                  array($numero2telephone)
              );

              $req_numero2telephone = $req_numero2telephone->fetch();
              /* var_dump($req_numero2telephone['numero2telephone']);
              die(); */

              if ($req_numero2telephone['numero2telephone'] <> "") {
                  $valid = false;
                  $er_numero2telephone = "Ce numéro de téléphone est déjà enregistré pour cette activité";
              }
          }

            // Vérification du mail
            if (empty($mail)) {
                $valid = false;
                $er_mail = "Le mail ne peut pas être vide";

                // On vérifit que le mail est dans le bon format
            } elseif (!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)) {
                $valid = false;
                $er_mail = "Le mail n'est pas valide";
            } else {
                // On vérifit que le mail est disponible
                $req_mail = $DB->query(
                  "SELECT email FROM participants 
                  JOIN participer ON id_participant = participant_id
                  JOIN activites ON id_activite = activite_id 
                  WHERE id_activite = $id_activite AND email = ?",
                  array($mail)
              );

                $req_mail = $req_mail->fetch();

                if ($req_mail['email'] <> "") {
                    $valid = false;
                    $er_mail = "Ce mail est déjà enregistré pour cette activité";
                }
            }

          // On insert nos données dans la table utilisateur
          if ($valid) {
            $DB->insert(
              "INSERT INTO participants(nom, prenom, numero2telephone, email) 
              VALUES (?, ?, ?, ?)",
              array($nom, $prenom, $numero2telephone, $mail));
              $id_participant = $DB->query(
                "SELECT id_participant FROM participants ORDER BY id_participant DESC LIMIT 1;")->fetchColumn(); // On conserve l'id du participant
              
            $DB->insert(
              "INSERT INTO participer(activite_id, participant_id) 
              VALUES (?, ?)",
              array($id_activite, $id_participant));
              }
            $nom  = "";
            $prenom = "";
            $mail = "";
            $numero2telephone = "";
                  
            }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="bootstrap.min.css">
  <title>Enregistrement</title>
</head>

<body>
<div class="container">

  <div class="row justify-content-center">

    <div class="col-md-6">
    
      <div class="card border-0 shadow-sm" style="border-radius: 6px;">
      
        <div class="card-body" style="padding: 32px;">
        
        <img src="images.png" height="32"> <br><br>
          
          <h2 class="mb-3" style="font-size:24px;font-weight:300;">Enregistrer participant</h2>
          <form method="post" action="" enctype="multipart/form-data">
          <div class="form-group">
            <?php if (isset($er_nom)) { ?>
              <div class="alert alert-danger"><?= $er_nom ?></div>
            <?php } ?>
            <input type="text" class="form-control" placeholder="Nom" name="nom" value="<?php if (isset($nom)) {echo $nom;} ?>" required>  
          </div>

          <div class="form-group">
            <?php if (isset($er_prenom)) { ?>
               <div class="alert alert-danger"><?= $er_prenom ?></div>
            <?php } ?>
            <input type="text" class="form-control" placeholder="Prénom(s)" name="prenom" value="<?php if (isset($prenom)) {echo $prenom;} ?>" required>
          </div>
          
          <div class="form-group">
          <?php if (isset($er_numero2telephone)) {?>
            <div class="alert alert-danger"><?= $er_numero2telephone ?></div>
          <?php }?>
            <input type="tel" class="form-control" placeholder="Numero de téléphone" name="numero2telephone" value="<?php if (isset($numero2telephone)) {echo $numero2telephone;} ?>" required>
          </div>
          <div class="form-group">
            <?php if (isset($er_mail)) { ?>
              <div class="alert alert-danger"><?= $er_mail ?></div>
            <?php } ?>
            <input type="email" class="form-control" placeholder="Adresse email" name="mail" value="<?php if (isset($mail)) {echo $mail;} ?>" required>
          </div>
          <button class="btn btn-outline-danger btn-block" name = "Enregistrement">Enregistrer</button>  
          <a class="btn btn-outline-success btn-lg" href="index.php" role="button">Terminer les enregistrements</a>
          </form>
        </div>
        
      </div>
  
    </div>

  </div>

</div>
</body>
</html>