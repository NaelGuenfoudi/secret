<div class="container d-flex flex-column justify-content-center">
    <!--- Affichage d'un message de confirmation lors de modifications du profil !--->
    <?php if (isset($params['message']['success'])) {
        echo '<div class="text-center">';
        echo '<p class="alert alert-success">' . $params['message']['success'] . '</p>';
        echo '</div>';
    }
    ?>
    <!--- image de profil !--->
    <div class="text-center">

        <img src="<?= ROOT . '/ressources/uploads/profil/' . $params['user']['profil_image'] ?>" alt="Image de profil"
            class="rounded-circle profil-img">
        <br> <a href="#" id="editProfilImg" class="text-dark "><i class="bi bi-pencil-fill"></i></a>
        <a href="<?= ROOT ?>/mon_espace/profil/supprimer/img/<?= $params['user']['profil_image'] ?>" id="editProfilImg"
            class="text-dark  "><i class="bi bi-trash-fill "></i></a>


    </div>
    <!--- Formulaire pour téléverser une nouvelle image de profil !--->
    <form action="<?=ROOT?>/mon_espace/profil/sauvegarder/img" method="post" id="changeProfilImgForm"
        enctype="multipart/form-data" class="hidden">
        <div class="row align-items-center">

            <div class="col-md-11">
                <div class="form-outline">
                    <label class="form-label" for="profile_image">Image de profil</label>
                    <input type="file" required id="profile_image" name="profile_image"
                        class="form-control form-control" accept="image/*">
                </div>
            </div>
            <div class="col-md-1 align-self-end">

                <button class="btn text-dark mr-3" type="submit"><i class="bi bi-save-fill h3"></i></button>

            </div>
        </div>
    </form>
    <!--- Affichage des informations de l'utilisateur - formulaire éditable !--->
    <form action="<?=ROOT?>/mon_espace/profil/sauvegarder" method="post">
        <br>
        <div class="row justify-content-between">
    <div class="col-md-8">
        <h1 class="h4">Informations personnelles</h1>
    </div>
    <div class="col-md-2">
        <button class="btn btn-dark text-warning" type="submit" value="Valider">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy2-fill" viewBox="0 0 16 16">
                <path d="M12 2h-2v3h2z"></path>
                <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5V2.914a1.5 1.5 0 0 0-.44-1.06L14.147.439A1.5 1.5 0 0 0 13.086 0zM4 6a1 1 0 0 1-1-1V1h10v4a1 1 0 0 1-1 1zM3 9h10a1 1 0 0 1 1 1v5H2v-5a1 1 0 0 1 1-1"></path>
            </svg>
        </button>
    </div>
</div>

            <div class="row">
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="first_name">Prénom</label>
                    <input required type="text" id="first_name" value="<?= $params['user']['first_name'] ?>"
                        name="first_name" class="form-control form-control" />
                    <?php if (isset($params['message']['err_first_name'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_first_name'] ?>
                        </p>
                    <?php } ?>

                </div>

            </div>
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="last_name">Nom</label>
                    <input required type="text" id="last_name" name="last_name"
                        value="<?= $params['user']['last_name'] ?>" class="form-control form-control" />
                    <?php if (isset($params['message']['err_last_name'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_last_name'] ?>
                        </p>
                    <?php } ?>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-outline">

                    <label class="form-label" for="lastname">Civilité</label>
                    <div>
                        <?php
                        //code permettant de cocher le genre de la personne déjà présent en BDD
                        $genderList = [
                            'M' => 'Monsieur',
                            'F' => 'Madame',
                            'A' => 'Autre'
                        ];
                        
                        foreach ($genderList as $key => $gender) {
                            if ($key == $params['user']['gender']) {
                                echo '<div class="form-check form-check-inline">';
                                echo '<input required class="form-check-input" type="radio" name="gender" id="' . $key . '" value="' . $key . '" checked />';
                                echo '<label class="form-check-label" for="' . $key . '">' . $gender . '</label>';
                                echo '</div>';
                            } else {
                                echo '<div class="form-check form-check-inline">';
                                echo '<input required class="form-check-input" type="radio" name="gender" id="' . $key . '" value="' . $key . '" />';
                                echo '<label class="form-check-label" for="' . $key . '">' . $gender . '</label>';
                                echo '</div>';
                            }
                        }
                        ?>



                    </div>
                    <?php if (isset($params['message']['err_gender'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_gender'] ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <br>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-outline">
                    <label class="form-label" for="address">Adresse</label>
                    <input required type="text" value="<?= $params['user']['address'] ?>" id="address" name="address"
                        class="form-control" />
                    <?php if (isset($params['message']['err_address'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_address'] ?>
                        </p>
                    <?php } ?>
                </div>

            </div>
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label basicAutoComplete" for="city">Ville</label>
                    <input required type="text" id="city" name="city" value="<?= $params['user']['city_label'] ?>"
                        class="form-control city_input" />
                    <?php if (isset($params['message']['err_city'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_city'] ?>
                        </p>
                    <?php } ?>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4 pb-2">

                <div class="form-outline">
                    <label class="form-label " for="mail">Adresse mail</label>
                    <input readonly required type="email" id="mail" name="mail" value="<?= $params['user']['mail'] ?>"
                        class="form-control form-control-plaintext" />

                </div>
            </div>
            <div class="col-md-6 mb-4 pb-2">

                <div class="form-outline">
                    <label class="form-label" for="phone">Numéro de téléphone</label>
                    <input required type="tel" id="phone" name="phone" value="<?= $params['user']['phone'] ?>"
                        class="form-control" />
                    <?php if (isset($params['message']['err_phone'])) { ?>
                        <p class="text-danger">
                            <?= $params['message']['err_phone'] ?>
                        </p>
                    <?php } ?>
                </div>


            </div>
            <?php if($params['user']['account_statut'] == 0){
                echo '<p class="alert alert-danger">Vous n\'avez pas encore vérifié votre adresse mail</p>';
            }
            ?>

        </div>
    </form>


    <!--- Gestion du mot de passe !--->

    <h2 class="h4">Changer de mot de passe</h2>
    <form action="<?=ROOT?>/mon_espace/profil/sauvegarder/mdp" method="POST">
        <div class="row align-items-center">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input required type="password" id="password" name="password" class="form-control" />

                </div>
                <?php if (isset($params['message']['err_password'])) { ?>
                <p class="text-danger">
                    <?= $params['message']['err_password'] ?>
                </p>
                <?php } ?>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="password2">Confirmation du mot de passe</label>
                    <input required type="password" id="password2" name="password2" class="form-control " />

                </div>
                <?php if (isset($params['message']['err_password2'])) { ?>
                <p class="text-danger">
                    <?= $params['message']['err_password2'] ?>
                </p>
                <?php } ?>
            </div>
            <div class="col-md-2 align-self-end">
                <input class="btn btn-dark text-warning" name="changePassword" type="submit"
                    value="Valider" />
            </div>
        </div>
    </form>
    <br>
    <div class="row align-item-center text-center justify-content-center">
    <div class="col-4 ">
    <a href="<?=ROOT.'/deconnexion'?>" class="btn btn-danger">Se déconnecter </a>
                </div>
</div>
                </div>

<script src="<?=SCRIPTS?>js/jquery-3.7.1.min.js"></script>
<script src="<?=SCRIPTS?>js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?=SCRIPTS?>/css/jquery-ui.min.css">

<script src="<?=SCRIPTS?>/js/city_autocomplete.js"></script>

<script>
    //affiche/cache le formulaire d'upload d'une nouvelle image
    document.getElementById('editProfilImg').addEventListener('click', function () {
        document.getElementById('changeProfilImgForm').classList.toggle('hidden');
    });

</script>
