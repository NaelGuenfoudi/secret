<div class="row">
    <div class="col-12">
    <button id="consultDetail<?= $booking['id_booking_pk'] ?>" class="text-dark btn ">Voir les coordonnées du passager <i class="bi bi-info-circle-fill"></i> </button>
</div>
</div>
<div id="detailSection<?=$booking['id_booking_pk'] ?>"class="row hidden">
<div class="col-lg-4">
                <p><i class="bi bi-geo-alt-fill"></i><br> <?= $booking['address'] . ' ' . $booking['user_city'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-envelope"></i> <br><?= $booking['mail'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-telephone-fill"></i><br> <?= $booking['phone'] ?>
            </div>

</div>










<script>
    //affiche/cache les details sur une demande de réservation. Astuce pour pour cibler de manière unique chaque réservation. On concatene dans l'id des éléments l'id de reservation
    document.getElementById('consultDetail<?= $booking['id_booking_pk'] ?>').addEventListener('click', function () {
        document.getElementById('detailSection<?= $booking['id_booking_pk'] ?>').classList.toggle('hidden');
    });

</script>