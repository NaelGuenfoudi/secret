<html>
<div class="container">
  <div class="row text-center m-4">

  </div>
</div>
<div class="container">
  <div class="card shadow border col-8 m-auto">

    <div class="card-body text-center">

      <img src="<?= ROOT . '/ressources/site/logo.png' ?>">

      <h4 class="title">Se connecter</h4>
      <div class="p-2">Veuillez vous identifier afin d'accéder à la plateforme</div>
      <form action="connexion" method="post" class="p-2">
        <div class="form-group pt-4">
          <label for="mail">Adresse mail</label>
          <input type="email" class="form-control" id="mail" aria-describedby="emailHelp" name="mail"
            placeholder="nom@cesi.fr" class="text-center" required>
          <?php if (isset($params['err_mail'])) { ?>
            <small class="text-danger"><?= $params['err_mail'] ?></small>
          <?php } ?>
        </div>
        <div class="form-group pt-4">
          <label for="password">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe"
            required>
          <?php if (isset($params['err_password'])) { ?>
            <small class="text-danger"><?= $params['err_password'] ?></small>
          <?php } ?>
        </div>
        <button type="submit" class="bg-black text-warning mt-4 mb-2 pt-1 pb-1 ps-4 pe-4 border-0">Se connecter</button>
        <p class="mt-2">Pas encore de compte ?</p>
        <a href="inscription" class="text-decoration-none link-dark">S'inscrire</a>
      </form>
    </div>
  </div>
</div>

</html>