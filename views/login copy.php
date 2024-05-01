
<h1>Connexion</h1>

    <!---La page de connexion   !--->

<br>
<p> Vous devez vous connecter pour accéder à la plateforme</p>
<form action="connexion" method="post">

    <!---Le formulaire de connexion  !--->
  <div class="form-group">
    <label for="mail">Adresse mail</label>
    <input type="email" class="form-control" id="mail" aria-describedby="emailHelp" name="mail" placeholder="nom@cesi.fr">
    
    <?php if(isset($params['mail'])){ ?>
      <p class="text-danger"><?= $params['mail'] ?></p>    
    <?php } ?> 
  </div>
  <div class="form-group">
    <label for="password">Mot de passe</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe">
    <?php if(isset($params['password'])){ ?>
      <p class="text-danger"><?= $params['password'] ?></p>    
    <?php } ?> 
</div>
  <button type="submit" class="btn btn-primary">Se connecter</button>
  <p>Vous n'avez pas encore de compte ? </p>
  <a href="inscription">S'inscrire</a>
</form>
