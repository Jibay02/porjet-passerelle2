<?php 

if (isset($_COOKIE['auth']) && !isset($_SESSION['connect'])) {

    // Connexion B.D.D.
    require_once('connection.php');

    // Variables
    $secret     = htmlspecialchars($_COOKIE['auth']);

    // Vérification du secret
    $req = $bdd->prepare('SELECT COUNT(*) AS secretNumber FROM users WHERE secret = ?');
    $req->execute([$secret]);

    while($user = $req->fetch()){

        if ($user['secretNumber'] == 1 ) {
             // Infos utilisateur
             $informations  = $bdd->prepare('SELECT * FROM users WHERE secret = ?');
             $informations->execute([$secret]);

             while ($userInformations = $informations->fetch()) {
                $_SESSION['connect']    = 1;
                $_SESSION['email']      = $userInformations['email'];
                $_SESSION['id']         = $userInformations['id'];
             }
        }

    //    if (isset($_GET['success']) && $_SESSION['id']==1) {
    //     // echo  'Vous êtes maintenant Connecté. <br> <a class="text-warning" href="BlogPageView.php"> Un petit coup d`oeil sur le blog ? </a>';
    //     header('location: ArticlesView.php');
    //     } 
    //     else if (isset($_GET['success']) && $_SESSION['id']>1) {
    //         // echo  'Vous êtes maintenant Connecté. <br> <a class="text-warning" href="BlogPageView.php"> Un petit coup d`oeil sur le blog ? </a>';
    //         header('location: TestimonialsView.php');
    //     } 

    }

    // header('location: BlogPageView.php');  
    
   
}