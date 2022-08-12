<?php
    session_start();

    require_once('src/option.php');

    if (isset($_SESSION['connect'])) {
            header('location: ConnectionView.php');
            exit();
    }

    if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])){
       
        // Connect Bdd.
       require_once('src/connection.php');
    
        // Variables
        $email      = htmlspecialchars($_POST['email']);
        $password   = htmlspecialchars($_POST['password']);
        $password2  = htmlspecialchars($_POST['password_two']);

        // Verif M.D.P.
        if ($password != $password2) {
            header('location: index.php?error=1&message=**Vos mots de passes ne sont pas identiques**');
            exit();
        }

        // Verif adresse mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('location: index.php?error=1&message=**Votre adresse Email est invalide**');
            exit();
        }
        // Doublon email ?
        $req = $bdd->prepare('SELECT COUNT(*) as numberEmail FROM users WHERE email = ?');
        $req->execute([$email]);

        while($emailVerification = $req->fetch()){
            if ($emailVerification['numberEmail'] != 0) {
                 header('location: index.php?error=1&message=**Votre adresse email est déjà utilisée.**');
                 exit();
            }
        }

        // Chiffrer les M.D.P.
        $password   = "aq1".sha1($password."123")."25";

        // Secret
        $secret     = sha1($email).time();
        $secret     = sha1($secret).time();                

        // Ajouter Utilisateur
        $req = $bdd->prepare('INSERT INTO users (email, password, secret) VALUES(?,?,?)');
        $req->execute([$email, $password, $secret]);

        header('location: index.php?success=1');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Updock&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Public/design/default.css">
    <title>Projet Passerelle N°2</title>
</head>

<body>
    <!-- Header -->
    <header class="p-4 text-center ">
    <!-- Gros titre -->
    <h1>Le projet passerelle 2</h1>
    <h2 class="pt-2 ">Allez Zou !! </h2>
    </header>

    <section class="Totale" >
        <div class="connexion float-end pe-3 pt-3">
            <span class="text-white p-2">Déjà inscrit ? connectez vous ici :</span>
            <a href="ConnectionView.php" class="btn btn-outline-light pe-2 btn-sm">Connexion</a>

        </div>


        <!-- Section  -->
        <section >
 
            <!-- Formulaire d'inscription -->
            <div class="Formulaire container">
                    
                        <form action="index.php" method="post" class=" text-white">

                                <p>Inscrivez-vous ici :</p>
                                <input type="email" name="email" id="emai" class="form-control-sm opacity-75" placeholder="Email" ><br><br> 
                                <input type="password" name="password" id="password" class="form-control-sm opacity-75" placeholder="Mot de passe"><br><br>
                                <input type="password" name="password_two" id="password_two" class="form-control-sm opacity-75" placeholder="Retapez votre mot de passe"><br><br>
                                <button type="submit" class="btn btn-info d-block opacity-75">Envoyer</button>
                            
                        </form>
                
            </div>

            <div class="connect_ok container text-white pt-3">
            <?php if(isset($_GET['error']) && isset($_GET['message'])) {
                echo htmlspecialchars($_GET['message']);

            }else if (isset($_GET['success'])) {
                echo 'Vous êtes maintenant inscrit.<br> <a class="text-warning " href="connectionView.php"> Connectez-vous !</a>';
            } ?>
            </div>
<br><br><br><br><br><br><br><br><br><br>
        </section>
   
    </section>    

    <!-- Footer -->
    <?php require('footer.php') ?>
 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>