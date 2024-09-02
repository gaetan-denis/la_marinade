<h2>S'enregistrer</h2>
<div class="form-container">
    <form action="../scripts/validation_registration.php" method="post">
        <label for="username">Votre nom d’utilisateur : </label>
        <input type="text" name="username" id="username" required>
        <label for="email"">Votre email :</label>
        <input type="email" name="email"  id="email" required>
        <label for="password" >Votre mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <label for="confirm_password" >Confirmez votre mot de passe :</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <button type="submit">S'enregistrer</button>
    </form>
</div>