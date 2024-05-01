<div class="container">
    <h1 class="text-center">Mes trajets</h1>


    <?php
    foreach ($params['rides'] as $ride) {

        include(__DIR__ . '/../components/ride_item.php');
    }


    ?>
    <div class="row">
        <div class="col-12 text-center">
            <a href="<?= ROOT ?>/mon_espace/trajet/ajouter" class="text-dark btn">
                <i class="bi bi-plus-circle-fill h1"></i>
            </a>
        </div>
    </div>

</div>