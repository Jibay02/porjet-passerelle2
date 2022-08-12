<?php 

    session_start();

    require_once('src/option.php');

    if (isset($_SESSION['connect']) && $_SESSION['id']==1 && isset($_COOKIE['auth'])) {
        header('location: ArticlesView.php');
        exit();
    } else if (isset($_SESSION['connect']) && $_SESSION['id']!= 1 && isset($_COOKIE['auth'])){
        header('location: TestimonialsView.php');
        exit();
    }

    if(!empty($_POST['email']) && !empty($_POST['password'])){

        // Connect Bdd.
        require_once('src/connection.php');

        $email       = htmlspecialchars($_POST['email']);
        $password    = htmlspecialchars($_POST['password']);
       
        // Verif adresse mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: ConnectionView.php?error=1&message=**Votre adresse Email est invalide**');
        exit();
        }

        // Email existe ou non?
        $req = $bdd->prepare('SELECT COUNT(*) as numberEmail FROM users WHERE email = ?');
        $req->execute([$email]);

        while($emailVerification = $req->fetch()){
            if ($emailVerification['numberEmail'] != 1) {
                 header('location: ConnectionView.php?error=1&message=**Impossible de vous authentifier.**');
                 exit();
            }
        } 
        
        // Chiffrer les M.D.P.
        $password   = "aq1".sha1($password."123")."25";

        // Connecter utilisateur
        $req = $bdd->prepare('SELECT * FROM users WHERE email=?');
        $req->execute([$email]);

        while ($user = $req->fetch()) {
            if($password == $user['password']){
                $_SESSION['connect'] = 1;
                $_SESSION['email']   = $user['email'];
                $_SESSION['id']      = $user['id'];
                $_SESSION['password']= $user['password'];
                
                // Mise en place du Cookie
                if (isset($_POST['auto'])) {
                    setcookie('auth', $user['secret'],time() + 365*24*3600, '/', null, false, true );
                }

                header('location: ConnectionView.php?success=1&id='.$user['id']);
                exit();
            }
            
            else{
                header('location: ConnectionView.php?error=1&message=**Impossible de vous authentifier.**');
                exit();
            }
        }

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
    <title>Projet Passerelle N°2|Connection</title>
</head>

<body>
    <!-- Header -->
    <header class="p-4 text-center ">
    <!-- Gros titre -->
    <h1>Le projet passerelle 2</h1>
    <h2 class="pt-2 ">Allez on se connecte !! </h2>
    </header>

    <section class="Totale" >
        <div class="connexion float-end pe-3 pt-3">
            <span class="text-white p-2"> Connectez-vous vite !</span>
            

        </div>
        <!-- Section  -->
        <section >

       
            <!-- Formulaire d'inscription -->
            <div class="Formulaire container">
                    
                        <form action="ConnectionView.php" method="post" class=" text-white">
                            <p>On s'connecte ici :</p>
                            <input type="email" name="email" id="emai" class="form-control-sm opacity-75" placeholder="Email" ><br><br>       
                            <input type="password" name="password" id="password" class="form-control-sm opacity-75" placeholder="Mot de passe"><br><br>
                            <button type="submit" class="btn btn-info d-block opacity-75">Connexion</button><br>
                            <label id="option"><input type="checkbox" name="auto" checked />  Se souvenir de moi</label>
            
                        </form>
            </div>    
            

            <div class="connect_ok container text-white pt-3">
            <?php if(isset($_GET['error'])) {

            if(isset($_GET['message'])) {
                echo htmlspecialchars($_GET['message']);
            }


            } else if (isset($_GET['success']) && $_SESSION['id']==1) {
                // echo  'Vous êtes maintenant Connecté. <br> <a class="text-warning" href="BlogPageView.php"> Un petit coup d`oeil sur le blog ? </a>';
                header('location: ArticlesView.php');
                
            } 
            else if (isset($_GET['success']) && $_SESSION['id']>1) {
                // echo  'Vous êtes maintenant Connecté. <br> <a class="text-warning" href="BlogPageView.php"> Un petit coup d`oeil sur le blog ? </a>';
                header('location: TestimonialsView.php');
            } 
                ?> 
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