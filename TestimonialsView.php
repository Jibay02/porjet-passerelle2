<?php
    session_start();
    require_once('src/option.php');

    if (!isset($_SESSION['connect'])) {
        header('location: ConnectionView.php');
        exit();
}
    
    // Connection BDD
    require_once('src/connection.php');

    if (isset($_POST['titleTestimonial']) && isset($_POST['contentTestimonial'])) {
        
        if(!empty($_POST['titleTestimonial']) && !empty($_POST['contentTestimonial'])){
        
        // Variables + protection injection
        $titleTestimonial       = htmlspecialchars($_POST['titleTestimonial']);
        $contentTestimonial     = htmlspecialchars($_POST['contentTestimonial']);
        
        
        // On ajoute le titre et le commentaire ds la BDD
        $req = $bdd->prepare('INSERT INTO blogtestimonials (titleTestimonial, contentTestimonial) VALUES(?,?)');
        $req->execute([$titleTestimonial, $contentTestimonial]);

        header('location: TestimonialsView.php?success=1&message=Votre commentaire à bien été envoyé. Merci à très bientôt');
        exit();

        }
        else{
            // Affichage de l'erreur
            header('location: TestimonialsView.php?error=1&message=**Veuillez remplir tous les champs.**');
            exit();
        }
    }

    $article        = $bdd->query('SELECT * FROM blog ORDER BY publication_date DESC');
    $testimonials   = $bdd->query('SELECT * FROM blogtestimonials ORDER BY creation_date DESC');
    
    

    // require_once('logout.php');

    
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
    <title>Projet Passerelle N°2|Blog</title>
</head>

<body>
    <!-- Header -->
    <header class="p-4 text-center ">
    <!-- Gros titre -->
    <h1>Le projet passerelle 2</h1>
    <h2 class="pt-2 ">La Snes ou la Mégadrive ??</h2>
    </header>

    <section class="Totale" >
        <!-- Articles -->
        <div class="articles m-5 text-warning ">
        <?php while ($a = $article->fetch()) { 
        ?>
        <h3 class="h2 text-center text-decoration-underline fst-italic"><?= $a['title'] ?></h3>
        <p class="lh-base fs-5 fw-normal"><?= $a['content'] ?></p>
        <li class=" text-white"><a class="text-white" href="Edit.php?edit=<?=$a['id']?>">Modifier</a> | <a class="text-white" href="delete.php?edit=<?=$a['id']?>">Supprimer</a> </li>
        <hr class="solid">
        <?php
        }
        ?>
        </div>
        <div class="Formulaire container m-5">
                    
                        <form action="TestimonialsView.php" method="post" class="Testi text-white">
                            <p>Envie de me dire quelle est votre petite préférée?? <br>  Dites le moi avec un petit commentaire.</p>
                            <input type="text" name="titleTestimonial" id="titleTestimonial" class="form-control-sm opacity-75" placeholder="Titre de votre commentaire" ><br><br>       
                            <textarea type="text" rows="3" name="contentTestimonial" id="contentTestimonial" class="form-control-xl opacity-75 w-100 " placeholder="Ecrivez votre Avis"></textarea><br><br>
                            <button type="submit" class="btn btn-info d-block opacity-75">On Envoie</button><br>
                        </form>
            
                        <?php if(isset($_GET['error'])) {
                            if(isset($_GET['message'])) {
                                echo htmlspecialchars($_GET['message']);
                            }
                        }else if (isset($_GET['success'])) {
                            if(isset($_GET['message'])) {
                            echo htmlspecialchars($_GET['message']);
                            }
                        }?>        
        </div>  
        <div class="articles m-5 mt-2 text-white bg-dark opacity-75 ps-3 pb-3">
        <?php while ($b = $testimonials->fetch()) { 
        ?>
        <p class="pt-3"><?= $b['creation_date']?></p>
        <h4 class="h5 mt-1 text-decoration-underline fst-italic"><?= $b['titleTestimonial'] ?></h3>
        <p class="lh-base pb-2 fs-6 fst-italic"><?= $b['contentTestimonial'] ?></p>
        <?php
        }
        ?>
        </div>


        <!--Formulaire pour le Commentaire -->
        

        <!-- Disconect -->
        <?php 
        require('Disconect.php');
        ?>
    
    </section>    
    
    <!-- Footer -->
    <?php require('footer.php') ?>
 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>