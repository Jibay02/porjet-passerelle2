<?php 

    session_start();
    require_once('src/option.php');

    if(!isset($_SESSION['connect'])) {
        header('location: ConnectionView.php');
        exit();
    }

    if(isset($_SESSION['connect']) && $_SESSION['id']!=1 && isset($_COOKIE['auth'])){
        header('location: TestimonialsView.php');
        exit();
    }

   // Connection BDD
    require('src/connection.php');
    
    // Verification si l'article existe pour afficher les articles à modifier
    if(!empty($_GET['edit'])){
            $edited_id = htmlspecialchars($_GET['edit']);
            
            $edit_article = $bdd->prepare('SELECT * FROM blog WHERE id = ?');
            $edit_article->execute([$edited_id]);
            
            if($edit_article -> rowCount() == 1) {
               $edit_article = $edit_article->fetch();
            }    
        } 
    
    if(isset($_POST['title']) && isset($_POST['content'])){
            if(!empty($_POST['title']) && !empty($_POST['content'])){
                require('src/connection.php');
            // Variables + protection injection
            $title     = htmlspecialchars($_POST['title']);
            $content   = htmlspecialchars($_POST['content']);
            
            // On met a jour la BDD
                $req = $bdd->prepare('UPDATE blog SET title= ?,content= ? WHERE id= ?');
                $req->execute([$title, $content, $edited_id]);
                header('location: ArticlesView.php?success=1&message=Votre article à bien été modifié. Merci à très bientôt');
                exit();
                
        }else{
            // Affichage de l'erreur
            header('location: Edit.php?error=1&message=**Veuillez remplir tous les champs.**');
            exit();
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
    <title>Projet Passerelle N°2|Blog</title>
</head>

<body>
    <!-- Header -->
    <header class="p-4 text-center ">
    <!-- Gros titre -->
    <h1>Le projet passerelle 2</h1>
    <h2 class="pt-2 ">Bon bah allez zou des articles alors!! </h2>
    </header>

    <section class="Totale" >

    <div class="connexion float-end pe-3 pt-3">
            <span class="text-white p-2">Juste envie de lire ce qui a été posté?</span>
            <a href="TestimonialsView.php" class="btn btn-outline-light pe-2 btn-sm">Cliquez ici.</a>

        </div>
        <!-- Articles -->
        <div class="Formulaire container">
                    
                        <form action="" method="post" class=" text-white">
                            <p> Envie de poster un article ? <br> C'est par ici:</p>
                            <input type="text" name="title" id="title" class="form-control-sm opacity-75" placeholder="Titre" value="<?= $edit_article['title']?>" /><br><br>       
                            <textarea type="text" rows="3" name="content" id="content" class="form-control opacity-75 w-100 " placeholder="Ecrivez votre article"><?= $edit_article['content']?></textarea><br><br>
                            <button type="submit" class="btn btn-info d-block opacity-75">On Envoie</button><br>
                        </form>
            
                        <?php if(isset($_GET['error'])) {
                            if(isset($_GET['message'])) {
                                echo htmlspecialchars($_GET['message']);
                            }
                        }else if (isset($_GET['success'])) {
                            if(isset($_GET['message'])) {
                            echo htmlspecialchars($_GET['message']).' <br> Vous pouvez découvrir les commentaires de nos utilisateurs en cliquant <a class="text-warning " href="TestimonialsView.php"> ICI</a>';
                            }
                        }?>                           
        </div>  

        <!-- Disconect -->
        <?php require('Disconect.php')?>
        
    </section>    
    
    <!-- Footer -->
    <?php require('footer.php')?>
 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>

