<div class="container shadow p-3 mb-5 bg-white rounded">
    <div class="row ">

        <h2><?= "Le " . $booking['fullDateString']; ?></h2>
        <div class="col-lg-6 col-md-6 col-sm-12 ">
            <?php
            if ($booking['booking_status'] == "Validée") {
                echo '<span class="btn btn-success">Validée</span>';
            } elseif ($booking['booking_status'] == 'En attente') {
                echo '<span class="btn btn-warning">En attente</span>';
            } else {
                echo '<span class="btn btn-danger">Refusée</span>';
            }

            ?>
            <br>
            <b><i class="bi bi-circle-fill"></i> <?= $booking['startCityName']; ?></b>
            <p class="no-margin"><?= $booking['travelTime']; ?></p>
            <b><i class="bi bi-circle"></i> <?= $booking['endCityName']; ?></b>



        </div>
        <!-- Colonne pour les boutons de modification et de suppression -->
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <a href="<?= ROOT ?>/trajet/<?= $booking['id_ride_pk']; ?>" class="text-dark mr-3 btn"><i
                    class="bi bi-pencil-fill h3"></i></a>
            <a href="<?= ROOT ?>/reservations/supprimer/<?= $booking['id_ride_pk'].'/'.$booking['id_booking_pk']; ?>"
                class="text-dark btn"><i class="bi bi-trash-fill h3"></i></a>
        </div>
    </div>
    <hr class="hr hr-blurry">
    <div class="row">
        <div class="col-12">
            <img class="rounded-circle miniature-img"
                src="<?= ROOT . '/ressources/uploads/profil/' . $booking['profil_image'] ?>" alt="Image de profil">
            </img>
            <b><?= $booking['first_name'] . ' ' . $booking['last_name'] ?></b>
        </div>
    </div>


</div>