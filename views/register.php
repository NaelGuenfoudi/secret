    <!---La page d'inscription   !--->
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">
                    <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                        <div class="card-body">
                            <div class="text-center">
                            <img src="<?= ROOT . '/ressources/site/logo.png' ?>" class="text-center">

<h1 class="mb-4 pb-2 pb-md-0 mb-md-5">S'inscrire</h1>
<p>Utilisez votre adresse mail CESI pour créer un compte</p>
</div>


                            <form action="inscription" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <label class="form-label" for="firstname">Prénom</label>
                                            <input required type="text" id="firstname" name="firstname" class="form-control form-control" />
                                            <?php if (isset($params['err_firstname'])) { ?>
                                                <p class="text-danger"><?= $params['err_firstname'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <label class="form-label" for="lastname">Nom</label>
                                            <input required type="text" id="lastname" name="lastname" class="form-control form-control" />
                                            <?php if (isset($params['err_lastname'])) { ?>
                                                <p class="text-danger"><?= $params['err_lastname'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-outline">
                                            <label class="form-label" for="lastname">Civilité</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input required class="form-check-input" type="radio" name="gender" id="F" value="F" checked />
                                                    <label class="form-check-label" for="F">Madame</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input required class="form-check-input" type="radio" name="gender" id="M" value="M" />
                                                    <label class="form-check-label" for="M">Monsieur</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input required class="form-check-input" type="radio" name="gender" id="A" value="A" />
                                                    <label class="form-check-label" for="A">Autres </label>
                                                </div>
                                                <?php if (isset($params['err_gender'])) { ?>
                                                    <p class="text-danger"><?= $params['err_gender'] ?></p>
                                                <?php } ?>
                                            </div>
                                            </br>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <label class="form-label" for="address">Adresse</label>
                                            <input required type="text" id="address" name="address" class="form-control form-control" />
                                            <?php if (isset($params['err_address'])) { ?>
                                                <p class="text-danger"><?= $params['err_address'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">

                                        <div class="form-outline">
                                            <label class="form-label basicAutoComplete" for="city">Ville</label>
                                            <input required type="text" id="city" name="city" class="form-control form-control city_input" />
                                            <?php if (isset($params['err_city'])) { ?>
                                                <p class="text-danger"><?= $params['err_city'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4 pb-2">
                                        <div class="form-outline">
                                            <label class="form-label" for="mail">Adresse mail</label>
                                            <input required type="email" id="mail" name="mail" class="form-control form-control" />

                                            <?php if (isset($params['err_mail'])) { ?>
                                                <p class="text-danger"><?= $params['err_mail'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4 pb-2">
                                        <div class="form-outline">
                                            <label class="form-label" for="phone">Numéro de téléphone</label>
                                            <input required type="tel" id="phone" name="phone" class="form-control form-control" />
                                            <?php if (isset($params['err_phone'])) { ?>
                                                <p class="text-danger"><?= $params['err_phone'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4 pb-2">
                                        <div class="form-outline">
                                            <label class="form-label" for="password">Mot de passe</label>
                                            <input required type="password" id="password" name="password" class="form-control form-control" />
                                            <?php if (isset($params['err_password'])) { ?>
                                                <p class="text-danger"><?= $params['err_password'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4 pb-2">
                                        <div class="form-outline">
                                            <label class="form-label" for="password2">Confirmation du mot de passe</label>
                                            <input required type="password" id="password2" name="password2" class="form-control form-control" />
                                            <?php if (isset($params['err_password'])) { ?>
                                                <p class="text-danger"><?= $params['err_password'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-4 pb-2">
                                        <div class="form-outline">
                                            <label class="form-label" for="profile_image">Image de profil</label>
                                            <input type="file" id="profile_image" name="profile_image" class="form-control form-control" accept="image/*">
                                            <?php if (isset($params['err_profile_image'])) { ?>
                                                <p class="text-danger"><?= $params['err_profile_image'] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <input class="btn btn-dark text-warning" name="changePassword" type="submit" value="Valider" />
                                </div>
                                <?php
                                if (isset($params['errorMsg'])) {
                                    echo ('<p>' . $params['errorMsg'] . '</p>');
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?= SCRIPTS ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= SCRIPTS ?>js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?= SCRIPTS ?>/css/jquery-ui.min.css">
    <script src="<?= SCRIPTS ?>/js/city_autocomplete.js"></script>