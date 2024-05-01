<?php include(VIEWS."header.php"); ?>

<div class="container-fluid mx-auto" style="width:80%">
    <div class="row justify-content-around shadow p-3 mb-5 bg-white rounded">
        <!-- Menu latéral (visible sur les appareils moyens et grands) -->
        <div class="col-md-4 col-lg-3 d-none d-md-block">
            <ul class="list-group text-center">
                <?php include(VIEWS."/profil/components/section_lateral_menu.php"); ?>
            </ul>
        </div>
        <!-- Bouton de menu hamburger (visible sur les appareils mobiles) -->
        <div class="col-12 col-md-4 col-lg-3 d-md-none text-center">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list h2"></i>
            </button>
            <!-- Menu hamburger -->
            <div class="collapse" id="mobileNav">
                <ul class="list-group text-center">
                    <?php include(VIEWS."/profil/components/section_lateral_menu.php"); ?>
                </ul>
            </div>
        </div>
        <!-- Section principale -->
        <div class="col-md-9 col-lg-7">
            <!-- Inclure le contenu de la section -->
            <?php include($params['section_name'] . '.php'); ?>
        </div>
    </div>
</div>




