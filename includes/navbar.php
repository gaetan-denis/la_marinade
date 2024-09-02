<nav>
    <ul>
        <li><a href="index.php?page=home.php">Accueil</a></li>
        <li><a href="index.php?page=new_recipes.php">Nouvelle recette</a></li>
        <li><a href="index.php?page=recipes.php">Les recettes</a></li>
        <?php
        if(isset($_SESSION['username'])){
            echo '<li><a href="#">Se déconnecter</a></li>';
        }else{
            echo '<li><a href="index.php?page=login.php">Se connecter</a></li>';
        }
        ?>
    </ul>
</nav>