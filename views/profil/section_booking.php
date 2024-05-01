<div class="container">
    <h1 class="text-center">Mes réservations</h1>
    <p class="text-center">Affiche la liste de vos réservations en tant que passager</p>

    <?php
    foreach ($params['bookingList'] as $booking) {
        if ($booking['d_start'] <= date(("Y-m-d H:i:s"))) {
            include ('components/booking_past.php');
        } else {
            include ('components/booking.php');
        }

    }


    ?>
    <div class="row">
        <div class="col-12 text-center">
            <a href="<?= ROOT ?>/mon_espace/reservations/true" class="text-dark btn">
                <i class="bi bi-clock-history h2"></i> Afficher les anciennes réservations
            </a>
        </div>
    </div>

</div>