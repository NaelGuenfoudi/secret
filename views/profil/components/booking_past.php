<div class="container shadow p-3 mb-5 bg-light rounded">
    <div class="row">

        <h2><?= "Le " . $booking['fullDateString']; ?> - Trajet terminé</h2>
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



    </div>
</div>